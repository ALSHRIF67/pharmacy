<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;

class SaleFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_sale_and_reduce_stock()
    {
        // Create product with zero initial quantity; stock will be added via StockMovement
        $product = Product::factory()->create(['base_price' => 5]);

        // Create a batch and add stock so FEFO/batch selection works
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

        $payload = [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2, 'price' => 5]
            ]
        ];

        // Use API endpoint for programmatic sale processing
        $response = $this->postJson('/api/sales', $payload);
        $response->assertStatus(200)->assertJson(['message' => 'Sale processed successfully']);

        $this->assertDatabaseHas('sales', []);
        $this->assertDatabaseHas('sale_items', ['product_id' => $product->id, 'quantity' => 2]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 8]);
    }
}
