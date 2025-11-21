@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Registrar Garantia</h1>

    <form method="POST" action="{{ route('warranties.store') }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Cliente</label>
                <select name="customer_id" class="form-select">
                    <option value="">Selecione</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ordem de Serviço</label>
                <select name="order_service_id" class="form-select">
                    <option value="">Selecione</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}">{{ $order->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Venda</label>
                <select name="sale_id" class="form-select">
                    <option value="">Selecione</option>
                    @foreach($sales as $sale)
                        <option value="{{ $sale->id }}">#{{ $sale->id }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Produto</label>
                <select name="product_id" class="form-select">
                    <option value="">Selecione</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Serviço</label>
                <select name="service_id" class="form-select">
                    <option value="">Selecione</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Início</label>
                <input type="date" name="starts_at" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Fim</label>
                <input type="date" name="expires_at" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active">Ativa</option>
                    <option value="expired">Expirada</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Observações</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
        </div>

        <button class="btn btn-primary" type="submit">Salvar</button>
    </form>
</div>
@endsection
