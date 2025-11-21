<?php

return [
    'env_path' => env('INSTALLER_ENV_PATH', base_path('.env')),
    'installed_flag' => env('APP_INSTALLED', false),
    'min_php' => '8.2',
    'required_extensions' => ['pdo', 'pdo_mysql', 'openssl', 'mbstring', 'bcmath', 'json', 'fileinfo'],
    'upload_max_mb' => env('UPLOAD_MAX_MB', 10),
    'rate_limit_per_minute' => env('GLOBAL_RATE_LIMIT', 120),
];
