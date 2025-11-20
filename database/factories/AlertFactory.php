<?php

namespace Database\Factories;

use App\Models\Alert;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alert>
 */
class AlertFactory extends Factory
{
    protected $model = Alert::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'type' => 'os_stale',
            'threshold_days' => 3,
            'is_active' => true,
            'last_triggered_at' => null,
        ];
    }
}
