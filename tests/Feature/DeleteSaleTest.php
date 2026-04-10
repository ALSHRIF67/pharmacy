<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Sale;
use App\Models\Product;

class DeleteSaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_sale_removes_items()
    {
        $product = Product::factory()->create();

        $batch = \App\Models\Batch::factory()->create([
            'product_id' => $product->id,
            'expiry_date' => now()->addYear(),
            'selling_price' => 5,
        ]);

        \App\Models\StockMovement::create([
            'product_id' => $product->id,
            'batch_id' => $batch->id,
            'type' => 'IN',
            'quantity' => 10,
        ]);

        $response = $this->postJson('/api/sales', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1, 'price' => 5]
            ]
        ]);

        // Retrieve created sale id from DB to ensure route receives a valid id
        $sale = \App\Models\Sale::first();
        if (! $sale) {
            $this->fail('Sale was not created. Response: ' . $response->getContent());
        }

        $saleId = $sale->id;

        // Use API delete for predictable JSON response
        $del = $this->deleteJson("/api/sales/{$saleId}");
        if ($del->status() !== 200) {
            $this->fail('Delete failed. Response: ' . $del->getContent());
        }

        $this->assertDatabaseMissing('sales', ['id' => $saleId]);
        $this->assertDatabaseMissing('sale_items', ['sale_id' => $saleId]);
    }
}
