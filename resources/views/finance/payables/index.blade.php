@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Contas a Pagar</h1>
    <form method="get" class="row g-2 mb-3">
        <div class="col"><input class="form-control" name="status" placeholder="Status" value="{{ $filters['status'] ?? '' }}"></div>
        <div class="col"><input class="form-control" name="category" placeholder="Categoria" value="{{ $filters['category'] ?? '' }}"></div>
        <div class="col"><input class="form-control" type="date" name="due_from" value="{{ $filters['due_from'] ?? '' }}"></div>
        <div class="col"><input class="form-control" type="date" name="due_to" value="{{ $filters['due_to'] ?? '' }}"></div>
        <div class="col-auto"><button class="btn btn-primary">Filtrar</button></div>
    </form>
    <a href="{{ route('payables.create') }}" class="btn btn-success mb-3">Nova despesa</a>
    <table class="table table-striped">
        <thead><tr><th>Fornecedor</th><th>Categoria</th><th>Valor</th><th>Vencimento</th><th>Recorrente</th></tr></thead>
        <tbody>
            @foreach($payables as $item)
                <tr>
                    <td>{{ $item->vendor_name }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ number_format($item->amount, 2, ',', '.') }}</td>
                    <td>{{ $item->due_date->format('d/m/Y') }}</td>
                    <td>{{ $item->is_recurring ? 'Sim' : 'Não' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
