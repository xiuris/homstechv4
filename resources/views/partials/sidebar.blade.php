<aside class="sidebar">
    <div class="sidebar-brand d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <span class="brand-mark">H</span>
            <div>
                <div class="brand-title">{{ config('app.name') }}</div>
                <small class="text-muted">Operações & Serviços</small>
            </div>
        </div>
        <span class="badge bg-primary-subtle text-primary fw-semibold">UI</span>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>
        @if (Route::has('order-services.index'))
            <a class="nav-link {{ request()->routeIs('order-services.*') ? 'active' : '' }}" href="{{ route('order-services.index') }}">
                <i class="bi bi-wrench-adjustable-circle me-2"></i>Ordens de Serviço
            </a>
        @else
            <a class="nav-link disabled" href="#" title="Configure as rotas de OS">
                <i class="bi bi-wrench-adjustable-circle me-2"></i>Ordens de Serviço
            </a>
        @endif
        <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" href="{{ route('pos.create') }}">
            <i class="bi bi-bag-check me-2"></i>PDV
        </a>
        <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
            <i class="bi bi-people me-2"></i>Clientes
        </a>
        @if (Route::has('resellers.index'))
            <a class="nav-link {{ request()->routeIs('resellers.*') ? 'active' : '' }}" href="{{ route('resellers.index') }}">
                <i class="bi bi-shop-window me-2"></i>Revendedores
            </a>
        @endif
        <a class="nav-link {{ request()->routeIs('receivables.*') || request()->routeIs('payables.*') || request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('receivables.index') }}">
            <i class="bi bi-cash-coin me-2"></i>Financeiro
        </a>
        <a class="nav-link {{ request()->routeIs('insights.*') ? 'active' : '' }}" href="{{ route('insights.index') }}">
            <i class="bi bi-gear-wide-connected me-2"></i>Configurações
        </a>
    </nav>
</aside>
