<?php

use App\Jobs\ExpireQuotationsJob;
use App\Models\AccountReceivable;
use App\Models\Customer;
use App\Models\OrderService;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\User;

beforeEach(function () {
    $this->seed();
});

it('expires quotations after scheduler runs', function () {
    $sale = Sale::factory()->create([
        'status' => 'quotation',
        'expires_at' => now()->subDay(),
    ]);

    (new ExpireQuotationsJob)->handle();

    expect($sale->fresh()->status)->toBe('expired');
});

it('registers sale with two payment methods and reduces stock', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $user = User::factory()->create()->givePermissionTo('manage sales', 'apply sale discount');

    $response = $this->actingAs($user)
        ->post(route('pos.store'), [
            'mode' => 'sale',
            'pricing_mode' => 'retail',
            'discount_total' => 5,
            'items' => [
                ['item_type' => 'product', 'item_id' => $product->id, 'quantity' => 2, 'discount' => 0],
            ],
            'payments' => [
                ['method' => 'cash', 'amount' => 20],
                ['method' => 'card', 'amount' => ($product->retail_price * 2) - 25],
            ],
        ]);

    $response->assertRedirect();

    $sale = Sale::latest('id')->first();

    expect($sale->payments)->toHaveCount(2)
        ->and($sale->status)->toBe('completed');

    $product->refresh();

    expect($product->stock)->toBe(8);
    expect(StockMovement::where('product_id', $product->id)->where('reference_id', $sale->id)->exists())->toBeTrue();
});

it('blocks discount without permission', function () {
    $product = Product::factory()->create();
    $user = User::factory()->create()->givePermissionTo('manage sales');

    $this->actingAs($user)
        ->post(route('pos.store'), [
            'mode' => 'sale',
            'pricing_mode' => 'retail',
            'discount_total' => 10,
            'items' => [
                ['item_type' => 'product', 'item_id' => $product->id, 'quantity' => 1, 'discount' => 0],
            ],
            'payments' => [
                ['method' => 'cash', 'amount' => 10],
            ],
        ])
        ->assertForbidden();
});

it('blocks order service invoicing without permission', function () {
    $user = User::factory()->create()->givePermissionTo('manage sales');
    $product = Product::factory()->create(['company_id' => $user->company_id]);
    $customer = Customer::factory()->create(['company_id' => $user->company_id]);
    $orderService = OrderService::factory()->create([
        'company_id' => $user->company_id,
        'customer_id' => $customer->id,
        'status' => 'approved',
    ]);

    $orderService->items()->create([
        'item_type' => 'product',
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => $product->retail_price,
        'discount' => 0,
        'total' => $product->retail_price,
    ]);

    $response = $this->actingAs($user)->post(route('pos.store'), [
        'order_service_id' => $orderService->id,
        'mode' => 'sale',
        'pricing_mode' => 'retail',
        'payments' => [
            ['method' => 'cash', 'amount' => $product->retail_price],
        ],
    ]);

    $response->assertForbidden();
});

it('invoices an order service, creates payments and receivables, and prevents duplicates', function () {
    $user = User::factory()->create()->givePermissionTo('manage sales', 'pdv.invoice_os');
    $product = Product::factory()->create(['company_id' => $user->company_id, 'stock' => 5]);
    $customer = Customer::factory()->create(['company_id' => $user->company_id]);
    $orderService = OrderService::factory()->create([
        'company_id' => $user->company_id,
        'customer_id' => $customer->id,
        'status' => 'approved',
        'total_value' => $product->retail_price,
    ]);

    $orderService->items()->create([
        'item_type' => 'product',
        'product_id' => $product->id,
        'quantity' => 2,
        'unit_price' => $product->retail_price,
        'discount' => 0,
        'total' => $product->retail_price * 2,
    ]);

    $response = $this->actingAs($user)->post(route('pos.store'), [
        'order_service_id' => $orderService->id,
        'mode' => 'sale',
        'pricing_mode' => 'retail',
        'payments' => [
            ['method' => 'cash', 'amount' => $product->retail_price],
        ],
    ]);

    $response->assertRedirect();

    $sale = Sale::where('order_service_id', $orderService->id)->first();

    expect($sale)->not->toBeNull()
        ->and($sale->payments)->toHaveCount(1)
        ->and(AccountReceivable::where('order_service_id', $orderService->id)->exists())->toBeTrue()
        ->and($orderService->fresh()->status)->toBe('invoiced');

    $duplicate = $this->actingAs($user)->post(route('pos.store'), [
        'order_service_id' => $orderService->id,
        'mode' => 'sale',
        'pricing_mode' => 'retail',
        'payments' => [
            ['method' => 'cash', 'amount' => $product->retail_price],
        ],
    ]);

    $duplicate->assertSessionHasErrors(['order_service_id']);
});
