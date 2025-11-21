@extends('layouts.app')

@section('title', 'PDV')

@section('breadcrumbs')
    <li class="breadcrumb-item">PDV</li>
    <li class="breadcrumb-item active" aria-current="page">Resumo #{{ $sale->id }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold mb-1">Resumo PDV #{{ $sale->id }}</h2>
        <p class="text-muted mb-0">Acompanhe o status e pagamentos da venda</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-info text-dark">Status: {{ ucfirst($sale->status) }}</span>
        @if($sale->status === 'quotation' && $sale->expires_at)
            <span class="badge bg-warning text-dark">Expira em {{ $sale->expires_at->format('d/m/Y H:i') }}</span>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-kpi">
            <div class="card-body">
                <p class="text-muted small mb-1">Cliente</p>
                <h5 class="fw-semibold mb-0">{{ optional($sale->customer)->name ?? 'N/A' }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-kpi">
            <div class="card-body">
                <p class="text-muted small mb-1">Revendedor</p>
                <h5 class="fw-semibold mb-0">{{ optional($sale->reseller)->name ?? 'N/A' }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-kpi">
            <div class="card-body">
                <p class="text-muted small mb-1">Total</p>
                <h4 class="fw-bold text-primary mb-0">R$ {{ number_format($sale->total, 2, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Itens</h5>
        <small class="text-muted">Produtos e serviços</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead><tr><th>Descrição</th><th>Qtd</th><th>Preço</th><th>Total</th></tr></thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? $item->service->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($item->total, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Pagamentos</h5>
        <small class="text-muted">Múltiplas formas registradas</small>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            @forelse($sale->payments as $payment)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ ucfirst($payment->method) }}</span>
                    <strong>R$ {{ number_format($payment->amount, 2, ',', '.') }}</strong>
                </li>
            @empty
                <li class="list-group-item">Nenhum pagamento registrado (orçamento).</li>
            @endforelse
        </ul>

        @if($sale->status === 'quotation')
            <form method="POST" action="{{ route('pos.complete', $sale) }}" class="mt-3 row g-3 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Forma</label>
                    <input type="text" name="payments[0][method]" class="form-control" placeholder="Forma" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Valor</label>
                    <input type="number" step="0.01" min="0" name="payments[0][amount]" class="form-control" placeholder="Valor" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success w-100">Concluir venda</button>
                </div>
            </form>
        @endif
    </div>
</div>

<div class="alert alert-secondary" role="alert">
    Cupom não fiscal pronto para impressão. Placeholder fiscal mantido para integração futura.
</div>
@endsection
