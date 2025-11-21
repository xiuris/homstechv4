@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nova Despesa</h1>
    <form method="post" action="{{ route('payables.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3"><label class="form-label">Fornecedor</label><input name="vendor_name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Categoria</label><input name="category" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Valor</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Vencimento</label><input type="date" name="due_date" class="form-control" required></div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="recurring" name="is_recurring">
            <label class="form-check-label" for="recurring">Recorrente</label>
        </div>
        <div class="mb-3"><label class="form-label">Intervalo</label>
            <select name="recurrence_interval" class="form-select">
                <option value="">Selecione</option>
                <option value="monthly">Mensal</option>
                <option value="weekly">Semanal</option>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Anexo</label><input type="file" name="attachment" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Notas</label><textarea name="notes" class="form-control"></textarea></div>
        <button class="btn btn-primary">Salvar</button>
    </form>
</div>
@endsection
