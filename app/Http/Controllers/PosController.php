<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivable;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Service;
use App\Models\StockMovement;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth.basic', 'permission:manage sales']);
    }

    public function create(): View
    {
        return view('pos.create', [
            'customers' => \App\Models\Customer::all(),
            'resellers' => \App\Models\Reseller::all(),
            'products' => Product::where('is_active', true)->get(),
            'services' => Service::where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'reseller_id' => ['nullable', 'exists:resellers,id'],
            'mode' => ['required', 'in:quotation,sale'],
            'pricing_mode' => ['required', 'in:retail,wholesale'],
            'discount_total' => ['nullable', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'in:product,service'],
            'items.*.item_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'payments' => ['nullable', 'array', 'min:1'],
            'payments.*.method' => ['required_with:payments', 'string'],
            'payments.*.amount' => ['required_with:payments', 'numeric', 'min:0.01'],
            'receivable_installments' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        if (($validated['discount_total'] ?? 0) > 0 && !Auth::user()->can('apply sale discount')) {
            abort(403, 'Você não tem permissão para aplicar descontos.');
        }

        $items = collect($validated['items'])->map(function (array $item) use ($validated) {
            if ($item['item_type'] === 'product') {
                $product = Product::findOrFail($item['item_id']);
                $price = $validated['pricing_mode'] === 'wholesale'
                    ? ($product->wholesale_price ?? $product->retail_price)
                    : $product->retail_price;
                return [
                    'product_id' => $product->id,
                    'service_id' => null,
                    'item_type' => 'product',
                    'quantity' => $item['quantity'],
                    'unit_price' => $price,
                    'discount' => $item['discount'] ?? 0,
                    'total' => ($price * $item['quantity']) - ($item['discount'] ?? 0),
                    'product' => $product,
                ];
            }

            $service = Service::findOrFail($item['item_id']);

            return [
                'product_id' => null,
                'service_id' => $service->id,
                'item_type' => 'service',
                'quantity' => $item['quantity'],
                'unit_price' => $service->price,
                'discount' => $item['discount'] ?? 0,
                'total' => ($service->price * $item['quantity']) - ($item['discount'] ?? 0),
            ];
        });

        $subtotal = $items->sum(fn ($item) => $item['unit_price'] * $item['quantity']);
        $discount = ($validated['discount_total'] ?? 0) + $items->sum(fn ($item) => $item['discount']);
        $total = max($subtotal - $discount, 0);

        $sale = Sale::create([
            'company_id' => Auth::user()->company_id,
            'customer_id' => Arr::get($validated, 'customer_id'),
            'reseller_id' => Arr::get($validated, 'reseller_id'),
            'user_id' => Auth::id(),
            'status' => $validated['mode'] === 'sale' ? 'completed' : 'quotation',
            'subtotal' => $subtotal,
            'discount_total' => $discount,
            'total' => $total,
            'sold_at' => $validated['mode'] === 'sale' ? now() : null,
            'expires_at' => $validated['mode'] === 'quotation' ? now()->addDays(3) : null,
        ]);

        $sale->items()->createMany($items->map(fn ($item) => Arr::except($item, ['product'])));

        $installments = $validated['receivable_installments'] ?? 1;

        if ($validated['mode'] === 'sale') {
            $this->processPayments($sale, $validated['payments'] ?? [['method' => 'cash', 'amount' => $total]], $installments);
            $this->applyStockMovements($sale, $items);
        }

        return redirect()->route('pos.show', $sale);
    }

    public function show(Sale $sale): View
    {
        $sale->load(['items.product', 'items.service', 'payments']);

        return view('pos.show', [
            'sale' => $sale,
        ]);
    }

    public function complete(Request $request, Sale $sale): RedirectResponse
    {
        abort_unless($sale->status === 'quotation', 404);

        $validated = $request->validate([
            'payments' => ['required', 'array', 'min:1'],
            'payments.*.method' => ['required', 'string'],
            'payments.*.amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $this->processPayments($sale, $validated['payments']);
        $this->applyStockMovements($sale, $sale->items);

        $sale->update([
            'status' => 'completed',
            'sold_at' => now(),
        ]);

        return redirect()->route('pos.show', $sale);
    }

    protected function processPayments(Sale $sale, array $payments, int $installments = 1): void
    {
        $totalAmount = collect($payments)->sum('amount');

        if ($totalAmount < $sale->total) {
            for ($i = 1; $i <= $installments; $i++) {
                AccountReceivable::create([
                    'company_id' => $sale->company_id,
                    'sale_id' => $sale->id,
                    'customer_id' => $sale->customer_id,
                    'amount' => ($sale->total - $totalAmount) / $installments,
                    'status' => 'pending',
                    'installment_number' => $i,
                    'installments_total' => $installments,
                    'due_date' => now()->addMonths($i - 1)->addDays(7),
                ]);
            }
        }

        foreach ($payments as $payment) {
            $sale->payments()->create([
                'company_id' => $sale->company_id,
                'amount' => $payment['amount'],
                'method' => $payment['method'],
                'paid_at' => now(),
            ]);
        }
    }

    protected function applyStockMovements(Sale $sale, $items): void
    {
        if ($sale->stock_processed_at) {
            return;
        }

        foreach ($items as $item) {
            if (($item['item_type'] ?? null) !== 'product') {
                continue;
            }

            $product = $item['product'] ?? Product::find($item['product_id']);

            if (!$product) {
                continue;
            }

            $product->decrement('stock', $item['quantity']);

            StockMovement::create([
                'company_id' => $sale->company_id,
                'product_id' => $product->id,
                'user_id' => $sale->user_id,
                'type' => 'sale',
                'quantity' => $item['quantity'],
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'description' => 'Baixa de estoque via PDV',
                'occurred_at' => now(),
            ]);
        }

        $sale->update(['stock_processed_at' => now()]);
    }
}
