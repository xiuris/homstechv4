@extends('layouts.app')

@section('title', 'Novo cliente')

@section('content')
<h1 class="h3 mb-3">Novo cliente</h1>
<form method="post" action="{{ route('customers.store') }}" class="card shadow-sm p-4">
    @include('customers._form')
</form>
@endsection
