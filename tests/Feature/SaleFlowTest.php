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
        $product = Product::factory()->create(['quantity' => 10, 'price' => 5]);

        $payload = [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2, 'price' => 5]
            ]
        ];

        $response = $this->postJson('/sales', $payload);
        $response->assertStatus(200)->assertJson(['message' => 'Sale processed successfully']);

        $this->assertDatabaseHas('sales', []);
        $this->assertDatabaseHas('sale_items', ['product_id' => $product->id, 'quantity' => 2]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 8]);
    }
}
