<?php

namespace Database\Factories;

use App\Models\Batch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Batch>
 */
class BatchFactory extends Factory
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
            'batch_number' => $this->faker->bothify('BN-####??'),
            'expiry_date' => $this->faker->dateTimeBetween('+1 month', '+2 years'),
            'cost_price' => $this->faker->randomFloat(2, 5, 200),
            'selling_price' => $this->faker->randomFloat(2, 210, 500),
        ];
    }
}
