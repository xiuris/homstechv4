@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lançar Conta a Receber</h1>
    <form method="post" action="{{ route('receivables.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Cliente (opcional)</label>
            <input type="number" name="customer_id" class="form-control" />
        </div>
        <div class="mb-3"><label class="form-label">Valor total</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Parcelas</label><input type="number" name="installments" value="1" min="1" max="12" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Primeiro vencimento</label><input type="date" name="first_due_date" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Notas</label><textarea name="notes" class="form-control"></textarea></div>
        <button class="btn btn-primary">Salvar</button>
    </form>
</div>
@endsection
