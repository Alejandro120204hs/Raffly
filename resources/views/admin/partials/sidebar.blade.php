<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            
            <span class="brand-name">Raffly</span>
        </a>
        <button class="sidebar-close" id="sidebarClose" aria-label="Cerrar menú">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <nav class="sidebar-nav">

        <span class="nav-section-label">Principal</span>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        <span class="nav-section-label">Gestión</span>

        <a href="{{ route('admin.rifas.index') }}" class="nav-item {{ request()->routeIs('admin.rifas.*') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i>
            <span>Rifas</span>
        </a>

        <a href="{{ route('admin.usuarios.index') }}" class="nav-item {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Usuarios</span>
        </a>

        <span class="nav-section-label">Finanzas</span>

        <a href="{{ route('admin.ingresos.index') }}" class="nav-item {{ request()->routeIs('admin.ingresos.*') ? 'active' : '' }}">
            <i class="fas fa-dollar-sign"></i>
            <span>Ingresos</span>
        </a>

        <div class="sidebar-divider"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item nav-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </button>
        </form>

    </nav>

</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
