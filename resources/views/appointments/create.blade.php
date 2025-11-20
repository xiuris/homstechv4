@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Novo Agendamento</h1>

    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Cliente</label>
                <select name="customer_id" class="form-select">
                    <option value="">Selecione</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ordem de Serviço</label>
                <select name="order_service_id" class="form-select">
                    <option value="">Selecione</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}">{{ $order->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Técnico</label>
                <select name="technician_id" class="form-select">
                    <option value="">Selecione</option>
                    @foreach($technicians as $technician)
                        <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Início</label>
                <input type="datetime-local" name="starts_at" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fim</label>
                <input type="datetime-local" name="ends_at" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="scheduled">Agendado</option>
                    <option value="confirmed">Confirmado</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-center mt-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_blocked" value="1" id="blocked">
                    <label class="form-check-label" for="blocked">Bloqueio</label>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Notas</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <button class="btn btn-primary" type="submit">Salvar</button>
    </form>
</div>
@endsection
