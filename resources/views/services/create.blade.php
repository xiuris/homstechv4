@extends('layouts.app')

@section('title', 'Novo serviço')

@section('content')
<h1 class="h3 mb-3">Novo serviço</h1>
<form method="post" action="{{ route('services.store') }}" class="card shadow-sm p-4">
    @include('services._form')
</form>
@endsection
