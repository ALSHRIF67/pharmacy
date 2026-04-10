<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Sale;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_sale_factory_creates_sale()
    {
        $sale = Sale::factory()->create();
        $this->assertDatabaseHas('sales', ['id' => $sale->id]);
    }
}
