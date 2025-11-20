<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->words(3, true),
            'category' => fake()->randomElement(['Implantação', 'Suporte', 'Consultoria']),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 100, 5000),
            'duration_minutes' => fake()->randomElement([60, 90, 120, 240]),
            'is_active' => true,
        ];
    }
}
