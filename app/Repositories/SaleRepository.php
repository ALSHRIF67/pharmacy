<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class SaleRepository
{
    public function create(array $data)
    {
        return Sale::create([
            'total_price' => $data['total_price'],
            'created_at' => now(),
        ]);
    }

    public function addItem(int $saleId, array $itemData)
    {
        return SaleItem::create([
            'sale_id' => $saleId,
            'product_id' => $itemData['product_id'],
            'batch_id' => $itemData['batch_id'],
            'quantity' => $itemData['quantity'],
            'price' => $itemData['price'],
        ]);
    }

    public function findById(int $id)
    {
        return Sale::with('saleItems.product', 'saleItems.batch')->find($id);
    }
}
