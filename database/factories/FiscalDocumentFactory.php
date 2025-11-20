<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class FiscalDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'customer_id' => Customer::factory(),
            'document_type' => 'nfe',
            'uf' => 'MS',
            'environment' => 'homologation',
            'total' => $this->faker->randomFloat(2, 50, 500),
            'status' => 'pending',
            'scheduled_at' => now(),
        ];
    }
}
