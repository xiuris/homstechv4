<?php

namespace App\Jobs;

use App\Models\AccountPayable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRecurringPayablesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        AccountPayable::query()
            ->where('is_recurring', true)
            ->whereDate('due_date', '<=', now())
            ->get()
            ->each(function (AccountPayable $payable): void {
                $nextDueDate = $payable->recurrence_interval === 'weekly'
                    ? $payable->due_date->addWeek()
                    : $payable->due_date->addMonth();

                $payable->update([
                    'due_date' => $nextDueDate,
                    'recurrence_count' => $payable->recurrence_count + 1,
                ]);

                AccountPayable::create([
                    'company_id' => $payable->company_id,
                    'vendor_name' => $payable->vendor_name,
                    'category' => $payable->category,
                    'amount' => $payable->amount,
                    'due_date' => $nextDueDate,
                    'is_recurring' => $payable->is_recurring,
                    'recurrence_interval' => $payable->recurrence_interval,
                    'recurrence_count' => $payable->recurrence_count + 1,
                    'attachment_path' => $payable->attachment_path,
                    'notes' => $payable->notes,
                ]);
            });
    }
}
