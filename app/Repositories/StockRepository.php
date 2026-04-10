<?php

namespace App\Repositories;

use App\Models\StockMovement;

class StockRepository
{
    public function createMovement(array $data)
    {
        return StockMovement::create([
            'product_id' => $data['product_id'],
            'batch_id' => $data['batch_id'],
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'created_at' => now(),
        ]);
    }

    public function getHistory(int $productId)
    {
        return StockMovement::where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
