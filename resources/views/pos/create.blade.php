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
    <input type="hidden" name="order_service_id" id="order_service_id">
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
            @can('pdv.invoice_os')
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#orderServiceModal">
                    <i class="bi bi-download me-1"></i>Importar OS
                </button>
            @endcan
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

@can('pdv.invoice_os')
    <div class="modal fade" id="orderServiceModal" tabindex="-1" aria-labelledby="orderServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="orderServiceModalLabel">Importar Ordem de Serviço</h5>
                        <small class="text-muted">Selecione uma OS aprovada ou pronta para faturar</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="orderServiceFilters" class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Número</label>
                            <input type="number" name="number" class="form-control" placeholder="Ex: 1201">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Cliente</label>
                            <input type="text" name="customer" class="form-control" placeholder="Nome do cliente">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data de abertura</label>
                            <input type="date" name="opened_at" class="form-control">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>

                    <div class="table-responsive border rounded">
                        <table class="table mb-0" id="orderServiceResults">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Status</th>
                                <th>Abertura</th>
                                <th>Total</th>
                                <th class="text-end">Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr><td colspan="6" class="text-center text-muted py-3">Busque uma OS para importar</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <nav class="mt-3" id="orderServicePagination"></nav>
                </div>
            </div>
        </div>
    </div>
@endcan
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

    const baseItemRow = document.querySelector('#items-table tbody tr').cloneNode(true);

    function createItemRow(data = {}, index = 0) {
        const row = baseItemRow.cloneNode(true);
        row.querySelectorAll('input, select').forEach((input) => {
            const name = input.getAttribute('name');
            input.setAttribute('name', name.replace(/items\[\d+\]/, `items[${index}]`));

            if (input.matches('.item-type')) {
                input.value = data.item_type ?? 'product';
            }

            if (input.matches('.product-select')) {
                input.classList.toggle('d-none', data.item_type === 'service');
                if (data.product_id) {
                    input.value = data.product_id;
                }
            }

            if (input.matches('.service-select')) {
                input.classList.toggle('d-none', data.item_type !== 'service');
                if (data.service_id) {
                    input.value = data.service_id;
                }
            }

            if (input.type === 'number' && input.name.includes('[quantity]')) {
                input.value = data.quantity ?? 1;
            }

            if (input.type === 'number' && input.name.includes('[discount]')) {
                input.value = data.discount ?? 0;
            }
        });

        return row;
    }

    document.getElementById('add-item').addEventListener('click', function () {
        const tbody = document.querySelector('#items-table tbody');
        tbody.appendChild(createItemRow({}, tbody.children.length));
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

    @can('pdv.invoice_os')
    const orderServiceModal = document.getElementById('orderServiceModal');
    const orderServiceFilters = document.getElementById('orderServiceFilters');
    const orderServiceTableBody = document.querySelector('#orderServiceResults tbody');
    const orderServicePagination = document.getElementById('orderServicePagination');

    async function loadOrderServices(url = '{{ route('pos.order-services.search') }}') {
        const formData = new FormData(orderServiceFilters);
        const params = new URLSearchParams(formData);
        const fetchUrl = url.includes('?') ? `${url}&${params.toString()}` : `${url}?${params.toString()}`;
        const response = await fetch(fetchUrl);
        const data = await response.json();
        renderOrderServices(data);
    }

    function renderOrderServices(data) {
        orderServiceTableBody.innerHTML = '';

        if (!data.data || data.data.length === 0) {
            orderServiceTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Nenhuma OS encontrada.</td></tr>';
            orderServicePagination.innerHTML = '';
            return;
        }

        data.data.forEach(os => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>#${os.id}</td>
                <td>${os.customer?.name ?? 'Cliente'}</td>
                <td><span class="badge bg-info-subtle text-info">${os.status}</span></td>
                <td>${os.opened_at ? new Date(os.opened_at).toLocaleDateString() : '-'}</td>
                <td>R$ ${Number(os.total_value ?? 0).toFixed(2)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-primary select-order-service" data-order='${JSON.stringify(os)}'>Importar</button>
                </td>`;
            orderServiceTableBody.appendChild(row);
        });

        orderServicePagination.innerHTML = '';
        const paginationList = document.createElement('ul');
        paginationList.className = 'pagination pagination-sm mb-0';

        data.links.forEach(link => {
            const li = document.createElement('li');
            li.className = `page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''}`;
            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.innerHTML = link.label;
            a.addEventListener('click', (e) => {
                e.preventDefault();
                if (link.url) {
                    loadOrderServices(link.url);
                }
            });
            li.appendChild(a);
            paginationList.appendChild(li);
        });

        orderServicePagination.appendChild(paginationList);
    }

    orderServiceFilters?.addEventListener('submit', function (e) {
        e.preventDefault();
        loadOrderServices();
    });

    orderServiceTableBody?.addEventListener('click', function (e) {
        const button = e.target.closest('.select-order-service');
        if (!button) return;

        const os = JSON.parse(button.dataset.order);
        document.getElementById('order_service_id').value = os.id;

        const customerSelect = document.querySelector('select[name="customer_id"]');
        if (customerSelect && os.customer_id) {
            customerSelect.value = os.customer_id;
        }

        const tbody = document.querySelector('#items-table tbody');
        tbody.innerHTML = '';
        (os.items ?? []).forEach((item, index) => {
            tbody.appendChild(createItemRow(item, index));
        });

        const modeSelect = document.querySelector('select[name="mode"]');
        if (modeSelect) {
            modeSelect.value = 'sale';
            modeSelect.setAttribute('readonly', 'readonly');
        }

        const modalInstance = bootstrap.Modal.getInstance(orderServiceModal);
        modalInstance.hide();
    });

    orderServiceModal?.addEventListener('shown.bs.modal', () => loadOrderServices());
    @endcan
</script>
@endpush
