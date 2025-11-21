@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Resumo PDV #{{ $sale->id }}</h1>
    <div class="mb-3">
        <span class="badge bg-info text-dark">Status: {{ ucfirst($sale->status) }}</span>
        @if($sale->status === 'quotation' && $sale->expires_at)
            <span class="badge bg-warning">Expira em {{ $sale->expires_at->format('d/m/Y H:i') }}</span>
        @endif
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ optional($sale->customer)->name ?? 'N/A' }}</p>
            <p><strong>Revendedor:</strong> {{ optional($sale->reseller)->name ?? 'N/A' }}</p>
            <p><strong>Total:</strong> R$ {{ number_format($sale->total, 2, ',', '.') }}</p>
        </div>
    </div>

    <h5>Itens</h5>
    <table class="table table-sm">
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

    <h5>Pagamentos</h5>
    <ul>
        @forelse($sale->payments as $payment)
            <li>{{ ucfirst($payment->method) }} - R$ {{ number_format($payment->amount, 2, ',', '.') }}</li>
        @empty
            <li>Nenhum pagamento registrado (orçamento).</li>
        @endforelse
    </ul>

    @if($sale->status === 'quotation')
        <form method="POST" action="{{ route('pos.complete', $sale) }}" class="mb-3">
            @csrf
            <h5>Fechar orçamento</h5>
            <div class="row g-3 mb-2">
                <div class="col-md-4"><input type="text" name="payments[0][method]" class="form-control" placeholder="Forma"></div>
                <div class="col-md-4"><input type="number" step="0.01" min="0" name="payments[0][amount]" class="form-control" placeholder="Valor"></div>
            </div>
            <button type="submit" class="btn btn-success">Concluir venda</button>
        </form>
    @endif

    <div class="alert alert-secondary" role="alert">
        Cupom não fiscal pronto para impressão. Placeholder fiscal mantido para integração futura.
    </div>
</div>
@endsection
