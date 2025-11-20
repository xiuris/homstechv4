<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AccountPayableController;
use App\Http\Controllers\AccountReceivableController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
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
    Route::resource('receivables', AccountReceivableController::class)->only(['index', 'create', 'store'])->middleware('permission:manage finances');
    Route::resource('payables', AccountPayableController::class)->only(['index', 'create', 'store'])->middleware('permission:manage finances');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:manage finances');
    Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel')->middleware('permission:manage finances');
    Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf')->middleware('permission:manage finances');
    Route::get('pos', [PosController::class, 'create'])->name('pos.create')->middleware('permission:manage sales');
    Route::post('pos', [PosController::class, 'store'])->name('pos.store')->middleware('permission:manage sales');
    Route::get('pos/{sale}', [PosController::class, 'show'])->name('pos.show')->middleware('permission:manage sales');
    Route::post('pos/{sale}/complete', [PosController::class, 'complete'])->name('pos.complete')->middleware('permission:manage sales');
});
