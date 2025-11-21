@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Documentos Fiscais</h1>
        <a href="{{ route('fiscal-documents.create') }}" class="btn btn-primary">Nova emissão</a>
    </div>

    <form class="row g-2 mb-3" method="GET" action="{{ route('fiscal-documents.index') }}">
        <div class="col-md-3">
            <input type="text" name="status" class="form-control" placeholder="Status" value="{{ $filters['status'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <input type="text" name="uf" class="form-control" placeholder="UF" value="{{ $filters['uf'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary" type="submit">Filtrar</button>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>UF</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Agendado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                        <tr>
                            <td>{{ $document->id }}</td>
                            <td>{{ strtoupper($document->document_type) }}</td>
                            <td>{{ $document->uf }}</td>
                            <td><span class="badge bg-info text-dark">{{ $document->status }}</span></td>
                            <td>R$ {{ number_format($document->total, 2, ',', '.') }}</td>
                            <td>{{ optional($document->scheduled_at)->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('fiscal-documents.show', $document) }}">Detalhes</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Nenhum documento encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
