<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Batch;
use App\Models\StockMovement;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_checkout_flow_creates_sale_and_reduces_stock()
    {
        // Create a product and a batch with stock
        $product = Product::factory()->create(['base_price' => 50, 'quantity' => 0]);

        $batch = Batch::factory()->create([
            'product_id'   => $product->id,
            'expiry_date'  => now()->addMonths(6),
            'selling_price' => 50,
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'batch_id' => $batch->id,
            'type' => 'IN',
            'quantity' => 10,
            'created_at' => now(),
        ]);

        $payload = [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2, 'price' => 50],
            ],
        ];

        $response = $this->postJson('/api/sales', $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('sales', [
            'total_price' => 100.00,
        ]);

        $this->assertDatabaseHas('sale_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertEquals(8, $product->fresh()->quantity);
    }
}
