<?php

namespace App\Services;

use App\Repositories\StockRepository;
use App\Repositories\ProductRepository;

class InventoryService
{
    protected $stockRepo;
    protected $productRepo;

    public function __construct(StockRepository $stockRepo, ProductRepository $productRepo = null)
    {
        $this->stockRepo = $stockRepo;
        $this->productRepo = $productRepo ?? new ProductRepository();
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

    public function getProduct(int $productId)
    {
        return $this->productRepo->findById($productId);
    }
}
