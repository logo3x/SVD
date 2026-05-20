<?php

namespace Database\Factories;

use App\Enums\ProductCategory;
use App\Enums\ProductUnit;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => strtoupper(fake()->unique()->bothify('???-####')),
            'name' => fake()->words(2, true),
            'description' => fake()->optional()->sentence(),
            'category' => fake()->randomElement(ProductCategory::cases())->value,
            'unit' => fake()->randomElement(ProductUnit::cases())->value,
            'default_price' => fake()->numberBetween(1000, 80000),
            'is_default_for_new_clients' => true,
            'is_active' => true,
        ];
    }
}
