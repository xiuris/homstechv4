@extends('layouts.app')

@section('title', $customer->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">{{ $customer->name }}</h1>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('customers.edit', $customer) }}">Editar</a>
        <form method="post" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Deseja remover este cliente?');">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-outline-danger">Excluir</button>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Documento</dt>
            <dd class="col-sm-9">{{ $customer->document }}</dd>

            <dt class="col-sm-3">E-mail</dt>
            <dd class="col-sm-9">{{ $customer->email ?? '-' }}</dd>

            <dt class="col-sm-3">Telefone</dt>
            <dd class="col-sm-9">{{ $customer->phone ?? '-' }}</dd>

            <dt class="col-sm-3">Celular</dt>
            <dd class="col-sm-9">{{ $customer->mobile_phone ?? '-' }}</dd>

            <dt class="col-sm-3">Endereço</dt>
            <dd class="col-sm-9">{{ $customer->address ?? '-' }}</dd>

            <dt class="col-sm-3">Cidade/UF</dt>
            <dd class="col-sm-9">{{ $customer->city ?? '-' }} / {{ $customer->state ?? '-' }}</dd>

            <dt class="col-sm-3">CEP</dt>
            <dd class="col-sm-9">{{ $customer->zip_code ?? '-' }}</dd>

            <dt class="col-sm-3">Revendedor</dt>
            <dd class="col-sm-9">{{ optional($customer->reseller)->name ?? '-' }}</dd>

            <dt class="col-sm-3">Observações</dt>
            <dd class="col-sm-9">{{ $customer->notes ?? '-' }}</dd>
        </dl>
    </div>
</div>
@endsection
