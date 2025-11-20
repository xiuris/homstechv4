@extends('layouts.app')

@section('title', $service->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">{{ $service->name }}</h1>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('services.edit', $service) }}">Editar</a>
        <form method="post" action="{{ route('services.destroy', $service) }}" onsubmit="return confirm('Deseja remover este serviço?');">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-outline-danger">Excluir</button>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Categoria</dt>
            <dd class="col-sm-9">{{ $service->category ?? '-' }}</dd>

            <dt class="col-sm-3">Preço</dt>
            <dd class="col-sm-9">R$ {{ number_format($service->price, 2, ',', '.') }}</dd>

            <dt class="col-sm-3">Duração</dt>
            <dd class="col-sm-9">{{ $service->duration_minutes ? $service->duration_minutes . ' min' : '-' }}</dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9">{{ $service->is_active ? 'Ativo' : 'Inativo' }}</dd>

            <dt class="col-sm-3">Descrição</dt>
            <dd class="col-sm-9">{{ $service->description ?? '-' }}</dd>
        </dl>
    </div>
</div>
@endsection
