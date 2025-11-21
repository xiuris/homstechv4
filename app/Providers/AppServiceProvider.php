<?php

namespace App\Providers;

use App\Services\Contracts\WhatsAppService;
use App\Services\WhatsApp\CloudWhatsAppService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(WhatsAppService::class, CloudWhatsAppService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('global', function ($request) {
            return Limit::perMinute(config('installer.rate_limit_per_minute', 120))
                ->by($request->ip())
                ->response(function () {
                    abort(429, 'Muitas requisições. Aguarde alguns segundos.');
                });
        });
    }
}
