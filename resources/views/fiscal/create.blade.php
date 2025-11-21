@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Nova Emissão Fiscal</h1>

    <form method="POST" action="{{ route('fiscal-documents.store') }}" class="card card-body">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tipo</label>
                <select name="document_type" class="form-select" required>
                    <option value="nfe">NF-e</option>
                    <option value="nfce">NFC-e</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">UF</label>
                <input type="text" name="uf" class="form-control" maxlength="2" placeholder="UF" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Total</label>
                <input type="number" step="0.01" name="total" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cliente (opcional)</label>
                <input type="number" name="customer_id" class="form-control" placeholder="ID do cliente">
            </div>
            <div class="col-md-6">
                <label class="form-label">Venda relacionada</label>
                <input type="number" name="sale_id" class="form-control" placeholder="ID da venda">
            </div>
            <div class="col-md-6">
                <label class="form-label">Ordem de Serviço relacionada</label>
                <input type="number" name="order_service_id" class="form-control" placeholder="ID da OS">
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            <a href="{{ route('fiscal-documents.index') }}" class="btn btn-secondary me-2">Voltar</a>
            <button type="submit" class="btn btn-primary">Agendar emissão</button>
        </div>
    </form>
</div>
@endsection
