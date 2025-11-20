@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Painel de Insights</h1>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-light">
                <div class="card-body">
                    <h5 class="card-title">OS Abertas</h5>
                    <p class="display-6">{{ $openOrders }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light">
                <div class="card-body">
                    <h5 class="card-title">Conversão Orçamento → Venda</h5>
                    <p class="display-6">{{ $conversionRate }}%</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light">
                <div class="card-body">
                    <h5 class="card-title">Inadimplência</h5>
                    <p class="display-6">{{ $overdueReceivables }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light">
                <div class="card-body">
                    <h5 class="card-title">Ticket Médio</h5>
                    <p class="display-6">R$ {{ number_format($averageTicket, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Alertas</div>
        <div class="card-body">
            <form method="POST" action="{{ route('insights.store') }}">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Dias sem avanço da OS</label>
                        <input type="number" name="threshold_days" value="{{ $alerts->firstWhere('type', 'os_stale')->threshold_days ?? 3 }}" class="form-control" min="1">
                    </div>
                    <div class="col-md-3 form-check mt-4">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ ($alerts->firstWhere('type', 'os_stale')->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Ativar alerta</label>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary" type="submit">Salvar alertas</button>
                    </div>
                </div>
            </form>

            <div class="mt-4">
                <h6>Últimas execuções</h6>
                <ul class="list-group">
                    @foreach($alerts as $alert)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ ucfirst($alert->type) }}</span>
                            <span>{{ $alert->last_triggered_at ? $alert->last_triggered_at->diffForHumans() : 'Nunca' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
