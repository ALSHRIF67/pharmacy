<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;

class InsufficientStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_fails_when_stock_not_enough()
    {
        $product = Product::factory()->create(['quantity' => 1, 'price' => 5]);

        $payload = [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2, 'price' => 5]
            ]
        ];

        $response = $this->postJson('/sales', $payload);
        $response->assertStatus(400);
    }
}
