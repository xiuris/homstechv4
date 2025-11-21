<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Company;
use App\Models\Customer;
use App\Models\OrderService;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        $start = now()->addDay();

        return [
            'company_id' => Company::factory(),
            'order_service_id' => OrderService::factory(),
            'customer_id' => Customer::factory(),
            'technician_id' => User::factory(),
            'starts_at' => $start,
            'ends_at' => $start->copy()->addHour(),
            'status' => 'scheduled',
            'is_blocked' => false,
            'notes' => $this->faker->sentence(),
        ];
    }
}
