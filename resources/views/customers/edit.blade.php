@extends('layouts.app')

@section('title', 'Editar cliente')

@section('content')
<h1 class="h3 mb-3">Editar cliente</h1>
<form method="post" action="{{ route('customers.update', $customer) }}" class="card shadow-sm p-4">
    @method('put')
    @include('customers._form')
</form>
@endsection
