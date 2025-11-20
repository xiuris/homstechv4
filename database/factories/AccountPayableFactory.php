<?php

namespace Database\Factories;

use App\Models\AccountPayable;
use App\Models\Company;
use App\Models\Reseller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AccountPayable>
 */
class AccountPayableFactory extends Factory
{
    protected $model = AccountPayable::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'reseller_id' => null,
            'vendor_name' => fake()->company(),
            'amount' => fake()->randomFloat(2, 100, 2000),
            'due_date' => now()->addDays(15),
            'status' => fake()->randomElement(['pending', 'paid', 'overdue']),
            'notes' => fake()->sentence(),
        ];
    }

    public function withReseller(): static
    {
        return $this->state(fn () => [
            'reseller_id' => Reseller::factory(),
        ]);
    }
}
