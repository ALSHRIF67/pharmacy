<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);

Route::get('/pos', function () {
    return view('pos.index');
})->name('pos.index');

Route::resource('products', \App\Http\Controllers\ProductController::class);
Route::resource('categories', \App\Http\Controllers\CategoryController::class);
Route::resource('customers', \App\Http\Controllers\CustomerController::class);
Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
Route::resource('purchases', \App\Http\Controllers\PurchaseController::class)->only(['index', 'create', 'store', 'show']);

Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
Route::get('/sync', [\App\Http\Controllers\SyncController::class, 'index'])->name('sync.index');
Route::post('/sync/push', [\App\Http\Controllers\SyncController::class, 'push'])->name('sync.push');
