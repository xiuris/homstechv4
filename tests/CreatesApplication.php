<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    public function createApplication(): Application
    {
        $basePath = __DIR__.'/..';

        $createdEnv = false;

        if (! file_exists($basePath.'/.env') && file_exists($basePath.'/.env.example')) {
            copy($basePath.'/.env.example', $basePath.'/.env');
            $createdEnv = true;
        }

        if (empty($_ENV['APP_KEY'] ?? null) && empty($_SERVER['APP_KEY'] ?? null)) {
            $key = 'base64:'.base64_encode(random_bytes(32));
            putenv("APP_KEY={$key}");
            $_ENV['APP_KEY'] = $_SERVER['APP_KEY'] = $key;
        }

        $app = require $basePath.'/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        if ($createdEnv) {
            register_shutdown_function(static function () use ($basePath): void {
                if (file_exists($basePath.'/.env')) {
                    @unlink($basePath.'/.env');
                }
            });
        }

        return $app;
    }
}
