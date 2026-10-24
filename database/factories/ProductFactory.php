<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku'            => strtoupper(fake()->unique()->lexify('???-####')),
            'name'           => fake()->words(3, true),
            'category_id'    => Category::factory(),
            'supplier_id'    => null,
            'description'    => fake()->optional()->sentence(),
            'purchase_price' => fake()->numberBetween(5000, 500000),
            'selling_price'  => fake()->numberBetween(8000, 600000),
            'current_stock'  => fake()->numberBetween(0, 100),
            'minimum_stock'  => fake()->numberBetween(5, 20),
            'unit'           => fake()->randomElement(['pcs', 'kg', 'botol', 'karung', 'kotak']),
            'is_active'      => true,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_stock' => 0,
        ]);
    }

    public function withStock(int $stock): static
    {
        return $this->state(fn (array $attributes) => [
            'current_stock' => $stock,
        ]);
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_stock' => 3,
            'minimum_stock' => 10,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
