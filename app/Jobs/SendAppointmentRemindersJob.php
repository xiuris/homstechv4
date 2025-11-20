<?php

namespace App\Jobs;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendAppointmentRemindersJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        Appointment::whereNull('reminder_sent_at')
            ->whereBetween('starts_at', [now(), now()->addDay()])
            ->get()
            ->each(function (Appointment $appointment): void {
                $appointment->forceFill(['reminder_sent_at' => now()])->save();

                Log::channel('structured')->info('appointment.reminder', [
                    'appointment_id' => $appointment->id,
                    'company_id' => $appointment->company_id,
                    'starts_at' => $appointment->starts_at,
                ]);
            });
    }
}
