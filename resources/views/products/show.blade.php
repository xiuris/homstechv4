@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">{{ $product->name }}</h1>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('products.edit', $product) }}">Editar</a>
        <form method="post" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Deseja remover este produto?');">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-outline-danger">Excluir</button>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">SKU</dt>
            <dd class="col-sm-9">{{ $product->sku }}</dd>

            <dt class="col-sm-3">Categoria</dt>
            <dd class="col-sm-9">{{ $product->category ?? '-' }}</dd>

            <dt class="col-sm-3">Preço varejo</dt>
            <dd class="col-sm-9">R$ {{ number_format($product->retail_price, 2, ',', '.') }}</dd>

            <dt class="col-sm-3">Preço atacado</dt>
            <dd class="col-sm-9">{{ $product->wholesale_price ? 'R$ ' . number_format($product->wholesale_price, 2, ',', '.') : '-' }}</dd>

            <dt class="col-sm-3">Estoque</dt>
            <dd class="col-sm-9">{{ $product->stock }} (mínimo {{ $product->stock_minimum }})</dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9">{{ $product->is_active ? 'Ativo' : 'Inativo' }}</dd>

            <dt class="col-sm-3">Descrição</dt>
            <dd class="col-sm-9">{{ $product->description ?? '-' }}</dd>
        </dl>
    </div>
</div>
@endsection
