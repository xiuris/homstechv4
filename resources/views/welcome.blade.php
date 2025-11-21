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
@endsection
