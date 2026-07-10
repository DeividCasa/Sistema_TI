@php
    $nombreUsuario = session('usuario_nombre', 'Mi cuenta');
    $iniciales = collect(explode(' ', trim($nombreUsuario)))
        ->filter()
        ->take(2)
        ->map(fn($parte) => mb_substr($parte, 0, 1))
        ->implode('');
    $iniciales = $iniciales ?: 'LJ';
@endphp

<header class="topbar">
    <div class="topbar-brand">
        <a href="{{ route('cliente.inicio') }}">
            <img src="{{ asset('images/logo.png') }}" width="110" height="99" alt="">
        </a>
    </div>

    <div class="topbar-right">
        <a href="{{ route('cliente.uniformes.index') }}" class="topbar-link">Uniformes escolares</a>
        <a href="{{ route('cliente.chompas.index') }}" class="topbar-link">Chompas</a>
        <a href="{{ route('cliente.disenios.index') }}" class="topbar-link">Mis diseños</a>
        <a href="{{ route('cliente.pedidos.index') }}" class="topbar-link">Mis pedidos</a>
        <a href="{{ route('cliente.carrito.index') }}" class="topbar-link">Ver mi carrito</a>
        <div class="topbar-divider"></div>
        <button class="btn-theme" onclick="toggleTheme()" title="Cambiar tema">
            <svg class="icon-moon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            <svg class="icon-sun"  viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        </button>
        <div class="account-menu-wrap" id="account-menu-wrap">
            <button type="button" class="nav-avatar" onclick="toggleAccountMenu()" aria-label="Cuenta" style="border:none;">
                {{ strtoupper($iniciales) }}
            </button>
            <div class="account-menu" id="account-menu">
                <div class="account-head">
                    <div class="account-name">{{ $nombreUsuario }}</div>
                    <div class="account-role">Mi cuenta</div>
                </div>
                <a href="{{ route('logout') }}" class="account-link danger">
                    <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Salir
                </a>
            </div>
        </div>
    </div>
</header>
