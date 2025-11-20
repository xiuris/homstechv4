<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'payable_type' => null,
            'payable_id' => null,
            'amount' => fake()->randomFloat(2, 50, 1000),
            'method' => fake()->randomElement(['pix', 'boleto', 'cartao']),
            'due_date' => now()->addDays(7),
            'paid_at' => null,
            'notes' => fake()->sentence(),
        ];
    }
}
