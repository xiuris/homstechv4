<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountReceivableController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth.basic', 'permission:manage finances']);
    }

    public function index(Request $request): View
    {
        $receivables = AccountReceivable::query()
            ->where('company_id', Auth::user()->company_id)
            ->when($request->string('status')->isNotEmpty(), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('due_from'), fn ($query) => $query->whereDate('due_date', '>=', $request->date('due_from')))
            ->when($request->filled('due_to'), fn ($query) => $query->whereDate('due_date', '<=', $request->date('due_to')))
            ->orderBy('due_date')
            ->get();

        return view('finance.receivables.index', [
            'receivables' => $receivables,
            'filters' => $request->only(['status', 'due_from', 'due_to']),
        ]);
    }

    public function create(): View
    {
        return view('finance.receivables.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'installments' => ['nullable', 'integer', 'min:1', 'max:12'],
            'first_due_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $installments = $validated['installments'] ?? 1;

        for ($i = 1; $i <= $installments; $i++) {
            $dueDate = $i === 1
                ? now()->parse($validated['first_due_date'])
                : now()->parse($validated['first_due_date'])->addMonths($i - 1);

            AccountReceivable::create([
                'company_id' => Auth::user()->company_id,
                'customer_id' => $validated['customer_id'] ?? null,
                'amount' => $validated['amount'] / $installments,
                'installment_number' => $i,
                'installments_total' => $installments,
                'due_date' => $dueDate,
                'notification_channel' => config('services.whatsapp.token') ? 'whatsapp' : 'email',
                'notes' => $validated['notes'] ?? null,
            ]);
        }

        Log::info('Mock WhatsApp notification for receivables');

        return redirect()->route('receivables.index');
    }
}
