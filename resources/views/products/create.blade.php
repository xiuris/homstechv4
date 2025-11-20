@extends('layouts.app')

@section('title', 'Novo produto')

@section('content')
<h1 class="h3 mb-3">Novo produto</h1>
<form method="post" action="{{ route('products.store') }}" class="card shadow-sm p-4">
    @include('products._form')
</form>
@endsection
