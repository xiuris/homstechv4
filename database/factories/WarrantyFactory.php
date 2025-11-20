<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Service;
use App\Models\Warranty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warranty>
 */
class WarrantyFactory extends Factory
{
    protected $model = Warranty::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'customer_id' => Customer::factory(),
            'order_service_id' => null,
            'sale_id' => null,
            'product_id' => Product::factory(),
            'service_id' => Service::factory(),
            'starts_at' => now(),
            'expires_at' => now()->addMonths(12),
            'status' => fake()->randomElement(['active', 'expired', 'cancelled']),
            'notes' => fake()->sentence(),
        ];
    }
}
