<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SaleItem>
 */
class SaleItemFactory extends Factory
{
    protected $model = SaleItem::class;

    public function definition(): array
    {
        $unitPrice = $this->faker->randomFloat(2, 10, 500);
        $quantity = $this->faker->numberBetween(1, 3);

        return [
            'sale_id' => Sale::factory(),
            'product_id' => Product::factory(),
            'service_id' => null,
            'item_type' => 'product',
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount' => 0,
            'total' => $unitPrice * $quantity,
        ];
    }
}
