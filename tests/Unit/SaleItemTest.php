<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\SaleItem;

class SaleItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_sale_item_factory_creates_item()
    {
        $item = SaleItem::factory()->create();
        $this->assertDatabaseHas('sale_items', ['id' => $item->id]);
    }
}
