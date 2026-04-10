<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('products')->group(function () {
    Route::get('/barcode/{barcode}', [ProductController::class, 'getByBarcode']);
    Route::post('/update', [ProductController::class, 'update']);
});

Route::post('/sales', [SaleController::class, 'store']);
