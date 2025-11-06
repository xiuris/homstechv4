<?php

use App\Models\Company;
use App\Models\Client;
use App\Models\Product;
use App\Models\Reseller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed();
});

it('denies unauthenticated access to the web status endpoint', function () {
    $this->get('/status')->assertUnauthorized();
});

it('allows authorized users to access the web status endpoint', function () {
    $user = User::whereEmail('admin@homstech.test')->firstOrFail();

    $this->get('/status', [
        'Authorization' => 'Basic '.base64_encode($user->email.':password'),
    ])->assertOk()->assertJson([
        'app' => config('app.name'),
        'status' => 'online',
    ]);
});

it('denies unauthenticated access to the api status endpoint', function () {
    $this->get('/api/status')->assertUnauthorized();
});

it('allows authorized users to access the api status endpoint', function () {
    $user = User::whereEmail('admin@homstech.test')->firstOrFail();

    $this->get('/api/status', [
        'Authorization' => 'Basic '.base64_encode($user->email.':password'),
    ])->assertOk()->assertJson([
        'service' => config('app.name'),
        'status' => 'online',
    ]);
});

it('seeds demo data for the initial setup', function () {
    expect(Company::count())->toBe(1)
        ->and(Reseller::count())->toBe(2)
        ->and(Client::count())->toBe(5)
        ->and(User::count())->toBe(4)
        ->and(Product::count())->toBe(3)
        ->and(Service::count())->toBe(3);
});
