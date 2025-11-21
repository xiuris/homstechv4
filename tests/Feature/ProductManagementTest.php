<?php

use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('retains the product activation flag when the request omits the checkbox value', function () {
    $this->seed();

    $company = Company::first();
    $product = Product::factory()->create([
        'company_id' => $company->id,
        'is_active' => true,
    ]);

    $authorized = User::whereEmail('admin@homstech.test')->firstOrFail();

    $payload = [
        'name' => 'Produto Atualizado',
        'sku' => $product->sku,
        'category' => $product->category,
        'description' => $product->description,
        'retail_price' => $product->retail_price,
        'wholesale_price' => $product->wholesale_price,
        'stock' => $product->stock,
        'stock_minimum' => $product->stock_minimum,
    ];

    $this->actingAs($authorized)
        ->put(route('products.update', $product), $payload)
        ->assertRedirect(route('products.show', $product));

    expect($product->refresh()->is_active)->toBeTrue();
});

it('allows deactivating a product via the form checkbox', function () {
    $this->seed();

    $company = Company::first();
    $product = Product::factory()->create([
        'company_id' => $company->id,
        'is_active' => true,
    ]);

    $authorized = User::whereEmail('admin@homstech.test')->firstOrFail();

    $payload = [
        'name' => 'Produto Desativado',
        'sku' => $product->sku,
        'category' => $product->category,
        'description' => $product->description,
        'retail_price' => $product->retail_price,
        'wholesale_price' => $product->wholesale_price,
        'stock' => $product->stock,
        'stock_minimum' => $product->stock_minimum,
        'is_active' => '0',
    ];

    $this->actingAs($authorized)
        ->put(route('products.update', $product), $payload)
        ->assertRedirect(route('products.show', $product));

    expect($product->refresh()->is_active)->toBeFalse();
});

it('scopes customer documents and product skus by company', function () {
    $companyA = Company::factory()->create();
    $companyB = Company::factory()->create();

    $sku = 'DUP-100';
    $document = '00000000000';

    Product::factory()->create([
        'company_id' => $companyA->id,
        'sku' => $sku,
    ]);

    $productB = Product::factory()->create([
        'company_id' => $companyB->id,
        'sku' => $sku,
    ]);

    Customer::factory()->create([
        'company_id' => $companyA->id,
        'document' => $document,
    ]);

    $customerB = Customer::factory()->create([
        'company_id' => $companyB->id,
        'document' => $document,
    ]);

    expect($productB->sku)->toBe($sku)
        ->and($customerB->document)->toBe($document);
});
