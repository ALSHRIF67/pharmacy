<?php

namespace Database\Factories;

use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'batch_id' => \App\Models\Batch::factory(),
            'type' => $this->faker->randomElement(['IN', 'OUT', 'ADJUST']),
            'quantity' => $this->faker->randomFloat(2, 1, 100),
            'reference_type' => 'Seeding',
            'reference_id' => $this->faker->randomNumber(4),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
