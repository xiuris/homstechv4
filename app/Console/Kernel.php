<?php

namespace App\Console;

use App\Jobs\ExpireQuotationsJob;
use App\Jobs\ProcessRecurringPayablesJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new ExpireQuotationsJob)->daily();
        $schedule->job(new ProcessRecurringPayablesJob)->daily();
    }
}
