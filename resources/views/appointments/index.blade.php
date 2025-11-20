@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Agendamentos</h1>
        <a class="btn btn-primary" href="{{ route('appointments.create') }}">Novo agendamento</a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Início</th>
                <th>Fim</th>
                <th>Técnico</th>
                <th>Cliente</th>
                <th>OS</th>
                <th>Status</th>
                <th>Bloqueio</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->starts_at->format('d/m H:i') }}</td>
                    <td>{{ $appointment->ends_at->format('d/m H:i') }}</td>
                    <td>{{ $appointment->technician?->name ?? '—' }}</td>
                    <td>{{ $appointment->customer?->name ?? '—' }}</td>
                    <td>{{ $appointment->orderService?->title ?? '—' }}</td>
                    <td>{{ $appointment->status }}</td>
                    <td>{{ $appointment->is_blocked ? 'Sim' : 'Não' }}</td>
                    <td>
                        <form method="POST" action="{{ route('appointments.update', $appointment) }}">
                            @csrf
                            @method('PUT')
                            <div class="d-flex gap-2">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="scheduled" @selected($appointment->status === 'scheduled')>Agendado</option>
                                    <option value="confirmed" @selected($appointment->status === 'confirmed')>Confirmado</option>
                                    <option value="done" @selected($appointment->status === 'done')>Concluído</option>
                                </select>
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" name="is_blocked" value="1" @checked($appointment->is_blocked)>
                                    <label class="form-check-label">Bloqueio</label>
                                </div>
                                <button class="btn btn-sm btn-secondary" type="submit">Salvar</button>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
