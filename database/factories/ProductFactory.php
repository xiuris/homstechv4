<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->words(3, true),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####')),
            'category' => fake()->randomElement(['Equipamentos', 'Software', 'Acessórios']),
            'description' => fake()->sentence(),
            'retail_price' => fake()->randomFloat(2, 50, 5000),
            'wholesale_price' => fake()->randomFloat(2, 40, 4000),
            'stock' => fake()->numberBetween(0, 200),
            'stock_minimum' => fake()->numberBetween(0, 20),
            'is_active' => true,
        ];
    }
}
