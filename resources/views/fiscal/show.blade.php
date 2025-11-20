@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Documento #{{ $document->id }}</h1>
        <a href="{{ route('fiscal-documents.index') }}" class="btn btn-secondary">Voltar</a>
    </div>

    <div class="card mb-3">
        <div class="card-body row g-3">
            <div class="col-md-3">
                <strong>Tipo</strong>
                <div>{{ strtoupper($document->document_type) }}</div>
            </div>
            <div class="col-md-2">
                <strong>UF</strong>
                <div>{{ $document->uf }}</div>
            </div>
            <div class="col-md-3">
                <strong>Status</strong>
                <div><span class="badge bg-info text-dark">{{ $document->status }}</span></div>
            </div>
            <div class="col-md-4">
                <strong>Protocolo</strong>
                <div>{{ $document->protocol ?? '—' }}</div>
            </div>
            <div class="col-md-3">
                <strong>Total</strong>
                <div>R$ {{ number_format($document->total, 2, ',', '.') }}</div>
            </div>
            <div class="col-md-4">
                <strong>XML</strong>
                <div>
                    @if($document->xml_path)
                        <a href="{{ route('fiscal-documents.download.xml', $document) }}" class="btn btn-sm btn-outline-primary">Baixar XML</a>
                    @else
                        Não disponível
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <strong>PDF</strong>
                <div>
                    @if($document->pdf_path)
                        <a href="{{ route('fiscal-documents.download.pdf', $document) }}" class="btn btn-sm btn-outline-primary">Baixar PDF</a>
                    @else
                        Não disponível
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Logs de emissão</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Mensagem</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($document->logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->status }}</td>
                            <td>{{ $log->message ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-3">Nenhum log registrado ainda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
