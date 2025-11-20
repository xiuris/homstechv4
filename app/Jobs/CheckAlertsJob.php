<?php

namespace App\Jobs;

use App\Models\Alert;
use App\Models\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckAlertsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        $alerts = Alert::where('is_active', true)->get();

        foreach ($alerts as $alert) {
            if ($alert->type !== 'os_stale') {
                continue;
            }

            $staleOrders = OrderService::where('company_id', $alert->company_id)
                ->whereNot('status', 'entregue')
                ->whereDate('opened_at', '<=', now()->subDays($alert->threshold_days))
                ->count();

            if ($staleOrders > 0) {
                $alert->forceFill(['last_triggered_at' => now()])->save();

                Log::channel('structured')->info('alert.triggered', [
                    'alert_id' => $alert->id,
                    'company_id' => $alert->company_id,
                    'stale_orders' => $staleOrders,
                ]);
            }
        }
    }
}
