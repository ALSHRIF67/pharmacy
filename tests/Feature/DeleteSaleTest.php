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
        $product = Product::factory()->create(['quantity' => 10]);

        $response = $this->postJson('/sales', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1, 'price' => 5]
            ]
        ]);

        $saleId = $response->json('sale.id');

        $this->delete("/sales/{$saleId}")->assertRedirect();

        $this->assertDatabaseMissing('sales', ['id' => $saleId]);
        $this->assertDatabaseMissing('sale_items', ['sale_id' => $saleId]);
    }
}
