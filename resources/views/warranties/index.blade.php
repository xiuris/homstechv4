@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Garantias</h1>
        <a class="btn btn-primary" href="{{ route('warranties.create') }}">Registrar garantia</a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Produto/Serviço</th>
                <th>OS/Venda</th>
                <th>Vigência</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warranties as $warranty)
                <tr>
                    <td>{{ $warranty->customer?->name ?? '—' }}</td>
                    <td>{{ $warranty->product?->name ?? $warranty->service?->name ?? '—' }}</td>
                    <td>
                        @if($warranty->orderService)
                            OS #{{ $warranty->orderService->id }}
                        @elseif($warranty->sale)
                            Venda #{{ $warranty->sale->id }}
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $warranty->starts_at?->format('d/m/Y') }} → {{ $warranty->expires_at?->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($warranty->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
