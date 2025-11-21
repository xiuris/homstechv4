<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureNotInstalled;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class InstallerController extends Controller
{
    public function __construct()
    {
        $this->middleware(EnsureNotInstalled::class);
    }

    public function index(Request $request): View
    {
        return view('install', [
            'requirements' => $this->requirements(),
            'defaults' => [
                'app_name' => config('app.name', 'Homstech'),
                'app_url' => $request->getSchemeAndHttpHost(),
                'db_host' => env('DB_HOST', '127.0.0.1'),
                'db_port' => env('DB_PORT', 3306),
                'db_database' => env('DB_DATABASE', 'homstech'),
                'db_username' => env('DB_USERNAME', 'root'),
                'company_name' => 'Homstech',
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:80'],
            'app_url' => ['required', 'url'],
            'db_driver' => ['required', 'in:mysql,sqlite'],
            'db_host' => ['required_if:db_driver,mysql', 'nullable', 'string'],
            'db_port' => ['required_if:db_driver,mysql', 'nullable', 'integer'],
            'db_database' => ['required', 'string'],
            'db_username' => ['required_if:db_driver,mysql', 'nullable', 'string'],
            'db_password' => ['nullable', 'string'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email'],
            'admin_password' => ['required', 'string', 'min:8'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_trade_name' => ['nullable', 'string', 'max:255'],
            'company_email' => ['nullable', 'email'],
        ]);

        $requirements = $this->requirements();
        if ($failed = collect($requirements)->firstWhere('passed', false)) {
            return back()->withErrors(['requirements' => "Requisito pendente: {$failed['label']}"])->withInput();
        }

        $dbConfig = $this->buildDatabaseConfig($validated);
        $this->testConnection($dbConfig);

        $envPath = config('installer.env_path');
        $this->writeEnvironment($envPath, $validated, $dbConfig);

        config(['app.name' => $validated['app_name'], 'app.url' => $validated['app_url']]);
        $this->reloadDatabaseConfig($dbConfig);

        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);

        $company = Company::first();
        if ($company) {
            $company->update([
                'name' => $validated['company_name'],
                'trade_name' => $validated['company_trade_name'] ?? $validated['company_name'],
                'email' => $validated['company_email'] ?? $company->email,
            ]);
        }

        $admin = User::updateOrCreate(
            ['email' => $validated['admin_email']],
            [
                'name' => $validated['admin_name'],
                'password' => Hash::make($validated['admin_password']),
                'company_id' => optional($company)->id,
            ]
        );

        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('Administrador');
        }

        file_put_contents(storage_path('app/installed'), now()->toIso8601String());

        return redirect()->route('home')->with('status', 'Instalação concluída com sucesso.');
    }

    private function requirements(): array
    {
        $extensions = config('installer.required_extensions');
        $checks = [
            [
                'label' => 'PHP >= '.config('installer.min_php'),
                'passed' => version_compare(PHP_VERSION, config('installer.min_php'), '>='),
            ],
            [
                'label' => 'Diretório storage gravável',
                'passed' => is_writable(storage_path()),
            ],
            [
                'label' => 'Arquivo .env gravável',
                'passed' => is_writable(dirname(config('installer.env_path'))),
            ],
        ];

        foreach ($extensions as $extension) {
            $checks[] = [
                'label' => "Extensão {$extension}",
                'passed' => extension_loaded($extension),
            ];
        }

        return $checks;
    }

    private function buildDatabaseConfig(array $data): array
    {
        if ($data['db_driver'] === 'sqlite') {
            $this->ensureSqliteFile($data['db_database']);

            return [
                'driver' => 'sqlite',
                'database' => $data['db_database'],
                'prefix' => '',
            ];
        }

        return [
            'driver' => 'mysql',
            'host' => $data['db_host'],
            'port' => $data['db_port'],
            'database' => $data['db_database'],
            'username' => $data['db_username'],
            'password' => $data['db_password'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];
    }

    private function testConnection(array $dbConfig): void
    {
        config(['database.connections.install' => $dbConfig]);
        DB::purge('install');
        DB::connection('install')->getPdo();
    }

    private function reloadDatabaseConfig(array $dbConfig): void
    {
        config(['database.connections.mysql' => $dbConfig]);
        DB::purge('mysql');
    }

    private function ensureSqliteFile(string $path): void
    {
        $directory = dirname($path);
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (! file_exists($path)) {
            touch($path);
        }
    }

    private function writeEnvironment(string $path, array $data, array $dbConfig): void
    {
        $envTemplate = @file_get_contents(base_path('.env.example')) ?: '';
        $appKey = 'base64:'.base64_encode(random_bytes(32));

        $envValues = [
            'APP_NAME' => $data['app_name'],
            'APP_ENV' => 'production',
            'APP_KEY' => $appKey,
            'APP_URL' => $data['app_url'],
            'APP_INSTALLED' => 'true',
            'DB_CONNECTION' => $dbConfig['driver'],
            'DB_HOST' => $dbConfig['driver'] === 'mysql' ? $dbConfig['host'] : '',
            'DB_PORT' => $dbConfig['driver'] === 'mysql' ? $dbConfig['port'] : '',
            'DB_DATABASE' => $dbConfig['database'],
            'DB_USERNAME' => $dbConfig['driver'] === 'mysql' ? $dbConfig['username'] : '',
            'DB_PASSWORD' => $dbConfig['driver'] === 'mysql' ? $dbConfig['password'] : '',
        ];

        $lines = explode("\n", $envTemplate);
        $lines = collect($lines)->map(function ($line) use ($envValues) {
            if (preg_match('/^([A-Z0-9_]+)=/', $line, $matches)) {
                $key = $matches[1];
                if (array_key_exists($key, $envValues)) {
                    return $key.'='.$envValues[$key];
                }
            }

            return $line;
        })->toArray();

        foreach ($envValues as $key => $value) {
            $hasKey = collect($lines)->contains(fn ($line) => str_starts_with($line, $key.'='));
            if (! $hasKey) {
                $lines[] = $key.'='.$value;
            }
        }

        if (! @file_put_contents($path, implode("\n", $lines))) {
            throw new FileNotFoundException("Não foi possível gravar {$path}");
        }
    }
}
