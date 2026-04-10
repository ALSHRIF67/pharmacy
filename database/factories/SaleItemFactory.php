<?php

namespace Database\Factories;

use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SaleItem>
 */
class SaleItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sale_id' => \App\Models\Sale::factory(),
            'product_id' => \App\Models\Product::factory(),
            'batch_id' => \App\Models\Batch::factory(),
            'quantity' => $this->faker->randomFloat(2, 0.5, 10),
            'price' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
