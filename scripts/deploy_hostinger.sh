#!/usr/bin/env bash
set -euo pipefail

php artisan storage:link --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force

php artisan queue:restart
php artisan schedule:run || true

echo "Deploy finalizado. Configure cron para queue:work e schedule:run."
