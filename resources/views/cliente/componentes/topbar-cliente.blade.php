@php
    $nombreUsuario = session('usuario_nombre', 'Mi cuenta');
    $iniciales = collect(explode(' ', trim($nombreUsuario)))
        ->filter()
        ->take(2)
        ->map(fn($parte) => mb_substr($parte, 0, 1))
        ->implode('');
    $iniciales = $iniciales ?: 'LJ';
    $usuarioLogueado = session('usuario_id') && session('usuario_rol') === 'cliente';
    $cantidadCarrito = count(session('carrito_uniformes', []))
        + count(session('carrito_chompas', []))
        + count(session('carrito_plantillas', []));
@endphp

<header class="topbar">
    @hasSection('sidebar-filtros')
        <div class="filtros-wrap" id="filtros-wrap">
            <button type="button" class="btn-filtros" onclick="toggleFiltros()" aria-label="Mostrar filtros">
                <svg viewBox="0 0 24 24"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
                <span>Filtros</span>
            </button>
            <div class="filtros-panel" id="filtros-panel">
                <div class="filtros-panel-head">Filtros</div>
                @yield('sidebar-filtros')
            </div>
        </div>

    @endif

    <a class="topbar-brand" href="{{ route('cliente.catalogo.index') }}">
        <img src="{{ asset('images/logo.png') }}" alt="Leo José">
    </a>

    @hasSection('sidebar-filtros')
        <div class="search-box">
            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="buscador" placeholder="Buscar productos..." oninput="filtrarProductos()">
        </div>
    @endif

    @php
        $categoriaNav = request()->routeIs('cliente.catalogo.*') ? request()->query('categoria', 'todos') : null;
    @endphp
    <div class="topbar-right">
        <a href="{{ route('cliente.catalogo.index') }}" class="topbar-link @if($categoriaNav === 'todos') active @endif">Toda la ropa</a>
        <a href="{{ route('cliente.catalogo.index', ['categoria' => 'uniforme']) }}" class="topbar-link @if($categoriaNav === 'uniforme') active @endif">Uniformes escolares</a>
        <a href="{{ route('cliente.catalogo.index', ['categoria' => 'chompa']) }}" class="topbar-link @if($categoriaNav === 'chompa') active @endif">Chompas</a>
        @if($usuarioLogueado)
            <a href="{{ route('cliente.disenios.index') }}" class="topbar-link @if(request()->routeIs('cliente.disenios.index')) active @endif">Mis diseños</a>
            <a href="@yield('mis-pedidos-route', route('cliente.mis-pedidos'))" class="topbar-link">Mis pedidos</a>
        @endif

        <div class="topbar-divider"></div>

        @if($usuarioLogueado)
            <div class="carrito-wrap" id="carrito-wrap">
                <button type="button" class="btn-cart" onclick="toggleCarrito()" aria-label="Carrito">
                    <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                    <span class="cart-badge" id="cart-badge" style="{{ $cantidadCarrito > 0 ? '' : 'display:none;' }}">{{ $cantidadCarrito }}</span>
                </button>
                <div id="carrito-dropdown-container">
                    @include('cliente.componentes.carrito-dropdown')
                </div>
            </div>
        @endif

        <button class="btn-theme" onclick="toggleTheme()" title="Cambiar tema">
            <svg class="icon-moon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            <svg class="icon-sun"  viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        </button>
        <div class="account-menu-wrap" id="account-menu-wrap">
            <button type="button" class="nav-avatar" onclick="toggleAccountMenu()" aria-label="Cuenta">
                {{ strtoupper($iniciales) }}
            </button>
            <div class="account-menu" id="account-menu">
                @if($usuarioLogueado)
                    <div class="account-head">
                        <div class="account-name">{{ $nombreUsuario }}</div>
                        <div class="account-role">Mi cuenta</div>
                    </div>
                    <a href="{{ route('cliente.testimonios.create') }}" class="account-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        Danos tu opinión
                    </a>
                    <a href="{{ route('logout') }}" class="account-link danger">
                        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Salir
                    </a>
                @else
                    <a href="{{ route('login.paso1') }}" class="account-link">
                        <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Iniciar sesión
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>

@once
    @push('scripts')
    <script>
        function toggleFiltros() {
            document.getElementById('filtros-wrap')?.classList.toggle('open');
        }
        function toggleCarrito() {
            document.getElementById('carrito-wrap')?.classList.toggle('open');
        }
        document.addEventListener('click', function (event) {
            const filtrosWrap = document.getElementById('filtros-wrap');
            if (filtrosWrap && !filtrosWrap.contains(event.target)) {
                filtrosWrap.classList.remove('open');
            }
            const carritoWrap = document.getElementById('carrito-wrap');
            if (carritoWrap && !carritoWrap.contains(event.target)) {
                carritoWrap.classList.remove('open');
            }
        });

        // Quitar un producto del carrito sin recargar la página (mantiene la
        // ventanita abierta y refresca su contenido + el contador del ícono).
        document.addEventListener('submit', function (event) {
            const form = event.target.closest('#carrito-dropdown-container form');
            if (!form) return;
            event.preventDefault();

            fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: new FormData(form),
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    const container = document.getElementById('carrito-dropdown-container');
                    if (container) container.innerHTML = data.html;

                    const badge = document.getElementById('cart-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? '' : 'none';
                    }
                })
                .catch(() => { form.submit(); });
        });
    </script>
    @endpush
@endonce
