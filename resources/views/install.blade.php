@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Instalador</h1>
            <p class="text-muted">Configure a conexão, crie o admin e gere o .env automaticamente.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header">Requisitos</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($requirements as $check)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $check['label'] }}</span>
                                @if($check['passed'])
                                    <span class="badge bg-success">OK</span>
                                @else
                                    <span class="badge bg-danger">Pendente</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('install.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Aplicação</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nome da Aplicação</label>
                            <input type="text" name="app_name" value="{{ old('app_name', $defaults['app_name']) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL</label>
                            <input type="url" name="app_url" value="{{ old('app_url', $defaults['app_url']) }}" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Banco de Dados</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Driver</label>
                            <select name="db_driver" class="form-select">
                                <option value="mysql" @selected(old('db_driver', 'mysql') === 'mysql')>MySQL</option>
                                <option value="sqlite" @selected(old('db_driver') === 'sqlite')>SQLite (para testes)</option>
                            </select>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-8">
                                <label class="form-label">Host</label>
                                <input type="text" name="db_host" value="{{ old('db_host', $defaults['db_host']) }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Porta</label>
                                <input type="number" name="db_port" value="{{ old('db_port', $defaults['db_port']) }}" class="form-control">
                            </div>
                        </div>
                        <div class="mt-3 mb-3">
                            <label class="form-label">Base</label>
                            <input type="text" name="db_database" value="{{ old('db_database', $defaults['db_database']) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Usuário</label>
                            <input type="text" name="db_username" value="{{ old('db_username', $defaults['db_username']) }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="db_password" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Administrador</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="admin_name" value="{{ old('admin_name') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="admin_email" value="{{ old('admin_email') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="admin_password" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Empresa</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Razão Social</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $defaults['company_name']) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nome Fantasia</label>
                            <input type="text" name="company_trade_name" value="{{ old('company_trade_name') }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="company_email" value="{{ old('company_email') }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Instalar e Iniciar</button>
        </div>
    </form>
</div>
@endsection
