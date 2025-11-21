@extends('layouts.app')

@section('title', 'Financeiro')

@section('breadcrumbs')
    <li class="breadcrumb-item">Financeiro</li>
    <li class="breadcrumb-item active" aria-current="page">Contas a Pagar</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold mb-1">Contas a Pagar</h2>
        <p class="text-muted mb-0">Controle despesas únicas e recorrentes</p>
    </div>
    <a href="{{ route('payables.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Nova despesa</a>
</div>

<form method="get" class="row g-2 mb-3 align-items-end">
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <input class="form-control" name="status" placeholder="Status" value="{{ $filters['status'] ?? '' }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Categoria</label>
        <input class="form-control" name="category" placeholder="Categoria" value="{{ $filters['category'] ?? '' }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Vencimento de</label>
        <input class="form-control" type="date" name="due_from" value="{{ $filters['due_from'] ?? '' }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Vencimento até</label>
        <input class="form-control" type="date" name="due_to" value="{{ $filters['due_to'] ?? '' }}">
    </div>
</form>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Despesas</h5>
        <input type="search" class="form-control w-auto" placeholder="Buscar fornecedor" aria-label="Buscar" />
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead><tr><th>Fornecedor</th><th>Categoria</th><th>Valor</th><th>Vencimento</th><th>Recorrente</th></tr></thead>
                <tbody>
                    @foreach($payables as $item)
                        <tr>
                            <td>{{ $item->vendor_name }}</td>
                            <td>{{ $item->category }}</td>
                            <td>{{ number_format($item->amount, 2, ',', '.') }}</td>
                            <td>{{ $item->due_date->format('d/m/Y') }}</td>
                            <td><span class="badge {{ $item->is_recurring ? 'bg-primary-subtle text-primary' : 'bg-light text-dark' }}">{{ $item->is_recurring ? 'Sim' : 'Não' }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
