<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth.basic', 'permission:view platform status'])->get('/status', function () {
    return response()->json([
        'app' => config('app.name'),
        'status' => 'online',
        'checked_at' => now()->toIso8601String(),
    ]);
})->name('status');
