<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);

Route::prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PosController::class, 'index'])->name('index');
    Route::get('/table', [\App\Http\Controllers\PosController::class, 'table'])->name('table');
    Route::get('/create', [\App\Http\Controllers\PosController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\PosController::class, 'store'])->name('store');
    Route::get('/{id}', [\App\Http\Controllers\PosController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [\App\Http\Controllers\PosController::class, 'edit'])->name('edit');
    Route::put('/{id}', [\App\Http\Controllers\PosController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\PosController::class, 'destroy'])->name('destroy');
});

Route::resource('products', \App\Http\Controllers\ProductController::class);
Route::resource('categories', \App\Http\Controllers\CategoryController::class);
Route::resource('customers', \App\Http\Controllers\CustomerController::class);
Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
Route::resource('purchases', \App\Http\Controllers\PurchaseController::class)->only(['index', 'create', 'store', 'show']);

Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
Route::get('/sync', [\App\Http\Controllers\SyncController::class, 'index'])->name('sync.index');
Route::post('/sync/push', [\App\Http\Controllers\SyncController::class, 'push'])->name('sync.push');
