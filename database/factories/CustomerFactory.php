<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Reseller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'reseller_id' => null,
            'name' => fake()->name(),
            'document' => fake()->unique()->numerify('###########'),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'mobile_phone' => fake()->phoneNumber(),
            'state' => fake()->stateAbbr(),
            'city' => fake()->city(),
            'zip_code' => fake()->postcode(),
            'address' => fake()->streetAddress(),
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
