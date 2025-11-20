<?php

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    Config::set('installer.installed_flag', false);
    Config::set('installer.env_path', base_path('storage/framework/testing/install.env'));

    File::delete(Config::get('installer.env_path'));
    File::delete(storage_path('app/installed'));
});

it('renders installer page when not installed', function () {
    $this->get('/install')->assertOk()->assertSee('Instalador');
});

it('runs installer, writes env and creates admin user', function () {
    $dbPath = database_path('installer.sqlite');
    File::delete($dbPath);

    $payload = [
        'app_name' => 'Homstech Suite',
        'app_url' => 'http://localhost',
        'db_driver' => 'sqlite',
        'db_database' => $dbPath,
        'db_username' => 'root',
        'db_password' => '',
        'admin_name' => 'Root Admin',
        'admin_email' => 'root@example.test',
        'admin_password' => 'password123',
        'company_name' => 'Empresa Teste',
    ];

    $response = $this->post('/install', $payload);

    $response->assertRedirect(route('home'));
    expect(File::exists(storage_path('app/installed')))->toBeTrue();
    expect(File::exists(Config::get('installer.env_path')))->toBeTrue();

    $this->assertDatabaseHas('users', ['email' => 'root@example.test']);
    $admin = User::whereEmail('root@example.test')->first();
    expect($admin->hasRole('Administrador'))->toBeTrue();
});
