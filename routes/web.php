<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth.basic', 'permission:view platform status'])->get('/status', function () {
    return response()->json([
        'app' => config('app.name'),
        'status' => 'online',
        'checked_at' => now()->toIso8601String(),
    ]);
})->name('status');

Route::middleware(['auth.basic'])->group(function () {
    Route::resource('customers', CustomerController::class)->middleware('permission:manage customers');
    Route::resource('products', ProductController::class)->middleware('permission:manage products');
    Route::resource('services', ServiceController::class)->middleware('permission:manage services');
});
