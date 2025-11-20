<?php

use App\Jobs\ProcessRecurringPayablesJob;
use App\Models\AccountPayable;
use App\Models\AccountReceivable;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Bus::fake();
    Storage::fake('public');
    $this->seed();
});

it('creates receivable installments from partial sale', function () {
    $user = User::factory()->create()->givePermissionTo('manage sales');
    $service = \App\Models\Service::factory()->create(['price' => 200]);
    $initial = AccountReceivable::count();

    $this->actingAs($user)
        ->post(route('pos.store'), [
            'mode' => 'sale',
            'pricing_mode' => 'retail',
            'discount_total' => 0,
            'items' => [
                ['item_type' => 'service', 'item_id' => $service->id, 'quantity' => 1, 'discount' => 0],
            ],
            'payments' => [
                ['method' => 'cash', 'amount' => 50],
            ],
            'receivable_installments' => 2,
        ]);

    $sale = \App\Models\Sale::latest('id')->first();

    expect($sale->accountsReceivable()->count())->toBe(2)
        ->and($sale->accountsReceivable()->first()->installments_total)->toBe(2);
});

it('processes recurring payables and generates next period entry', function () {
    $payable = AccountPayable::factory()->create([
        'is_recurring' => true,
        'recurrence_interval' => 'monthly',
        'due_date' => now()->subDay(),
    ]);

    (new ProcessRecurringPayablesJob)->handle();

    $next = AccountPayable::where('id', '!=', $payable->id)->first();
    expect($next)->not->toBeNull()
        ->and($next->due_date->month)->toBe(now()->addMonth()->month);
});

it('filters receivables by status and due date', function () {
    $user = User::factory()->create()->givePermissionTo('manage finances');
    AccountReceivable::query()->delete();
    AccountReceivable::factory()->create(['status' => 'pending', 'due_date' => now()->addDay(), 'company_id' => $user->company_id]);
    AccountReceivable::factory()->create(['status' => 'paid', 'due_date' => now()->subDay(), 'company_id' => $user->company_id]);

    $response = $this->actingAs($user)->get(route('receivables.index', [
        'status' => 'pending',
        'due_from' => now()->toDateString(),
    ]));

    $response->assertOk();
    expect($response->viewData('receivables')->count())->toBe(1);
});

it('uploads attachment and dispatches recurrence job for payable', function () {
    $user = User::factory()->create()->givePermissionTo('manage finances');

    $this->actingAs($user)
        ->post(route('payables.store'), [
            'vendor_name' => 'Fornecedor X',
            'category' => 'servicos',
            'amount' => 100,
            'due_date' => now()->toDateString(),
            'is_recurring' => true,
            'recurrence_interval' => 'monthly',
            'attachment' => UploadedFile::fake()->create('nota.pdf', 10),
        ])
        ->assertRedirect();

    Storage::disk('public')->assertExists(AccountPayable::first()->attachment_path);
    Bus::assertDispatched(ProcessRecurringPayablesJob::class);
});

it('exports finance report as excel and pdf', function () {
    $user = User::factory()->create()->givePermissionTo('manage finances');

    $excel = $this->actingAs($user)->get(route('reports.export.excel'));
    $pdf = $this->actingAs($user)->get(route('reports.export.pdf'));

    $excel->assertOk();
    $pdf->assertOk();
});
