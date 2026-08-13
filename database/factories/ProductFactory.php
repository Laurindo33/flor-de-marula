<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 100000),
            'sku' => 'FM-' . strtoupper(Str::random(6)),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(5000, 30000),
            'compare_price' => null,
            'image_path' => 'images/products/serum-facial.png',
            'stock' => 50,
            'stock_minimo' => 5,
            'is_featured' => false,
            'is_best_seller' => false,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
