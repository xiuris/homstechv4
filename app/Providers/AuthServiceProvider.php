<?php

namespace App\Providers;

use App\Models\AccountPayable;
use App\Models\AccountReceivable;
use App\Models\Alert;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\FiscalDocument;
use App\Models\OrderService;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Service;
use App\Models\StockMovement;
use App\Models\Warranty;
use App\Policies\AccountPayablePolicy;
use App\Policies\AccountReceivablePolicy;
use App\Policies\AlertPolicy;
use App\Policies\AppointmentPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\FiscalDocumentPolicy;
use App\Policies\OrderServicePolicy;
use App\Policies\ProductPolicy;
use App\Policies\SalePolicy;
use App\Policies\ServicePolicy;
use App\Policies\StockMovementPolicy;
use App\Policies\WarrantyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Alert::class => AlertPolicy::class,
        Appointment::class => AppointmentPolicy::class,
        Customer::class => CustomerPolicy::class,
        Product::class => ProductPolicy::class,
        Service::class => ServicePolicy::class,
        OrderService::class => OrderServicePolicy::class,
        Sale::class => SalePolicy::class,
        AccountReceivable::class => AccountReceivablePolicy::class,
        AccountPayable::class => AccountPayablePolicy::class,
        Warranty::class => WarrantyPolicy::class,
        StockMovement::class => StockMovementPolicy::class,
        FiscalDocument::class => FiscalDocumentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
