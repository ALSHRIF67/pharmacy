<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Batch;
use App\Models\StockMovement;
use App\Services\InventoryService;
use App\Repositories\StockRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StockMovementIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_movements_are_recorded_correctly()
    {
        $product = Product::factory()->create();
        $batch = Batch::factory()->create(['product_id' => $product->id]);

        $stockRepo = new StockRepository();
        $inventoryService = new InventoryService($stockRepo);

        $inventoryService->addStock($product->id, $batch->id, 50, 'Initial load');

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'batch_id' => $batch->id,
            'type' => 'IN',
            'quantity' => 50,
            'reference_type' => 'Manual Adjustment'
        ]);

        $this->assertEquals(50, $batch->fresh()->quantity);
    }
}
