<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\OrderService;
use App\Models\Reseller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderService>
 */
class OrderServiceFactory extends Factory
{
    protected $model = OrderService::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'customer_id' => Customer::factory(),
            'reseller_id' => null,
            'assigned_user_id' => null,
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['open', 'in_progress', 'approved', 'ready_to_invoice']),
            'priority' => fake()->randomElement(['low', 'normal', 'high']),
            'total_value' => fake()->randomFloat(2, 100, 3000),
            'opened_at' => now(),
            'closed_at' => null,
        ];
    }

    public function withAssignee(): static
    {
        return $this->state(fn () => [
            'assigned_user_id' => User::factory(),
        ]);
    }

    public function withReseller(): static
    {
        return $this->state(fn () => [
            'reseller_id' => Reseller::factory(),
        ]);
    }
}
