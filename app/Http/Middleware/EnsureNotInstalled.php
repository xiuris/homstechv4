<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotInstalled
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $installed = (bool) config('installer.installed_flag') || file_exists(storage_path('app/installed'));

        if ($installed) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
