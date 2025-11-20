<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Reseller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Customer::class, 'customer');
    }

    public function index(Request $request): View
    {
        $customers = Customer::with('reseller')
            ->where('company_id', $request->user()->company_id)
            ->orderBy('name')
            ->paginate(10);

        return view('customers.index', compact('customers'));
    }

    public function create(Request $request): View
    {
        $resellers = Reseller::where('company_id', $request->user()->company_id)
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('customers.create', [
            'customer' => new Customer,
            'resellers' => $resellers,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $companyId = $request->user()->company_id;

        $data = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'document' => [
                'required',
                'string',
                'max:20',
                Rule::unique('customers', 'document')->where('company_id', $companyId),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile_phone' => ['nullable', 'string', 'max:30'],
            'state' => ['nullable', 'string', 'max:2'],
            'city' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:12'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'reseller_id' => ['nullable', Rule::exists('resellers', 'id')->where('company_id', $companyId)],
        ]);

        $data['company_id'] = $companyId;

        $customer = Customer::create($data);

        return redirect()->route('customers.show', $customer)
            ->with('status', 'Cliente criado com sucesso.');
    }

    public function show(Customer $customer): View
    {
        $customer->load(['reseller']);

        return view('customers.show', compact('customer'));
    }

    public function edit(Request $request, Customer $customer): View
    {
        $resellers = Reseller::where('company_id', $request->user()->company_id)
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('customers.edit', compact('customer', 'resellers'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $companyId = $request->user()->company_id;

        $data = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'document' => [
                'required',
                'string',
                'max:20',
                Rule::unique('customers', 'document')
                    ->ignore($customer->id)
                    ->where('company_id', $customer->company_id),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile_phone' => ['nullable', 'string', 'max:30'],
            'state' => ['nullable', 'string', 'max:2'],
            'city' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:12'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'reseller_id' => ['nullable', Rule::exists('resellers', 'id')->where('company_id', $companyId)],
        ]);

        $customer->update($data);

        return redirect()->route('customers.show', $customer)
            ->with('status', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('status', 'Cliente removido com sucesso.');
    }
}
