<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <a href="{{ route('cliente.dashboard') }}" class="brand-link">
            <span class="brand-name">Raffly</span>
        </a>
        <button class="sidebar-close" id="sidebarClose" aria-label="Cerrar menú">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <nav class="sidebar-nav">

        <span class="nav-section-label">Mi panel</span>

        <a href="{{ route('cliente.dashboard') }}"
           class="nav-item {{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Inicio</span>
        </a>

        <a href="{{ route('cliente.mis-numeros') }}"
           class="nav-item {{ request()->routeIs('cliente.mis-numeros') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i>
            <span>Historial</span>
        </a>

        <span class="nav-section-label">Explorar</span>

        <a href="{{ route('cliente.rifas') }}"
           class="nav-item {{ request()->routeIs('cliente.rifas') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span>Rifas disponibles</span>
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
