@php
    $nombreUsuario = session('usuario_nombre', 'Mi cuenta');
    $usuarioLogueado = session('usuario_id') && session('usuario_rol') === 'cliente';
    $cantidadCarrito = count(session('carrito_uniformes', []))
        + count(session('carrito_chompas', []))
        + count(session('carrito_plantillas', []));
    $categoriaNav = request()->routeIs('cliente.catalogo.*') ? request()->query('categoria', 'todos') : null;
@endphp

<!-- Header -->
<header>
    <!-- Header desktop -->
    <div class="container-menu-desktop">
        <!-- Topbar -->
        <div class="top-bar">
            <div class="content-topbar flex-sb-m h-full container">
                <div class="left-top-bar">
                    🚚 Retiro en tienda o envío a todo Salcedo &nbsp;·&nbsp; Diseña tu propio uniforme en 3D
                </div>

                <div class="right-top-bar flex-w h-full">
                    <a href="https://wa.me/{{ \App\Models\InformacionLocal::actual()->whatsapp_numero ?: '593992502749' }}" target="_blank" rel="noopener" class="flex-c-m trans-04 p-lr-25">
                        Ayuda
                    </a>

                    @if($usuarioLogueado)
                        <a href="{{ route('logout') }}" class="flex-c-m trans-04 p-lr-25">
                            Salir
                        </a>
                    @else
                        <a href="{{ route('login.paso1') }}" class="flex-c-m trans-04 p-lr-25">
                            Mi cuenta
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="wrap-menu-desktop">
            <nav class="limiter-menu-desktop container">

                <!-- Logo desktop -->
                <a href="{{ route('inicio') }}" class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Leo José">
                </a>

                <!-- Menu desktop -->
                <div class="menu-desktop">
                    <ul class="main-menu">
                        <li class="{{ $categoriaNav === 'todos' ? 'active-menu' : '' }}">
                            <a href="{{ route('cliente.catalogo.index') }}">Catálogo</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('cliente.catalogo.index') }}">Toda la ropa</a></li>
                                <li><a href="{{ route('cliente.catalogo.index', ['categoria' => 'uniforme']) }}">Uniformes escolares</a></li>
                                <li><a href="{{ route('cliente.catalogo.index', ['categoria' => 'chompa']) }}">Chompas</a></li>
                            </ul>
                        </li>

                        <li class="{{ $categoriaNav === 'uniforme' ? 'active-menu' : '' }}">
                            <a href="{{ route('cliente.catalogo.index', ['categoria' => 'uniforme']) }}">Uniformes</a>
                        </li>

                        <li class="{{ $categoriaNav === 'chompa' ? 'active-menu' : '' }}">
                            <a href="{{ route('cliente.catalogo.index', ['categoria' => 'chompa']) }}">Chompas</a>
                        </li>

                        <li>
                            <a href="{{ route('cliente.mis-pedidos') }}">Mis pedidos</a>
                        </li>

                        @if($usuarioLogueado)
                            <li>
                                <a href="{{ route('cliente.disenios.index') }}">Mis diseños</a>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Icon header -->
                <div class="wrap-icon-header flex-w flex-r-m">
                    @if($usuarioLogueado)
                        <a href="{{ route('cliente.mis-pedidos') }}" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11" style="display:inline-flex;align-items:center;gap:6px;font-size:22px;" title="{{ $nombreUsuario }}">
                            <i class="zmdi zmdi-account"></i>
                            <span class="stext-107 cl2">{{ explode(' ', $nombreUsuario)[0] }}</span>
                        </a>
                        <a href="{{ route('cliente.testimonios.create') }}" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11" title="Danos tu opinión">
                            <i class="zmdi zmdi-star"></i>
                        </a>
                        <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart" data-notify="{{ $cantidadCarrito }}">
                            <i class="zmdi zmdi-shopping-cart"></i>
                        </div>
                    @else
                        <a href="{{ route('login.paso1') }}" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
                            <i class="zmdi zmdi-account"></i>
                        </a>
                    @endif
                </div>
            </nav>
        </div>
    </div>

    <!-- Header Mobile -->
    <div class="wrap-header-mobile">
        <!-- Logo moblie -->
        <div class="logo-mobile">
            <a href="{{ route('inicio') }}"><img src="{{ asset('images/logo.png') }}" alt="Leo José"></a>
        </div>

        <!-- Icon header -->
        <div class="wrap-icon-header flex-w flex-r-m m-r-15">
            @if($usuarioLogueado)
                <a href="{{ route('cliente.mis-pedidos') }}" class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10" style="display:inline-flex;align-items:center;gap:5px;font-size:20px;" title="{{ $nombreUsuario }}">
                    <i class="zmdi zmdi-account"></i>
                </a>
                <a href="{{ route('cliente.testimonios.create') }}" class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10" title="Danos tu opinión">
                    <i class="zmdi zmdi-star"></i>
                </a>
                <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart" data-notify="{{ $cantidadCarrito }}">
                    <i class="zmdi zmdi-shopping-cart"></i>
                </div>
            @else
                <a href="{{ route('login.paso1') }}" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10">
                    <i class="zmdi zmdi-account"></i>
                </a>
            @endif
        </div>

        <!-- Button show menu -->
        <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </div>
    </div>


    <!-- Menu Mobile -->
    <div class="menu-mobile">
        <ul class="topbar-mobile">
            <li>
                <div class="left-top-bar">
                    🚚 Envíos a todo Salcedo
                </div>
            </li>

            <li>
                <div class="right-top-bar flex-w h-full">
                    @if($usuarioLogueado)
                        <a href="{{ route('logout') }}" class="flex-c-m p-lr-10 trans-04">Salir</a>
                    @else
                        <a href="{{ route('login.paso1') }}" class="flex-c-m p-lr-10 trans-04">Mi cuenta</a>
                    @endif
                </div>
            </li>
        </ul>

        <ul class="main-menu-m">
            <li>
                <a href="{{ route('cliente.catalogo.index') }}">Catálogo</a>
                <ul class="sub-menu-m">
                    <li><a href="{{ route('cliente.catalogo.index') }}">Toda la ropa</a></li>
                    <li><a href="{{ route('cliente.catalogo.index', ['categoria' => 'uniforme']) }}">Uniformes escolares</a></li>
                    <li><a href="{{ route('cliente.catalogo.index', ['categoria' => 'chompa']) }}">Chompas</a></li>
                </ul>
                <span class="arrow-main-menu-m">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                </span>
            </li>

            <li>
                <a href="{{ route('cliente.mis-pedidos') }}">Mis pedidos</a>
            </li>

            @if($usuarioLogueado)
                <li><a href="{{ route('cliente.disenios.index') }}">Mis diseños</a></li>
            @endif

            <li>
                <a href="{{ route('cliente.testimonios.create') }}">Danos tu opinión</a>
            </li>
        </ul>
    </div>

</header>

@if($usuarioLogueado)
    <!-- Cart -->
    <div class="wrap-header-cart js-panel-cart">
        <div class="s-full js-hide-cart"></div>

        <div class="header-cart flex-col-l p-l-65 p-r-25">
            <div class="header-cart-title flex-w flex-sb-m p-b-8">
                <span class="mtext-103 cl2">
                    Mi carrito
                </span>

                <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                    <i class="zmdi zmdi-close"></i>
                </div>
            </div>

            <div class="header-cart-content flex-w js-pscroll" id="carrito-dropdown-container">
                @include('cliente.componentes.carrito-dropdown')
            </div>
        </div>
    </div>
@endif

@once
    @push('scripts')
    <script>
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

                    document.querySelectorAll('.js-show-cart').forEach(function (el) {
                        el.setAttribute('data-notify', data.count);
                    });
                })
                .catch(() => { form.submit(); });
        });
    </script>
    @endpush
@endonce
