@extends('layouts.app')

@section('title', 'Serviços')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Serviços</h1>
    <a class="btn btn-primary" href="{{ route('services.create') }}">Novo serviço</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Duração</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $service)
                    <tr>
                        <td><a href="{{ route('services.show', $service) }}">{{ $service->name }}</a></td>
                        <td>{{ $service->category ?? '-' }}</td>
                        <td>R$ {{ number_format($service->price, 2, ',', '.') }}</td>
                        <td>{{ $service->duration_minutes ? $service->duration_minutes . ' min' : '-' }}</td>
                        <td>{{ $service->is_active ? 'Ativo' : 'Inativo' }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('services.edit', $service) }}">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Nenhum serviço cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($services->hasPages())
        <div class="card-footer">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection
