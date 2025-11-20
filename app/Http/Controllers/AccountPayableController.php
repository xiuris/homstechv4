<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessRecurringPayablesJob;
use App\Models\AccountPayable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class AccountPayableController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth.basic', 'permission:manage finances']);
    }

    public function index(Request $request): View
    {
        $payables = AccountPayable::query()
            ->where('company_id', Auth::user()->company_id)
            ->when($request->string('status')->isNotEmpty(), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->string('category')->isNotEmpty(), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->filled('due_from'), fn ($query) => $query->whereDate('due_date', '>=', $request->date('due_from')))
            ->when($request->filled('due_to'), fn ($query) => $query->whereDate('due_date', '<=', $request->date('due_to')))
            ->orderBy('due_date')
            ->get();

        return view('finance.payables.index', [
            'payables' => $payables,
            'filters' => $request->only(['status', 'category', 'due_from', 'due_to']),
        ]);
    }

    public function create(): View
    {
        return view('finance.payables.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_name' => ['required', 'string'],
            'category' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'due_date' => ['required', 'date'],
            'is_recurring' => ['nullable', 'boolean'],
            'recurrence_interval' => ['nullable', 'in:monthly,weekly'],
            'attachment' => ['nullable', 'file'],
            'notes' => ['nullable', 'string'],
        ]);

        $path = $request->file('attachment')
            ? $request->file('attachment')->store('attachments', 'public')
            : null;

        $payable = AccountPayable::create([
            'company_id' => Auth::user()->company_id,
            'vendor_name' => $validated['vendor_name'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'],
            'is_recurring' => (bool) ($validated['is_recurring'] ?? false),
            'recurrence_interval' => $validated['recurrence_interval'] ?? null,
            'attachment_path' => $path,
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($payable->is_recurring) {
            Bus::dispatch(new ProcessRecurringPayablesJob());
        }

        return redirect()->route('payables.index');
    }
}
