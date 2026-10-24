<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockMovement>
 */
class StockMovementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reference_number' => 'TRX-' . fake()->unique()->numerify('##########'),
            'product_id'       => Product::factory(),
            'user_id'          => User::factory(),
            'type'             => 'in',
            'quantity'         => fake()->numberBetween(1, 50),
            'unit_cost'        => fake()->numberBetween(5000, 100000),
            'stock_before'     => 0,
            'stock_after'      => 10,
            'notes'            => fake()->optional()->sentence(),
            'occurred_at'      => now(),
        ];
    }
}
