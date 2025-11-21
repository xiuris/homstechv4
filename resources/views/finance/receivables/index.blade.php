@extends('layouts.app')

@section('title', 'Financeiro')

@section('breadcrumbs')
    <li class="breadcrumb-item">Financeiro</li>
    <li class="breadcrumb-item active" aria-current="page">Contas a Receber</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold mb-1">Contas a Receber</h2>
        <p class="text-muted mb-0">Filtros rápidos por status e vencimento</p>
    </div>
    <a href="{{ route('receivables.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Novo Lançamento</a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="section-header">
            <div>
                <h6 class="mb-0">Filtros</h6>
                <small>Refine por status e período de vencimento.</small>
            </div>
            <span class="badge badge-soft">{{ $receivables->count() }} lançamentos</span>
        </div>
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <input class="form-control" name="status" placeholder="Status" value="{{ $filters['status'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Vencimento de</label>
                <input class="form-control" type="date" name="due_from" value="{{ $filters['due_from'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Vencimento até</label>
                <input class="form-control" type="date" name="due_to" value="{{ $filters['due_to'] ?? '' }}">
            </div>
            <div class="col-md-3 text-md-end">
                <button class="btn btn-outline-primary">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recebíveis</h5>
        <input type="search" class="form-control w-auto" placeholder="Buscar cliente" aria-label="Buscar" />
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead><tr><th>Cliente</th><th>Valor</th><th>Parcela</th><th>Vencimento</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($receivables as $item)
                        <tr>
                            <td>{{ $item->customer?->name ?? '-' }}</td>
                            <td>{{ number_format($item->amount, 2, ',', '.') }}</td>
                            <td>{{ $item->installment_number }}/{{ $item->installments_total }}</td>
                            <td>{{ $item->due_date->format('d/m/Y') }}</td>
                            <td><span class="badge bg-light text-dark">{{ ucfirst($item->status) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
