<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'apiIndex']);
    Route::get('/barcode/{barcode}', [ProductController::class, 'getByBarcode']);
    Route::post('/update', [ProductController::class, 'apiUpdate']); // Fixed: was calling wrong 'update' method
});

Route::get('/products/search', function (Request $request) {
    $barcode = $request->query('barcode');
    $product = Product::where('barcode', $barcode)->first();

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    return response()->json($product);
});

Route::post('/sales', [SaleController::class, 'store']);
Route::delete('/sales/{sale}', [SaleController::class, 'destroy']);
