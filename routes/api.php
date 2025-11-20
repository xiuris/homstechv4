<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth.basic', 'permission:view platform status'])->get('/status', function () {
    return response()->json([
        'service' => config('app.name'),
        'status' => 'online',
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('api.status');
