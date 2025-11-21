@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item">Dashboard</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold mb-1">Painel de Insights</h2>
        <p class="text-muted mb-0">KPIs e alertas inteligentes do Homstech OS</p>
    </div>
    <span class="badge bg-primary-subtle text-primary">Homstech OS</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-kpi">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 small">OS Abertas</p>
                    <h3 class="fw-bold mb-0">{{ $openOrders }}</h3>
                </div>
                <span class="icon-wrap" style="background: #2563EB33; color: #2563EB">
                    <i class="bi bi-wrench-adjustable"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 small">Conversão Orçamento</p>
                    <h3 class="fw-bold mb-0">{{ $conversionRate }}%</h3>
                    <small class="text-success">▲ +2,3% vs. semana passada</small>
                </div>
                <span class="icon-wrap" style="background: #22C55E33; color: #22C55E">
                    <i class="bi bi-graph-up"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 small">Inadimplência</p>
                    <h3 class="fw-bold mb-0">{{ $overdueReceivables }}</h3>
                    <small class="text-warning">Atenção aos recebíveis vencidos</small>
                </div>
                <span class="icon-wrap" style="background: #F9731633; color: #F97316">
                    <i class="bi bi-exclamation-triangle"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 small">Ticket Médio</p>
                    <h3 class="fw-bold mb-0">R$ {{ number_format($averageTicket, 2, ',', '.') }}</h3>
                    <small class="text-muted">Mês atual</small>
                </div>
                <span class="icon-wrap" style="background: #0ea5e933; color: #0ea5e9">
                    <i class="bi bi-currency-dollar"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Alertas</h5>
            <small class="text-muted">Configure alertas automáticos do Homstech OS</small>
        </div>
        <span class="badge bg-primary-subtle text-primary">Staus & KPI</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('insights.store') }}" class="row gy-3 align-items-end">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Dias sem avanço da OS</label>
                <input type="number" name="threshold_days" value="{{ $alerts->firstWhere('type', 'os_stale')->threshold_days ?? 3 }}" class="form-control" min="1">
            </div>
            <div class="col-md-3">
                <div class="form-check mt-4">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ ($alerts->firstWhere('type', 'os_stale')->is_active ?? true) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Ativar alerta</label>
                </div>
            </div>
            <div class="col-md-3 text-md-end">
                <button class="btn btn-primary" type="submit">Salvar alertas</button>
            </div>
        </form>

        <div class="mt-4">
            <h6 class="fw-semibold">Últimas execuções</h6>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Último disparo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alerts as $alert)
                            <tr>
                                <td class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $alert->type) }}</td>
                                <td>{{ $alert->last_triggered_at ? $alert->last_triggered_at->diffForHumans() : 'Nunca' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
