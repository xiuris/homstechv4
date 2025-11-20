@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Relatórios</h1>
    <p>Total de Vendas: R$ {{ number_format($sales_total, 2, ',', '.') }}</p>
    <p>Total de OS: R$ {{ number_format($order_total, 2, ',', '.') }}</p>
    <a href="{{ route('reports.export.excel') }}" class="btn btn-outline-primary">Exportar Excel</a>
    <a href="{{ route('reports.export.pdf') }}" class="btn btn-outline-secondary">Exportar PDF</a>
</div>
@endsection
