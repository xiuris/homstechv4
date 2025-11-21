@extends('layouts.app')

@section('title', 'Visão Geral')

@section('breadcrumbs')
    <li class="breadcrumb-item">Início</li>
@endsection

@section('content')
<div class="row align-items-center g-4 mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold">Bem-vindo ao Homstech OS</h2>
        <p class="text-muted">Gerencie ordens de serviço, vendas e finanças em um ambiente unificado com visual moderno.</p>
        <div class="d-flex gap-2">
            <a href="{{ route('pos.create') }}" class="btn btn-primary">Abrir PDV</a>
            <a href="{{ route('receivables.index') }}" class="btn btn-outline-primary">Ver Financeiro</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-semibold">Atalhos rápidos</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard</span>
                        <a href="{{ route('insights.index') }}" class="btn btn-sm btn-outline-primary">Abrir</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-bag-check me-2 text-primary"></i>PDV</span>
                        <a href="{{ route('pos.create') }}" class="btn btn-sm btn-outline-primary">Abrir</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-cash-coin me-2 text-primary"></i>Financeiro</span>
                        <a href="{{ route('receivables.index') }}" class="btn btn-sm btn-outline-primary">Abrir</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card card-kpi">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 small">OS em aberto</p>
                    <h4 class="fw-bold mb-0">—</h4>
                    <small class="text-muted">Acompanhe o fluxo por status</small>
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
                    <p class="text-muted mb-1 small">Vendas do dia</p>
                    <h4 class="fw-bold mb-0">—</h4>
                    <small class="text-muted">Monitore PDV em tempo real</small>
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
                    <p class="text-muted mb-1 small">Faturamento do mês</p>
                    <h4 class="fw-bold mb-0">—</h4>
                    <small class="text-muted">Consolide OS + PDV</small>
                </div>
                <span class="icon-wrap" style="background: #0ea5e933; color: #0ea5e9">
                    <i class="bi bi-currency-dollar"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 small">Alertas ativos</p>
                    <h4 class="fw-bold mb-0">—</h4>
                    <small class="text-muted">Configure em Configurações</small>
                </div>
                <span class="icon-wrap" style="background: #F9731633; color: #F97316">
                    <i class="bi bi-bell"></i>
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
