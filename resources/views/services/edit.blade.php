@extends('layouts.app')

@section('title', 'Editar serviço')

@section('content')
<h1 class="h3 mb-3">Editar serviço</h1>
<form method="post" action="{{ route('services.update', $service) }}" class="card shadow-sm p-4">
    @method('put')
    @include('services._form')
</form>
@endsection
