<?php

use App\Http\Controllers\AccountPayableController;
use App\Http\Controllers\AccountReceivableController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FiscalDocumentController;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\InsightController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\WarrantyController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware('not_installed')->group(function () {
    Route::get('/install', [InstallerController::class, 'index'])->name('install.index');
    Route::post('/install', [InstallerController::class, 'store'])->name('install.store');
});

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
    Route::resource('warranties', WarrantyController::class)->only(['index', 'create', 'store'])->middleware('permission:manage warranties');
    Route::resource('appointments', AppointmentController::class)->only(['index', 'create', 'store', 'update'])->middleware('permission:manage scheduling');
    Route::get('insights', [InsightController::class, 'index'])->name('insights.index')->middleware('permission:manage alerts');
    Route::post('insights', [InsightController::class, 'store'])->name('insights.store')->middleware('permission:manage alerts');
    Route::resource('receivables', AccountReceivableController::class)->only(['index', 'create', 'store'])->middleware('permission:manage finances');
    Route::resource('payables', AccountPayableController::class)->only(['index', 'create', 'store'])->middleware('permission:manage finances');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:manage finances');
    Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel')->middleware('permission:manage finances');
    Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf')->middleware('permission:manage finances');
    Route::get('pos', [PosController::class, 'create'])->name('pos.create')->middleware('permission:manage sales');
    Route::post('pos', [PosController::class, 'store'])->name('pos.store')->middleware('permission:manage sales');
    Route::get('pos/{sale}', [PosController::class, 'show'])->name('pos.show')->middleware('permission:manage sales');
    Route::post('pos/{sale}/complete', [PosController::class, 'complete'])->name('pos.complete')->middleware('permission:manage sales');
    Route::get('pos/order-services/search', [PosController::class, 'orderServices'])
        ->name('pos.order-services.search')
        ->middleware(['permission:manage sales', 'permission:pdv.invoice_os']);

    Route::get('fiscal-documents', [FiscalDocumentController::class, 'index'])->name('fiscal-documents.index')->middleware('permission:manage integrations');
    Route::get('fiscal-documents/create', [FiscalDocumentController::class, 'create'])->name('fiscal-documents.create')->middleware('permission:manage integrations');
    Route::post('fiscal-documents', [FiscalDocumentController::class, 'store'])->name('fiscal-documents.store')->middleware('permission:manage integrations');
    Route::get('fiscal-documents/{fiscalDocument}', [FiscalDocumentController::class, 'show'])->name('fiscal-documents.show')->middleware('permission:manage integrations');
    Route::get('fiscal-documents/{fiscalDocument}/xml', [FiscalDocumentController::class, 'downloadXml'])->name('fiscal-documents.download.xml')->middleware('permission:manage integrations');
    Route::get('fiscal-documents/{fiscalDocument}/pdf', [FiscalDocumentController::class, 'downloadPdf'])->name('fiscal-documents.download.pdf')->middleware('permission:manage integrations');
});
