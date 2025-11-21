<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Reseller;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'customer_id' => Customer::factory(),
            'reseller_id' => null,
            'user_id' => User::factory(),
 codex/create-laravel-11-project-with-base-routes-00l5fh
            'status' => 'quotation',
            'subtotal' => fake()->randomFloat(2, 100, 5000),
            'discount_total' => fake()->randomFloat(2, 0, 500),
            'total' => fake()->randomFloat(2, 100, 5000),
            'sold_at' => null,
=======
            'status' => fake()->randomElement(['draft', 'confirmed', 'invoiced']),
            'subtotal' => fake()->randomFloat(2, 100, 5000),
            'discount_total' => fake()->randomFloat(2, 0, 500),
            'total' => fake()->randomFloat(2, 100, 5000),
            'sold_at' => now(),
 main
        ];
    }

    public function withReseller(): static
    {
        return $this->state(fn () => [
            'reseller_id' => Reseller::factory(),
        ]);
    }
}
