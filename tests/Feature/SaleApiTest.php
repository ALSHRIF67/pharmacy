<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Batch;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_process_sale_via_api()
    {
        $product = Product::factory()->create(['base_price' => 100]);
        $batch = Batch::factory()->create([
            'product_id' => $product->id,
            'expiry_date' => now()->addYear(),
            'selling_price' => 100
        ]);
        
        StockMovement::create([
            'product_id' => $product->id,
            'batch_id' => $batch->id,
            'type' => 'IN',
            'quantity' => 10
        ]);

        $payload = [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                    'price' => 100
                ]
            ]
        ];

        $response = $this->postJson('/api/sales', $payload);

        $response->assertStatus(200)
                 ->assertJsonPath('message', 'Sale processed successfully');

        $this->assertDatabaseHas('sales', ['total_price' => 500]);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'batch_id' => $batch->id,
            'type' => 'OUT',
            'quantity' => 5
        ]);
    }

    public function test_fails_if_insufficient_stock()
    {
        $product = Product::factory()->create();
        $batch = Batch::factory()->create([
            'product_id' => $product->id,
            'expiry_date' => now()->addYear()
        ]);
        
        StockMovement::create([
            'product_id' => $product->id,
            'batch_id' => $batch->id,
            'type' => 'IN',
            'quantity' => 2
        ]);

        $payload = [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                    'price' => 100
                ]
            ]
        ];

        $response = $this->postJson('/api/sales', $payload);

        $response->assertStatus(400);
    }
}
