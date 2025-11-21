<?php

use App\Jobs\CheckAlertsJob;
use App\Jobs\SendAppointmentRemindersJob;
use App\Models\AccountReceivable;
use App\Models\Alert;
use App\Models\Appointment;
use App\Models\OrderService;
use App\Models\Sale;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('shows KPIs and allows configuring alerts', function () {
    Permission::findOrCreate('manage alerts');
    Permission::findOrCreate('manage scheduling');

    $user = User::factory()->create();
    $user->givePermissionTo(['manage alerts', 'manage scheduling']);

    $order = OrderService::factory()->create([
        'company_id' => $user->company_id,
        'status' => 'diagnostico',
        'opened_at' => now()->subDays(2),
    ]);

    Sale::factory()->create([
        'company_id' => $user->company_id,
        'status' => 'quotation',
    ]);

    Sale::factory()->create([
        'company_id' => $user->company_id,
        'status' => 'completed',
        'total' => 500,
    ]);

    AccountReceivable::factory()->create([
        'company_id' => $user->company_id,
        'status' => 'overdue',
    ]);

    Auth::login($user);

    $response = test()->get(route('insights.index'));
    $response->assertOk()->assertSee('Painel de Insights')->assertSee('Alertas');

    test()->post(route('insights.store'), ['threshold_days' => 2, 'is_active' => true])
        ->assertRedirect(route('insights.index'));

    expect(Alert::where('company_id', $user->company_id)->first()->threshold_days)->toBe(2);
});

it('triggers alerts for stale orders', function () {
    Permission::findOrCreate('manage alerts');

    $alert = Alert::factory()->create(['threshold_days' => 1]);

    OrderService::factory()->create([
        'company_id' => $alert->company_id,
        'status' => 'diagnostico',
        'opened_at' => now()->subDays(3),
    ]);

    Bus::dispatchSync(new CheckAlertsJob());

    $alert->refresh();

    expect($alert->last_triggered_at)->not()->toBeNull();
});

it('sends reminders for upcoming appointments', function () {
    $appointment = Appointment::factory()->create([
        'starts_at' => now()->addHours(3),
        'ends_at' => now()->addHours(4),
    ]);

    Bus::dispatchSync(new SendAppointmentRemindersJob());

    $appointment->refresh();

    expect($appointment->reminder_sent_at)->not()->toBeNull();
});
