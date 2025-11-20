<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivable;
use App\Models\Alert;
use App\Models\OrderService;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Alert::class);

        $companyId = $request->user()->company_id;

        $openOrders = OrderService::where('company_id', $companyId)
            ->whereNot('status', 'entregue')
            ->count();

        $quotations = Sale::where('company_id', $companyId)->where('status', 'quotation');
        $converted = Sale::where('company_id', $companyId)->where('status', 'completed');
        $conversionRate = $quotations->count() > 0
            ? round(($converted->count() / max($quotations->count(), 1)) * 100, 2)
            : 0;

        $overdueReceivables = AccountReceivable::where('company_id', $companyId)
            ->where('status', 'overdue')
            ->count();

        $averageTicket = (float) Sale::where('company_id', $companyId)
            ->where('status', 'completed')
            ->avg('total');

        $alerts = Alert::where('company_id', $companyId)->get();

        return view('insights.index', compact(
            'openOrders',
            'conversionRate',
            'overdueReceivables',
            'averageTicket',
            'alerts'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Alert::class);

        $data = $request->validate([
            'threshold_days' => ['required', 'integer', 'min:1'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $companyId = $request->user()->company_id;

        $alert = Alert::updateOrCreate(
            ['company_id' => $companyId, 'type' => 'os_stale'],
            [
                'threshold_days' => $data['threshold_days'],
                'is_active' => $data['is_active'] ?? false,
            ]
        );

        return redirect()->route('insights.index')->with(
            'status',
            __('Alertas atualizados: :days dias de inatividade', ['days' => $alert->threshold_days])
        );
    }
}
