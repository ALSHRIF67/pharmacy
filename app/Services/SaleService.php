<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use App\Repositories\BatchRepository;
use App\Repositories\ProductRepository;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use App\Events\SaleCompleted;

class SaleService
{
    protected $saleRepo;
    protected $batchRepo;
    protected $inventoryService;

    public function __construct(
        SaleRepository $saleRepo,
        BatchRepository $batchRepo,
        InventoryService $inventoryService
    ) {
        $this->saleRepo = $saleRepo;
        $this->batchRepo = $batchRepo;
        $this->inventoryService = $inventoryService;
    }

    public function processSale(array $items)
    {
        $sale = null;
        DB::transaction(function () use ($items, &$sale) {
            $sale = $this->saleRepo->create(['total_price' => 0]);
            $totalPrice = 0;

            foreach ($items as $item) {
                // Validate product existence and stock
                $product = $this->inventoryService->getProduct($item['product_id']);
                if (!$product) {
                    throw new \Exception("Product not found: ID {$item['product_id']}");
                }

                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $totalPrice += $this->processSaleItem($sale->id, $item);
            }

            $sale->update(['total_price' => $totalPrice]);
            
            event(new \App\Events\SaleCompleted($sale));
        });
        return $sale;
    }

    protected function processSaleItem(int $saleId, array $item)
    {
        $orderedQty = $item['quantity'];
        $quantity = $item['quantity'];
        $productId = $item['product_id'];
        $price = $item['price'];

        $batches = $this->batchRepo->getAvailableBatches($productId);

        $totalItemPrice = 0;
        foreach ($batches as $batch) {
            if ($quantity <= 0) break;

            $available = $batch->quantity;
            $decrement = min($quantity, $available);

            $this->inventoryService->removeStock(
                $productId,
                $batch->id,
                $decrement,
                'Sale',
                $saleId
            );

            $this->saleRepo->addItem($saleId, [
                'product_id' => $productId,
                'batch_id' => $batch->id,
                'quantity' => $decrement,
                'price' => $price,
            ]);

            $totalItemPrice += $decrement * $price;
            $quantity -= $decrement;
        }

        if ($quantity > 0) {
            throw new \Exception("Insufficient stock for product ID: {$productId}");
        }

        return $totalItemPrice;
    }
}
