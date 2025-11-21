@extends('layouts.app')

@section('title', 'PDV')

@section('breadcrumbs')
    <li class="breadcrumb-item">PDV</li>
    <li class="breadcrumb-item active" aria-current="page">Nova venda</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold mb-0">Registro de venda</h2>
        <small class="text-muted">Escolha o cliente, itens e finalize com múltiplos pagamentos</small>
    </div>
    <span class="badge bg-primary-subtle text-primary">Homstech OS</span>
</div>

<form method="POST" action="{{ route('pos.store') }}" class="needs-validation" novalidate>
    @csrf
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">Cliente</label>
            <select name="customer_id" class="form-select" required>
                <option value="">Selecione...</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback">Escolha um cliente.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Revendedor</label>
            <select name="reseller_id" class="form-select">
                <option value="">Selecione...</option>
                @foreach($resellers as $reseller)
                    <option value="{{ $reseller->id }}">{{ $reseller->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Modo</label>
            <select name="mode" class="form-select">
                <option value="sale">Venda</option>
                <option value="quotation">Orçamento</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Preço</label>
            <select name="pricing_mode" class="form-select">
                <option value="retail">Varejo</option>
                <option value="wholesale">Atacado</option>
            </select>
        </div>
    </div>
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <label class="form-label">Desconto total</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="number" step="0.01" min="0" name="discount_total" class="form-control" value="0">
            </div>
        </div>
        <div class="col-md-9 d-flex align-items-end justify-content-end gap-2">
            <input type="search" class="form-control w-auto" placeholder="Buscar itens">
            <button type="button" class="btn btn-outline-primary" id="add-item">Adicionar item</button>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Itens</h5>
            <small class="text-muted">Produtos e serviços da venda</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="items-table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Produto/Serviço</th>
                            <th>Qtd</th>
                            <th>Desconto</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="items[0][item_type]" class="form-select item-type">
                                    <option value="product">Produto</option>
                                    <option value="service">Serviço</option>
                                </select>
                            </td>
                            <td class="item-selector">
                                <select name="items[0][item_id]" class="form-select product-select">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} ({{ number_format($product->retail_price, 2, ',', '.') }})</option>
                                    @endforeach
                                </select>
                                <select name="items[0][item_id]" class="form-select service-select d-none">
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }} ({{ number_format($service->price, 2, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" min="1" name="items[0][quantity]" class="form-control" value="1" required></td>
                            <td><input type="number" min="0" step="0.01" name="items[0][discount]" class="form-control" value="0"></td>
                            <td><button type="button" class="btn btn-link text-danger remove-row"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pagamentos</h5>
            <small class="text-muted">Cadastre múltiplos métodos</small>
        </div>
        <div class="card-body">
            <div id="payments" class="row g-3 mb-2 payment-row">
                <div class="col-md-4">
                    <input type="text" name="payments[0][method]" class="form-control" placeholder="Forma (ex: dinheiro)" required>
                    <div class="invalid-feedback">Informe a forma.</div>
                </div>
                <div class="col-md-4">
                    <input type="number" step="0.01" min="0" name="payments[0][amount]" class="form-control" placeholder="Valor" required>
                    <div class="invalid-feedback">Informe o valor.</div>
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <button class="btn btn-outline-danger remove-payment" type="button"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            <button type="button" class="btn btn-outline-secondary" id="add-payment"><i class="bi bi-plus-circle me-1"></i>Adicionar pagamento</button>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="submit" class="btn btn-success">Registrar</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    document.getElementById('add-item').addEventListener('click', function () {
        const tbody = document.querySelector('#items-table tbody');
        const index = tbody.children.length;
        const row = tbody.children[0].cloneNode(true);
        row.querySelectorAll('input, select').forEach((input) => {
            const name = input.getAttribute('name');
            input.setAttribute('name', name.replace(/items\[\d+\]/, `items[${index}]`));
            if (input.tagName === 'INPUT') {
                input.value = input.type === 'number' ? 1 : '';
            }
        });
        row.querySelector('.service-select').classList.add('d-none');
        row.querySelector('.product-select').classList.remove('d-none');
        tbody.appendChild(row);
    });

    document.getElementById('items-table').addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            const tbody = document.querySelector('#items-table tbody');
            if (tbody.children.length > 1) {
                e.target.closest('tr').remove();
            }
        }
    });

    document.getElementById('items-table').addEventListener('change', function (e) {
        if (e.target.classList.contains('item-type')) {
            const row = e.target.closest('tr');
            const isProduct = e.target.value === 'product';
            row.querySelector('.product-select').classList.toggle('d-none', !isProduct);
            row.querySelector('.service-select').classList.toggle('d-none', isProduct);
        }
    });

    document.getElementById('add-payment').addEventListener('click', function () {
        const container = document.getElementById('payments');
        const index = container.querySelectorAll('.payment-row').length;
        const row = container.querySelector('.payment-row').cloneNode(true);
        row.querySelectorAll('input').forEach((input) => {
            const name = input.getAttribute('name');
            input.setAttribute('name', name.replace(/payments\[\d+\]/, `payments[${index}]`));
            input.value = '';
        });
        container.appendChild(row);
    });

    document.getElementById('payments').addEventListener('click', function (e) {
        if (e.target.closest('.remove-payment')) {
            const container = document.getElementById('payments');
            if (container.querySelectorAll('.payment-row').length > 1) {
                e.target.closest('.payment-row').remove();
            }
        }
    });
</script>
@endpush
