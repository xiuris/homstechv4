<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed();
});

it('enforces customer permissions on index route', function () {
    $company = Company::first();
    $unauthorized = User::factory()->create([
        'company_id' => $company->id,
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($unauthorized)
        ->get(route('customers.index'))
        ->assertForbidden();

    $authorized = User::whereEmail('admin@homstech.test')->firstOrFail();
    expect($authorized->can('manage customers'))->toBeTrue();

    $this->actingAs($authorized)
        ->get(route('customers.index'))
        ->assertOk();
});

it('enforces product permissions on index route', function () {
    $company = Company::first();
    $unauthorized = User::factory()->create([
        'company_id' => $company->id,
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($unauthorized)
        ->get(route('products.index'))
        ->assertForbidden();

    $authorized = User::whereEmail('vendas@homstech.test')->firstOrFail();
    expect($authorized->can('manage products'))->toBeTrue();

    $this->actingAs($authorized)
        ->get(route('products.index'))
        ->assertOk();
});

it('enforces service permissions on index route', function () {
    $company = Company::first();
    $unauthorized = User::factory()->create([
        'company_id' => $company->id,
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($unauthorized)
        ->get(route('services.index'))
        ->assertForbidden();

    $authorized = User::whereEmail('vendas@homstech.test')->firstOrFail();
    expect($authorized->can('manage services'))->toBeTrue();

    $this->actingAs($authorized)
        ->get(route('services.index'))
        ->assertOk();
});
