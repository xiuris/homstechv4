<?php

namespace Database\Factories;

use App\Models\AccountReceivable;
use App\Models\Company;
use App\Models\Customer;
use App\Models\OrderService;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AccountReceivable>
 */
class AccountReceivableFactory extends Factory
{
    protected $model = AccountReceivable::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'customer_id' => Customer::factory(),
            'sale_id' => null,
            'order_service_id' => null,
            'amount' => fake()->randomFloat(2, 100, 2000),
            'due_date' => now()->addDays(10),
            'status' => fake()->randomElement(['pending', 'paid', 'overdue']),
            'notes' => fake()->sentence(),
        ];
    }

    public function forSale(): static
    {
        return $this->state(fn () => [
            'sale_id' => Sale::factory(),
        ]);
    }

    public function forOrderService(): static
    {
        return $this->state(fn () => [
            'order_service_id' => OrderService::factory(),
        ]);
    }
}
