<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;

class BarcodeLookupTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_lookup_product_by_barcode()
    {
        $product = Product::factory()->create([
            'barcode' => '1234567890123',
            'name' => 'Test Medicine'
        ]);

        $response = $this->getJson('/api/products/barcode/1234567890123');

        $response->assertStatus(200)
                 ->assertJsonPath('name', 'Test Medicine');
    }

    public function test_returns_404_for_invalid_barcode()
    {
        $response = $this->getJson('/api/products/barcode/9999999999999');

        $response->assertStatus(404);
    }
}
