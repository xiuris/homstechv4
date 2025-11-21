<?php

namespace Database\Factories;

use App\Models\OrderService;
use App\Models\OrderServiceItem;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderServiceItem>
 */
class OrderServiceItemFactory extends Factory
{
    protected $model = OrderServiceItem::class;

    public function definition(): array
    {
        $isProduct = $this->faker->boolean();
        $quantity = $this->faker->numberBetween(1, 3);

        if ($isProduct) {
            $product = Product::factory()->create();
            $price = $product->retail_price;
        } else {
            $service = Service::factory()->create();
            $price = $service->price;
        }

        return [
            'order_service_id' => OrderService::factory(),
            'item_type' => $isProduct ? 'product' : 'service',
            'product_id' => $isProduct ? $product->id : null,
            'service_id' => $isProduct ? null : $service->id,
            'quantity' => $quantity,
            'unit_price' => $price,
            'discount' => 0,
            'total' => $price * $quantity,
        ];
    }
}
