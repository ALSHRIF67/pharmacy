<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Batch;
use App\Models\StockMovement;
use App\Services\SaleService;
use App\Services\InventoryService;
use App\Repositories\SaleRepository;
use App\Repositories\BatchRepository;
use App\Repositories\StockRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FEFOAlgorithmTest extends TestCase
{
    use RefreshDatabase;

    protected $saleService;

    protected function setUp(): void
    {
        parent::setUp();

        $stockRepo = new StockRepository();
        $inventoryService = new InventoryService($stockRepo);
        $batchRepo = new BatchRepository();
        $saleRepo = new SaleRepository();

        $this->saleService = new SaleService($saleRepo, $batchRepo, $inventoryService);
    }

    public function test_earliest_expiry_is_used_first()
    {
        $product = Product::factory()->create();

        // Expiring in 1 month
        $batch1 = Batch::factory()->create([
            'product_id' => $product->id,
            'expiry_date' => now()->addMonth(),
            'selling_price' => 100
        ]);
        StockMovement::create([
            'product_id' => $product->id,
            'batch_id' => $batch1->id,
            'type' => 'IN',
            'quantity' => 10
        ]);

        // Expiring in 2 months
        $batch2 = Batch::factory()->create([
            'product_id' => $product->id,
            'expiry_date' => now()->addMonths(2),
            'selling_price' => 100
        ]);
        StockMovement::create([
            'product_id' => $product->id,
            'batch_id' => $batch2->id,
            'type' => 'IN',
            'quantity' => 10
        ]);

        // Sale of 15 items
        $this->saleService->processSale([
            ['product_id' => $product->id, 'quantity' => 15, 'price' => 100]
        ]);

        // Verify Batch 1 is empty (deducted 10)
        $this->assertEquals(0, $batch1->fresh()->quantity);
        // Verify Batch 2 has 5 left (deducted 5)
        $this->assertEquals(5, $batch2->fresh()->quantity);
    }

    public function test_expired_batches_are_skipped()
    {
        $product = Product::factory()->create();

        // Expired batch
        $expiredBatch = Batch::factory()->create([
            'product_id' => $product->id,
            'expiry_date' => now()->subMonth(),
            'selling_price' => 100
        ]);
        StockMovement::create([
            'product_id' => $product->id,
            'batch_id' => $expiredBatch->id,
            'type' => 'IN',
            'quantity' => 10
        ]);

        // Valid batch
        $validBatch = Batch::factory()->create([
            'product_id' => $product->id,
            'expiry_date' => now()->addMonth(),
            'selling_price' => 100
        ]);
        StockMovement::create([
            'product_id' => $product->id,
            'batch_id' => $validBatch->id,
            'type' => 'IN',
            'quantity' => 10
        ]);

        // Sale of 5 items
        $this->saleService->processSale([
            ['product_id' => $product->id, 'quantity' => 5, 'price' => 100]
        ]);

        // Valid batch should have 5 left (deducted 5)
        $this->assertEquals(5, $validBatch->fresh()->quantity);
        // Expired batch should still have 10
        $this->assertEquals(10, $expiredBatch->fresh()->quantity);
    }
}
