@extends('layouts.app')

@section('title', 'Editar produto')

@section('content')
<h1 class="h3 mb-3">Editar produto</h1>
<form method="post" action="{{ route('products.update', $product) }}" class="card shadow-sm p-4">
    @method('put')
    @include('products._form')
</form>
@endsection
