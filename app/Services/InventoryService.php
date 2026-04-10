<?php

namespace App\Services;

use App\Repositories\StockRepository;

class InventoryService
{
    protected $stockRepo;

    public function __construct(StockRepository $stockRepo)
    {
        $this->stockRepo = $stockRepo;
    }

    public function addStock(int $productId, int $batchId, float $quantity, string $notes = '')
    {
        return $this->stockRepo->createMovement([
            'product_id' => $productId,
            'batch_id' => $batchId,
            'type' => 'IN',
            'quantity' => $quantity,
            'reference_type' => 'Manual Adjustment',
            'notes' => $notes,
        ]);
    }

    public function removeStock(int $productId, int $batchId, float $quantity, string $refType, int $refId)
    {
        return $this->stockRepo->createMovement([
            'product_id' => $productId,
            'batch_id' => $batchId,
            'type' => 'OUT',
            'quantity' => $quantity,
            'reference_type' => $refType,
            'reference_id' => $refId,
        ]);
    }
}
