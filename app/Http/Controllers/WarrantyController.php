<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderService;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Service;
use App\Models\Warranty;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WarrantyController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Warranty::class);

        $warranties = Warranty::with(['customer', 'orderService', 'product'])
            ->where('company_id', $request->user()->company_id)
            ->latest()
            ->get();

        return view('warranties.index', compact('warranties'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Warranty::class);

        $companyId = $request->user()->company_id;

        return view('warranties.create', [
            'customers' => Customer::where('company_id', $companyId)->get(),
            'orders' => OrderService::where('company_id', $companyId)->get(),
            'sales' => Sale::where('company_id', $companyId)->get(),
            'products' => Product::where('company_id', $companyId)->get(),
            'services' => Service::where('company_id', $companyId)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Warranty::class);

        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'order_service_id' => ['nullable', 'exists:order_services,id'],
            'sale_id' => ['nullable', 'exists:sales,id'],
            'product_id' => ['nullable', 'exists:products,id'],
            'service_id' => ['nullable', 'exists:services,id'],
            'starts_at' => ['required', 'date'],
            'expires_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'status' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['company_id'] = $request->user()->company_id;

        Warranty::create($data);

        return redirect()->route('warranties.index')->with('status', __('Garantia registrada'));
    }
}
