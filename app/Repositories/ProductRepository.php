<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public $model = Product::class;

    public function findByBarcode(string $barcode)
    {
        return Product::where('barcode', $barcode)->first();
    }

    public function findById(int $id)
    {
        return Product::find($id);
    }

    public function updateByBarcode(string $barcode, array $data)
    {
        return Product::updateOrCreate(['barcode' => $barcode], $data);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }
}
