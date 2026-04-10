<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function createPurchase(array $data)
    {
        return DB::transaction(function () use ($data) {
            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'total_amount' => $data['total_amount'],
                'purchase_date' => $data['purchase_date'],
                'sync_status' => 'pending',
            ]);

            foreach ($data['items'] as $item) {
                // 1. Create or find batch
                $batch = Batch::create([
                    'product_id' => $item['product_id'],
                    'batch_number' => $item['batch_number'],
                    'expiry_date' => $item['expiry_date'],
                    'cost_price' => $item['cost_price'],
                    'selling_price' => $item['selling_price'],
                    'sync_status' => 'pending',
                ]);

                // 2. Create purchase item
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'batch_id' => $batch->id,
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                ]);

                // 3. Update stock
                $this->inventoryService->addStock(
                    $item['product_id'],
                    $batch->id,
                    $item['quantity'],
                    "Purchase ID: {$purchase->id}"
                );
            }

            return $purchase;
        });
    }
}
