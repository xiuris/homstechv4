<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'trade_name' => fake()->companySuffix(),
            'document' => fake()->unique()->numerify('##.###.###/####-##'),
            'email' => fake()->companyEmail(),
            'phone' => fake()->e164PhoneNumber(),
            'address' => fake()->address(),
        ];
    }
}
