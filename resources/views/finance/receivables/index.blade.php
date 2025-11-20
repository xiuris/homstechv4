@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Contas a Receber</h1>
    <form method="get" class="row g-2 mb-3">
        <div class="col"><input class="form-control" name="status" placeholder="Status" value="{{ $filters['status'] ?? '' }}"></div>
        <div class="col"><input class="form-control" type="date" name="due_from" value="{{ $filters['due_from'] ?? '' }}"></div>
        <div class="col"><input class="form-control" type="date" name="due_to" value="{{ $filters['due_to'] ?? '' }}"></div>
        <div class="col-auto"><button class="btn btn-primary">Filtrar</button></div>
    </form>
    <a href="{{ route('receivables.create') }}" class="btn btn-success mb-3">Novo Lançamento</a>
    <table class="table table-striped">
        <thead><tr><th>Cliente</th><th>Valor</th><th>Parcela</th><th>Vencimento</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($receivables as $item)
                <tr>
                    <td>{{ $item->customer?->name ?? '-' }}</td>
                    <td>{{ number_format($item->amount, 2, ',', '.') }}</td>
                    <td>{{ $item->installment_number }}/{{ $item->installments_total }}</td>
                    <td>{{ $item->due_date->format('d/m/Y') }}</td>
                    <td>{{ $item->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
