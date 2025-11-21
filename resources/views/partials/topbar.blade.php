<header class="topbar d-flex align-items-center justify-content-between">
    <div>
        <h1 class="page-title mb-0">@yield('title', 'Painel')</h1>
        <p class="text-muted small mb-0">Bem-vindo ao {{ config('app.name') }}</p>
    </div>
    <div class="d-flex align-items-center gap-3">
        <form class="topbar-search" role="search">
            <i class="bi bi-search"></i>
            <input type="search" class="form-control" placeholder="Buscar no sistema">
        </form>
        <div class="dropdown">
            <button class="btn btn-outline-primary rounded-pill d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar">HO</span>
                <span class="d-none d-md-inline">Usuário</span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><h6 class="dropdown-header">{{ config('app.name') }}</h6></li>
                <li><a class="dropdown-item" href="{{ route('insights.index') }}"><i class="bi bi-gear me-2"></i>Configurações</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
            </ul>
        </div>
    </div>
</header>
