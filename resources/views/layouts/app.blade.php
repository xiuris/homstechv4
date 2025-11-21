<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - @yield('title', 'Painel')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/homstech-os.css') }}">
</head>
<body>
<div class="app-shell">
    @include('partials.sidebar')
    <div class="app-content">
        @include('partials.topbar')
        <main class="page-content">
            @if (session('status'))
                <div class="alert alert-success shadow-sm mb-3">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm mb-4">
                    <h6 class="fw-semibold mb-2">Corrija os erros abaixo:</h6>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @hasSection('breadcrumbs')
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        @yield('breadcrumbs')
                    </ol>
                </nav>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
