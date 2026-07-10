<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>@yield('titulo', 'Leo José | Catálogo Deportivo')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    /* ========== VARIABLES MODO CLARO ========== */
    :root {
      --bg:           #F1F5F9;
      --bg-2:         #FFFFFF;
      --bg-3:         #F8FAFC;
      --border:       #E2E8F0;
      --border-2:     #CBD5E1;
      --text-1:       #0F172A;
      --text-2:       #475569;
      --text-3:       #94A3B8;
      --blue:         #240677;
      --blue-h:       #1D4ED8;
      --blue-soft:    #EFF6FF;
      --blue-border:  #BFDBFE;
      --blue-light:   #60A5FA;
      --sidebar-bg:   #0F172A;
      --sidebar-txt:  rgba(255,255,255,0.55);
      --sidebar-act:  #FFFFFF;
      --sidebar-hover:rgba(255,255,255,0.06);
      --sidebar-actbg:rgba(255,255,255,0.1);
      --shadow-sm:    0 1px 3px rgba(0,0,0,0.06);
      --shadow-md:    0 4px 16px rgba(0,0,0,0.08);
      --shadow-lg:    0 12px 40px rgba(0,0,0,0.1);
      --radius:       14px;
      --radius-sm:    10px;
      --font-d:       'Outfit', sans-serif;
      --font-b:       'DM Sans', sans-serif;
      --nav-h:        64px;
      --sidebar-w:    260px;
      --tr:           0.22s cubic-bezier(.4,0,.2,1);
    }

    /* ========== MODO OSCURO ========== */
    [data-theme="dark"] {
      --bg:           #171e2c;
      --bg-2:         #000000;
      --bg-3:         #1A2235;
      --border:       #1E2D45;
      --border-2:     #2A3F5F;
      --text-1:       #F1F5F9;
      --text-2:       #ebebeb;
      --text-3:       #677b97;
      --sidebar-bg:   #080E1A;
      --sidebar-actbg:rgba(37,99,235,0.2);
      --blue-soft:    rgba(37,99,235,0.12);
      --blue-border:  rgba(37,99,235,0.3);
      --shadow-sm:    0 1px 3px rgba(0,0,0,0.3);
      --shadow-md:    0 4px 16px rgba(0,0,0,0.4);
      --shadow-lg:    0 12px 40px rgba(0,0,0,0.5);
    }

    /* Fondo con textura sutil profesional */
    body {
      background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40" opacity="0.08"><path d="M0 0h40v40H0z" fill="none"/><path d="M20 0v40M0 20h40" stroke="%23475569" stroke-width="0.5"/></svg>');
      background-color: var(--bg);
      background-attachment: fixed;
    }

    html, body { height: 100%; scroll-behavior: smooth; }
    body {
      font-family: var(--font-b);
      color: var(--text-1);
      transition: background var(--tr), color var(--tr);
    }

    /* ========== TOPBAR ========== */
    .topbar {
      position: fixed; top: 0; left: 0; right: 0; z-index: 200;
      height: var(--nav-h);
      background: var(--bg-2);
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center;
      padding: 0 24px;
      gap: 20px;
      box-shadow: var(--shadow-sm);
    }
    .topbar-brand {
      display: flex; align-items: center; gap: 10px;
      text-decoration: none; flex-shrink: 0;
    }
    .brand-icon {
      width: 36px; height: 36px; border-radius: 10px;
      background: var(--blue);
      display: flex; align-items: center; justify-content: center;
    }
    .brand-icon i { color: white; font-size: 18px; }
    .brand-name {
      font-family: var(--font-d); font-weight: 800; font-size: 1.2rem;
      color: var(--text-1); letter-spacing: -0.02em;
    }
    .search-box {
      flex: 1; max-width: 400px;
      display: flex; align-items: center;
      background: var(--bg-3); border: 1px solid var(--border);
      border-radius: 40px; padding: 6px 16px;
      transition: all var(--tr);
    }
    .search-box i { color: var(--text-3); font-size: 14px; margin-right: 8px; }
    .search-box input {
      background: transparent; border: none; outline: none;
      width: 100%; font-size: 0.85rem; color: var(--text-1);
    }
    .search-box input::placeholder { color: var(--text-3); }
    .topbar-right {
      display: flex; align-items: center; gap: 12px;
    }
    /* Enlaces de texto simples (sin iconos ni estilo de botón) */
    .topbar-link {
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--text-2);
      text-decoration: none;
      transition: color 0.2s;
      margin-left: 4px;
    }
    .topbar-link:hover {
      color: var(--blue);
    }
    .topbar-link.active {
      color: var(--blue);
      font-weight: 700;
    }
    .topbar-action {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--blue); color: #fff;
      padding: 8px 16px; border-radius: 40px;
      font-size: 0.82rem; font-weight: 600;
      text-decoration: none; transition: opacity var(--tr);
    }
    .topbar-action:hover { opacity: 0.9; }
    /* Separador visual */
    .topbar-divider {
      width: 1px;
      height: 20px;
      background: var(--border);
      margin: 0 4px;
    }
    .btn-theme {
      width: 36px; height: 36px; border-radius: 40px;
      background: var(--bg-3); border: 1px solid var(--border);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--text-2);
    }
    .btn-theme i { font-size: 16px; }
    .btn-theme:hover { background: var(--blue-soft); color: var(--blue); }
    .icon-moon { display: inline; }
    .icon-sun  { display: none;  }
    [data-theme="dark"] .icon-moon { display: none;  }
    [data-theme="dark"] .icon-sun  { display: inline; }

    .nav-avatar {
      width: 36px; height: 36px; border-radius: 50%;
      background: linear-gradient(135deg, var(--blue), var(--blue-light));
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; color: white; cursor: pointer;
      border: none;
      font-family: var(--font-d);
    }
    .account-menu-wrap {
      position: relative;
    }
    .account-menu {
      position: absolute;
      top: calc(100% + 10px);
      right: 0;
      width: 220px;
      background: var(--bg-2);
      border: 1px solid var(--border);
      border-radius: 12px;
      box-shadow: var(--shadow-lg);
      padding: 8px;
      display: none;
      z-index: 260;
    }
    .account-menu-wrap:hover .account-menu,
    .account-menu-wrap.open .account-menu {
      display: block;
    }
    .account-menu::before {
      content: '';
      position: absolute;
      top: -6px;
      right: 14px;
      width: 12px;
      height: 12px;
      background: var(--bg-2);
      border-left: 1px solid var(--border);
      border-top: 1px solid var(--border);
      transform: rotate(45deg);
    }
    .account-head {
      padding: 8px 10px 10px;
      border-bottom: 1px solid var(--border);
      margin-bottom: 6px;
    }
    .account-name {
      font-size: 0.86rem;
      font-weight: 700;
      color: var(--text-1);
      line-height: 1.2;
    }
    .account-role {
      font-size: 0.75rem;
      color: var(--text-3);
      margin-top: 2px;
    }
    .account-link {
      display: flex;
      align-items: center;
      gap: 10px;
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      color: var(--text-2);
      text-decoration: none;
      font-size: 0.84rem;
      font-weight: 600;
      transition: background var(--tr), color var(--tr);
    }
    .account-link:hover {
      background: var(--bg-3);
      color: var(--blue);
    }
    .account-link.danger:hover {
      color: #DC2626;
      background: #FEF2F2;
    }

    /* ========== SIDEBAR (FILTROS) ========== */
    .sidebar {
      position: fixed; top: var(--nav-h); left: 0; bottom: 0;
      width: var(--sidebar-w);
      background: var(--bg-2);
      border-right: 1px solid var(--border);
      display: flex; flex-direction: column;
      padding: 20px 16px;
      z-index: 100;
      overflow-y: auto;
      transition: background var(--tr), border-color var(--tr);
    }
    .sidebar h5 {
      font-size: 1rem; font-weight: 700; margin-bottom: 20px;
      color: var(--text-1); letter-spacing: -0.2px;
    }
    .filter-group {
      margin-bottom: 28px;
    }
    .filter-group h6 {
      font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
      color: var(--blue); letter-spacing: 0.5px;
      margin-bottom: 12px;
      display: flex; align-items: center; gap: 6px;
    }
    .filter-group ul {
      list-style: none;
    }
    .filter-group li {
      padding: 6px 0;
      font-size: 0.85rem;
      color: var(--text-2);
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: 0.2s;
    }
    .filter-group li i {
      width: 18px; font-size: 12px; color: var(--text-3);
    }
    .filter-group li:hover {
      color: var(--blue);
      transform: translateX(3px);
    }
    .filter-group li.filtro-activo {
      color: var(--blue);
      font-weight: 600;
    }
    .filter-group li.filtro-activo i { color: var(--blue); }
    .color-filter-dot {
      width: 14px;
      height: 14px;
      border-radius: 50%;
      border: 1px solid var(--border-2);
      display: inline-block;
      flex-shrink: 0;
    }

    /* ========== MAIN CONTENT ========== */
    .main-wrap {
      margin-top: var(--nav-h);
      margin-left: var(--sidebar-w);
      min-height: calc(100vh - var(--nav-h));
      display: flex;
      flex-direction: column;
    }
    .main-content {
      flex: 1;
      padding: 28px 32px;
    }

    /* Tarjeta destacada tipo "Crear diseño" */
    .create-card {
      background: var(--bg-2);
      border: 1px solid var(--border);
      padding: 24px 28px;
      margin-bottom: 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 20px;
      transition: all 0.2s;
    }
    .create-card:hover {
      border-color: var(--blue-border);
      box-shadow: var(--shadow-md);
    }
    .create-info h3 {
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--text-1);
      margin-bottom: 4px;
    }
    .create-info p {
      font-size: 0.85rem;
      color: var(--text-2);
    }
    .create-btn {
      background: var(--blue);
      color: white;
      border: none;
      padding: 10px 24px;
      font-weight: 600;
      font-size: 0.85rem;
      border-radius: 40px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: opacity 0.2s;
    }
    .create-btn:hover {
      opacity: 0.9;
    }

    /* Encabezado catálogo */
    .catalog-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 24px;
      flex-wrap: wrap;
      gap: 12px;
    }
    .catalog-header h2 {
      font-size: 1.6rem; font-weight: 700; color: var(--text-1);
      letter-spacing: -0.3px;
    }
    .product-count {
      background: var(--blue-soft);
      padding: 5px 14px; border-radius: 40px;
      font-weight: 600; font-size: 0.8rem; color: var(--blue);
    }

    /* Grid de productos */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 28px;
    }
    .product-card {
      background: var(--bg-2);
      overflow: hidden;
      border: 1px solid var(--border);
      transition: all 0.25s ease;
      display: flex;
      flex-direction: column;
      height: 100%;
      text-decoration: none;
      color: inherit;
    }
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-md);
      border-color: var(--blue-border);
    }
    .product-image {
      position: relative;
      background: var(--bg-3);
      aspect-ratio: 1 / 1;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      transition: transform 0.3s;
      background: var(--bg-3);
    }
    .product-card:hover .product-image img {
      transform: scale(1.02);
    }
    .product-image .no-img {
      font-size: 48px;
      color: var(--text-3);
    }
    .badge-new {
      position: absolute;
      top: 12px; right: 12px;
      background: var(--blue);
      color: white;
      font-size: 0.7rem;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: 40px;
      z-index: 2;
      text-transform: capitalize;
    }
    .product-body {
      padding: 16px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }
    .product-name {
      font-weight: 700;
      font-size: 0.95rem;
      color: var(--text-1);
      margin-bottom: 8px;
      line-height: 1.3;
    }
    .product-price {
      font-size: 1.2rem;
      font-weight: 800;
      color: var(--blue);
      margin-top: auto;
      margin-bottom: 10px;
    }
    .product-colors {
      display: flex;
      gap: 8px;
      margin-top: 6px;
      margin-bottom: 12px;
    }
    .color-dot {
      width: 18px; height: 18px;
      border-radius: 50%;
      border: 1px solid var(--border-2);
    }
    .product-sizes {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
      margin-bottom: 12px;
    }
    .product-sizes span {
      background: var(--bg-3);
      border: 1px solid var(--border);
      color: var(--text-2);
      padding: 2px 8px;
      border-radius: 5px;
      font-size: 0.72rem;
      font-weight: 600;
    }
    .buy-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 10px;
      border-radius: 6px;
      background: var(--blue);
      color: white;
      font-size: 0.82rem;
      font-weight: 600;
      transition: background var(--tr);
      margin-top: auto;
      text-decoration: none;
      border: none;
      cursor: pointer;
    }

    /* Footer */
    .main-footer {
      margin-top: 50px;
      padding: 30px 32px 20px;
      border-top: 1px solid var(--border);
      background: var(--bg-2);
      transition: background var(--tr);
    }
    .footer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 30px;
      margin-bottom: 30px;
    }
    .footer-col h4 {
      font-size: 0.85rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.5px; margin-bottom: 16px; color: var(--text-1);
    }
    .footer-col ul {
      list-style: none;
    }
    .footer-col li {
      margin-bottom: 8px;
    }
    .footer-col a {
      text-decoration: none; font-size: 0.8rem; color: var(--text-2);
      transition: color 0.2s;
    }
    .footer-col a:hover { color: var(--blue); }
    .footer-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
      padding-top: 20px;
      border-top: 1px solid var(--border);
      font-size: 0.7rem;
      color: var(--text-3);
    }
    .social-icons i {
      font-size: 18px; margin-left: 12px; color: var(--text-2);
      cursor: pointer;
      transition: color 0.2s;
    }
    .social-icons i:hover { color: var(--blue); }

    @media (max-width: 900px) {
      .sidebar { display: none; }
      .main-wrap { margin-left: 0; }
      .products-grid { gap: 16px; }
      .main-content { padding: 20px 16px; }
      .search-box { max-width: 200px; }
      .topbar-link { font-size: 0.75rem; }
    }
    @media (max-width: 550px) {
      .topbar { padding: 0 12px; gap: 10px; }
      .brand-name { font-size: 1rem; }
      .search-box { display: none; }
      .topbar-link { display: none; }
    }

    @stack('estilos')
  </style>
</head>
<body>
@php
  $usuarioLogueado = session('usuario_id') && session('usuario_rol') === 'cliente';
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
      <img src="{{ asset('images/logo.png') }}" width="110" height="99" alt="" >
    </a>
  </div>
  <div class="search-box">
    <i class="fas fa-search"></i>
    <input type="text" id="buscador" placeholder="Buscar productos..." oninput="filtrarProductos()">
  </div>
  <div class="topbar-right">
    <a href="{{ route('cliente.uniformes.index') }}" class="topbar-link @if(request()->routeIs('cliente.uniformes.*')) active @endif">Uniformes escolares</a>
    <a href="{{ route('cliente.chompas.index') }}" class="topbar-link @if(request()->routeIs('cliente.chompas.*')) active @endif">Chompas</a>
    @if($usuarioLogueado)
      <a href="{{ route('cliente.disenios.index') }}" class="topbar-link @if(request()->routeIs('cliente.disenios.index')) active @endif">Mis diseños</a>
      <a href="@yield('mis-pedidos-route', route('cliente.pedidos.index'))" class="topbar-link">Mis pedidos</a>
      <a href="{{ request()->routeIs('cliente.chompas.*') ? route('cliente.chompas.carrito') : route('cliente.carrito.index') }}" class="topbar-link">Ver mi carrito</a>
    @endif
    <div class="topbar-divider"></div>
    <button class="btn-theme" onclick="toggleTheme()">
      <i class="fas fa-moon icon-moon"></i>
      <i class="fas fa-sun icon-sun"></i>
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
          <a href="{{ route('logout') }}" class="account-link danger">
            <i class="fas fa-sign-out-alt"></i> Salir
          </a>
        @else
          <a href="{{ route('login.paso1') }}" class="account-link">
            <i class="fas fa-right-to-bracket"></i> Iniciar sesión
          </a>
        @endif
      </div>
    </div>
  </div>
</header>

<aside class="sidebar">
  <h5><i class="fas fa-sliders-h" style="margin-right: 6px;"></i> Filtros</h5>
  @yield('sidebar-filtros')
</aside>

<div class="main-wrap">
  <main class="main-content">
    @yield('contenido')
  </main>

  <footer class="main-footer">
    <div class="footer-grid">
      <div class="footer-col">
        <h4>COMPANY</h4>
        <ul>
          <li><a href="#">Sobre Leo José</a></li>
          <li><a href="#">Carreras</a></li>
          <li><a href="#">Prensa</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>SOCIAL RESPONSIBILITY</h4>
        <ul>
          <li><a href="#">Sostenibilidad</a></li>
          <li><a href="#">Comunidad</a></li>
          <li><a href="#">Ética</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>HELP CENTER</h4>
        <ul>
          <li><a href="#">FAQ</a></li>
          <li><a href="#">Cómo comprar</a></li>
          <li><a href="#">Términos y condiciones</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>CONTACTO</h4>
        <ul>
          <li><i class="fas fa-phone"></i> (800) 225-0550</li>
          <li><i class="fas fa-envelope"></i> hola@leojose.com</li>
          <li>Lun-Vie 8:30AM - 5:15PM</li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 Leo José Apparel. Todos los derechos reservados.</span>
      <div class="social-icons">
        <i class="fab fa-instagram"></i>
        <i class="fab fa-facebook"></i>
        <i class="fab fa-x-twitter"></i>
      </div>
    </div>
  </footer>
</div>

<script>
  // Tema
  const html = document.documentElement;
  const savedTheme = localStorage.getItem('lj-theme');
  if (savedTheme) html.setAttribute('data-theme', savedTheme);
  else if (window.matchMedia('(prefers-color-scheme: dark)').matches)
    html.setAttribute('data-theme', 'dark');

  function toggleTheme() {
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('lj-theme', next);
  }

  function toggleAccountMenu() {
    document.getElementById('account-menu-wrap').classList.toggle('open');
  }

  document.addEventListener('click', function (event) {
    const wrap = document.getElementById('account-menu-wrap');
    if (wrap && !wrap.contains(event.target)) {
      wrap.classList.remove('open');
    }
  });
</script>

@stack('scripts')

</body>
</html>
