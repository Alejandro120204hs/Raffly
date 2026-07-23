<header class="topbar">

    <div class="topbar-left">
        <button class="topbar-toggle" id="sidebarToggle" aria-label="Abrir menú">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="topbar-title">@yield('page-title', 'Inicio')</h1>
    </div>

    <div class="topbar-right">
        <div class="topbar-user" id="userMenuTrigger">
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="user-role">Cliente</span>
            </div>
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>

        <div class="user-dropdown" id="userDropdown">
            <div class="dropdown-header">
                <span class="dropdown-name">{{ auth()->user()->name }}</span>
                <span class="dropdown-email">{{ auth()->user()->email }}</span>
            </div>
            <div class="dropdown-divider"></div>
            <a href="{{ route('cliente.perfil') }}" class="dropdown-item">
                <i class="fas fa-user"></i> Mi Perfil
            </a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item dropdown-logout">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </div>

</header>
