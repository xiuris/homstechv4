<?php

namespace App\Console;

use App\Jobs\CheckAlertsJob;
use App\Jobs\ExpireQuotationsJob;
use App\Jobs\ProcessRecurringPayablesJob;
use App\Jobs\RunBackupJob;
use App\Jobs\SendAppointmentRemindersJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new ExpireQuotationsJob)->daily();
        $schedule->job(new ProcessRecurringPayablesJob)->daily();
        $schedule->job(new CheckAlertsJob)->hourly();
        $schedule->job(new SendAppointmentRemindersJob)->hourly();
        $schedule->job(new RunBackupJob)->dailyAt('02:00');
    }
}
