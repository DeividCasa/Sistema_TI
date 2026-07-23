<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <script>
    // Aplicar el tema guardado antes de pintar, para evitar el flash claro/oscuro al navegar.
    (function () {
      const saved = localStorage.getItem('lj-theme');
      if (saved) document.documentElement.setAttribute('data-theme', saved);
      else if (window.matchMedia('(prefers-color-scheme: dark)').matches)
        document.documentElement.setAttribute('data-theme', 'dark');
    })();
  </script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>@yield('titulo', 'Leo José | Catálogo Deportivo')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    /* ========== VARIABLES MODO CLARO ========== */
    :root {
      --bg:           #FAF8F5;
      --bg-2:         #FFFFFF;
      --bg-3:         #F3F0EA;
      --border:       #E7E2D9;
      --border-2:     #D2CBBC;
      --text-1:       #1C1A17;
      --text-2:       #5B564D;
      --text-3:       #948E80;
      --blue:         #0E6B4F;
      --blue-h:       #0A5540;
      --blue-soft:    #E4F1EC;
      --blue-border:  #BFE1D2;
      --blue-light:   #2F9271;
      --blue-shadow:  rgba(14,107,79,0.22);
      --accent:       #1D4ED8;
      --accent-soft:  #DBEAFE;
      --accent-border:#93C5FD;
      --band-green:       #1F4B3F;
      --band-green-strong:#FFFFFF;
      --band-cream:       #4A0F35;
      --band-cream-strong:#FFFFFF;
      --promo-bg:         #ff0527;
      --band-blue:        #1E2A4A;
      --band-blue-strong: #FFFFFF;
      --ink:          #1B1721;
      --sidebar-bg:   #1B1721;
      --sidebar-txt:  rgba(255,255,255,0.62);
      --sidebar-act:  #FFFFFF;
      --sidebar-hover:rgba(255,255,255,0.08);
      --sidebar-actbg:rgba(29,78,216,0.28);
      --shadow-sm:    0 1px 3px rgba(28,26,23,0.08);
      --shadow-md:    0 4px 16px rgba(28,26,23,0.1);
      --shadow-lg:    0 12px 40px rgba(28,26,23,0.14);
      --radius:       14px;
      --radius-sm:    10px;
      --font-d:       'Fraunces', serif;
      --font-b:       'Inter', sans-serif;
      --nav-h:        64px;
      --promo-h:      34px;
      --tr:           0.22s cubic-bezier(.4,0,.2,1);
    }

    /* ========== MODO OSCURO ========== */
    [data-theme="dark"] {
      --bg:           #15130F;
      --bg-2:         #1E1B23;
      --bg-3:         #262330;
      --border:       #363047;
      --border-2:     #453E5C;
      --text-1:       #F3F0FF;
      --text-2:       #B6AFC9;
      --text-3:       #837B99;
      --blue:         #34D399;
      --blue-h:       #6EE7B7;
      --blue-soft:    rgba(52,211,153,0.14);
      --blue-border:  rgba(52,211,153,0.32);
      --blue-shadow:  rgba(52,211,153,0.35);
      --sidebar-bg:   #0E0B14;
      --sidebar-actbg:rgba(96,165,250,0.22);
      --accent:       #60A5FA;
      --accent-soft:  rgba(96,165,250,0.16);
      --accent-border:rgba(96,165,250,0.35);
      --band-green:       #123028;
      --band-green-strong:#6EE7A8;
      --band-cream:       #260A1C;
      --band-cream-strong:#F0A8D8;
      --promo-bg:         #101B33;
      --band-blue:        #10192F;
      --band-blue-strong: #93C5FD;
      --ink:          #0E0B14;
      --shadow-sm:    0 1px 3px rgba(0,0,0,0.35);
      --shadow-md:    0 4px 18px rgba(0,0,0,0.45);
      --shadow-lg:    0 14px 44px rgba(0,0,0,0.55);
    }

    /* Fondo con imagen de marca */
    body {
      background-image:
        linear-gradient(rgba(250,248,245,0.92), rgba(250,248,245,0.92)),
        url('{{ asset('images/fondo.png') }}');
      background-color: var(--bg);
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      background-repeat: no-repeat;
    }
    [data-theme="dark"] body {
      background-image:
        linear-gradient(rgba(21,19,15,0.94), rgba(21,19,15,0.94)),
        url('{{ asset('images/fondo.png') }}');
    }

    html, body { height: 100%; scroll-behavior: smooth; overflow-x: hidden; }
    body {
      font-family: var(--font-b);
      color: var(--text-1);
      transition: background var(--tr), color var(--tr);
    }

    svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    /* ========== FRANJA DE BIENVENIDA ========== */
    .promo-strip {
      position: fixed; top: 0; left: 0; right: 0; z-index: 201;
      height: var(--promo-h);
      background: var(--promo-bg);
      color: rgba(255,255,255,0.92);
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-b);
      font-size: 0.72rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase;
      padding: 0 16px; text-align: center;
    }

    /* ========== TOPBAR (banda oscura fija, no cambia con el tema claro/oscuro) ========== */
    .topbar {
      position: fixed; top: var(--promo-h); left: 0; right: 0; z-index: 200;
      height: var(--nav-h);
      background: var(--ink);
      display: flex; align-items: center;
      padding: 0 24px;
      gap: 16px;
      box-shadow: var(--shadow-sm);
    }
    .topbar-brand {
      display: flex; align-items: center;
      text-decoration: none; flex-shrink: 0;
    }
    .topbar-brand img { display: block; height: 48px; width: auto; }
    .search-box {
      flex: 1; max-width: 360px;
      display: flex; align-items: center;
      background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
      border-radius: 40px; padding: 6px 16px;
      transition: all var(--tr);
    }
    .search-box svg { width: 14px; height: 14px; color: rgba(255,255,255,0.5); margin-right: 8px; flex-shrink: 0; }
    .search-box input {
      background: transparent; border: none; outline: none;
      width: 100%; font-size: 0.85rem; color: #fff;
    }
    .search-box input::placeholder { color: rgba(255,255,255,0.45); }
    .topbar-right {
      display: flex; align-items: center; gap: 12px;
      margin-left: auto;
    }
    .topbar-link {
      font-size: 0.85rem;
      font-weight: 500;
      color: rgba(255,255,255,0.68);
      text-decoration: none;
      transition: color 0.2s;
      white-space: nowrap;
    }
    .topbar-link:hover { color: #fff; }
    .topbar-link.active { color: var(--accent); font-weight: 700; }
    .topbar-divider { width: 1px; height: 20px; background: rgba(255,255,255,0.16); margin: 0 2px; flex-shrink: 0; }

    /* Botón de filtros (junto al logo) + panel desplegable */
    .filtros-wrap { position: relative; flex-shrink: 0; }
    .btn-filtros {
      display: inline-flex; align-items: center; gap: 8px;
      height: 38px; padding: 0 16px; border-radius: 40px;
      background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
      color: rgba(255,255,255,0.75); font-family: var(--font-b); font-size: 0.83rem; font-weight: 600;
      cursor: pointer; transition: all var(--tr);
    }
    .btn-filtros svg { width: 15px; height: 15px; }
    .btn-filtros:hover, .filtros-wrap.open .btn-filtros {
      background: rgba(255,255,255,0.16); border-color: rgba(255,255,255,0.28); color: #fff;
    }
    .filtros-panel {
      position: absolute; top: calc(100% + 10px); left: 0;
      width: 260px; max-height: 70vh; overflow-y: auto;
      background: var(--bg-2); border: 1px solid var(--border);
      border-radius: var(--radius); box-shadow: var(--shadow-lg);
      padding: 18px; display: none; z-index: 260;
    }
    .filtros-wrap.open .filtros-panel { display: block; }
    .filtros-panel-head {
      font-family: var(--font-d); font-size: 1rem; font-weight: 700;
      color: var(--text-1); margin-bottom: 16px;
    }

    .btn-theme {
      width: 38px; height: 38px; border-radius: 40px;
      background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: rgba(255,255,255,0.75); flex-shrink: 0;
    }
    .btn-theme svg { width: 16px; height: 16px; }
    .icon-moon { display: inline; }
    .icon-sun  { display: none;  }
    [data-theme="dark"] .icon-moon { display: none;  }
    [data-theme="dark"] .icon-sun  { display: inline; }
    .btn-theme:hover { background: rgba(255,255,255,0.16); color: #fff; }

    /* Carrito: ícono + burbuja de cantidad */
    .btn-cart {
      position: relative;
      width: 38px; height: 38px; border-radius: 40px; padding: 0;
      background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,0.75); text-decoration: none; flex-shrink: 0;
      cursor: pointer; font-family: inherit;
      transition: all var(--tr);
    }
    .btn-cart svg { width: 17px; height: 17px; }
    .btn-cart:hover { background: rgba(255,255,255,0.16); border-color: rgba(255,255,255,0.28); color: #fff; }
    .cart-badge {
      position: absolute; top: -5px; right: -5px;
      min-width: 17px; height: 17px; padding: 0 4px;
      border-radius: 20px; background: var(--accent); color: #fff;
      font-size: 0.62rem; font-weight: 700; font-family: var(--font-d);
      display: flex; align-items: center; justify-content: center;
      border: 2px solid var(--ink);
    }

    .nav-avatar {
      width: 38px; height: 38px; border-radius: 50%;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; color: white; cursor: pointer;
      border: none;
      font-family: var(--font-d);
      flex-shrink: 0;
    }
    /* Carrito: ventanita desplegable con ambos carritos (uniformes + chompas) */
    .carrito-wrap { position: relative; }
    .carrito-dropdown {
      position: absolute; top: calc(100% + 10px); right: 0;
      width: 340px; max-height: 75vh; overflow-y: auto;
      background: var(--bg-2); border: 1px solid var(--border);
      border-radius: var(--radius); box-shadow: var(--shadow-lg);
      padding: 16px; display: none; z-index: 260;
    }
    .carrito-wrap.open .carrito-dropdown { display: block; }
    .carrito-dropdown-titulo {
      font-family: var(--font-d); font-size: 1rem; font-weight: 800;
      color: var(--text-1); margin-bottom: 12px;
    }
    .carrito-seccion { margin-bottom: 18px; }
    .carrito-seccion:last-child { margin-bottom: 0; }
    .carrito-seccion-titulo {
      font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.05em; color: var(--text-3); margin-bottom: 10px;
    }
    .carrito-item {
      display: flex; align-items: center; gap: 10px;
      padding: 8px 0; border-bottom: 1px solid var(--border);
    }
    .carrito-item:last-of-type { border-bottom: none; }
    .carrito-item img {
      width: 42px; height: 42px; object-fit: cover; border-radius: 8px;
      border: 1px solid var(--border); flex-shrink: 0; background: var(--bg-3);
    }
    .carrito-item-info { flex: 1; min-width: 0; }
    .carrito-item-nombre { font-size: 0.82rem; font-weight: 700; color: var(--text-1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .carrito-item-detalle { font-size: 0.72rem; color: var(--text-3); }
    .carrito-item-precio { font-size: 0.8rem; font-weight: 700; color: var(--text-1); white-space: nowrap; }
    .carrito-item-quitar {
      background: none; border: none; color: var(--text-3); cursor: pointer;
      width: 22px; height: 22px; border-radius: 50%; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
    }
    .carrito-item-quitar:hover { background: #FEF2F2; color: #DC2626; }
    .carrito-item-quitar svg { width: 13px; height: 13px; }
    .carrito-seccion-footer {
      display: flex; justify-content: space-between; align-items: center;
      margin-top: 10px; gap: 10px;
    }
    .carrito-seccion-total { font-size: 0.82rem; color: var(--text-2); }
    .carrito-seccion-total strong { color: var(--text-1); }
    .carrito-btn-pagar {
      display: inline-flex; align-items: center; justify-content: center;
      padding: 8px 16px; border-radius: var(--radius-sm);
      background: var(--blue); color: white; border: none;
      font-family: var(--font-b); font-size: 0.78rem; font-weight: 600;
      cursor: pointer; text-decoration: none; white-space: nowrap;
      transition: background var(--tr);
    }
    .carrito-btn-pagar:hover { background: var(--blue-h); }
    .carrito-vacio { text-align: center; padding: 20px 0; color: var(--text-3); font-size: 0.85rem; }
    .carrito-vacio svg { width: 32px; height: 32px; margin: 0 auto 10px; display: block; stroke: var(--border-2); }

    .account-menu-wrap { position: relative; }
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
    .account-menu-wrap.open .account-menu {
      display: block;
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
    .account-link svg { width: 15px; height: 15px; }
    .account-link:hover {
      background: var(--bg-3);
      color: var(--blue);
    }
    .account-link.danger:hover {
      color: #DC2626;
      background: #FEF2F2;
    }

    /* ========== FILTROS (contenido dentro del panel) ========== */
    .filter-group { margin-bottom: 22px; }
    .filter-group:last-child { margin-bottom: 0; }
    .filter-group h6 {
      font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
      color: var(--blue); letter-spacing: 0.5px;
      margin-bottom: 12px;
      display: flex; align-items: center; gap: 6px;
    }
    .filter-group ul { list-style: none; }
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
    .filter-group li i { width: 18px; font-size: 12px; color: var(--text-3); }
    .filter-group li:hover { color: var(--blue); transform: translateX(3px); }
    .filter-group li.filtro-activo { color: var(--accent); font-weight: 600; }
    .filter-group li.filtro-activo i { color: var(--accent); }
    .color-filter-dot {
      width: 14px; height: 14px; border-radius: 50%;
      border: 1px solid var(--border-2);
      display: inline-block; flex-shrink: 0;
    }

    /* Filtro de precio: slider de rango con dos manijas */
    .price-range-label {
      display: flex; justify-content: space-between; align-items: center;
      font-size: 0.85rem; font-weight: 700; color: var(--text-1);
      margin-bottom: 14px;
    }
    .price-slider {
      position: relative; height: 20px; margin: 0 2px 4px;
    }
    .price-slider .price-track {
      position: absolute; top: 8px; left: 0; right: 0; height: 4px;
      background: var(--border); border-radius: 4px;
    }
    .price-slider .price-track-fill {
      position: absolute; top: 8px; height: 4px;
      background: var(--blue); border-radius: 4px;
    }
    .price-slider input[type="range"] {
      position: absolute; top: 0; left: 0; width: 100%; height: 20px;
      margin: 0; background: transparent; pointer-events: none;
      -webkit-appearance: none; appearance: none;
    }
    .price-slider input[type="range"]::-webkit-slider-runnable-track { background: transparent; }
    .price-slider input[type="range"]::-moz-range-track { background: transparent; }
    .price-slider input[type="range"]::-webkit-slider-thumb {
      -webkit-appearance: none; pointer-events: auto;
      width: 16px; height: 16px; border-radius: 50%;
      background: var(--blue); border: 2px solid var(--bg-2);
      box-shadow: 0 1px 4px rgba(0,0,0,0.3);
      cursor: pointer; margin-top: 2px;
    }
    .price-slider input[type="range"]::-moz-range-thumb {
      pointer-events: auto; width: 14px; height: 14px; border-radius: 50%;
      background: var(--blue); border: 2px solid var(--bg-2); cursor: pointer;
    }

    /* ========== MAIN CONTENT ========== */
    .main-wrap {
      margin-top: calc(var(--nav-h) + var(--promo-h));
      min-height: calc(100vh - var(--nav-h) - var(--promo-h));
      display: flex;
      flex-direction: column;
    }
    .main-content {
      flex: 1;
      padding: 28px 32px;
      max-width: 1400px;
      width: 100%;
      margin: 0 auto;
    }

    /* Bandas de sección a todo el ancho del navegador, aunque el contenido
       vive dentro de un .main-content centrado y limitado a 1400px. */
    .full-bleed {
      width: 100vw;
      position: relative;
      left: 50%;
      right: 50%;
      margin-left: -50vw;
      margin-right: -50vw;
    }
    .full-bleed-inner {
      max-width: 1400px;
      margin: 0 auto;
      padding: 40px 32px;
    }

    /* Banner principal del inicio: panel de texto + imagen real, dos columnas */
    .hero-split {
      display: grid;
      grid-template-columns: 1.1fr 1fr;
      min-height: 380px;
      border-radius: var(--radius);
      overflow: hidden;
      margin-bottom: 36px;
      box-shadow: var(--shadow-lg);
    }
    .hero-text {
      background: var(--ink);
      display: flex;
      align-items: center;
      padding: 48px;
    }
    .hero-text h1 {
      font-family: var(--font-d); font-size: 2.5rem; font-weight: 600;
      color: #fff; letter-spacing: -0.01em; line-height: 1.15; margin-bottom: 16px;
    }
    .hero-text p {
      color: rgba(255,255,255,0.72); font-size: 1rem; line-height: 1.6;
      margin-bottom: 28px; max-width: 420px;
    }
    .hero-image { position: relative; min-height: 260px; }
    .hero-image img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .hero-image .hero-image-fallback {
      width: 100%; height: 100%;
      background: linear-gradient(135deg, var(--blue), var(--band-blue));
    }
    @media (max-width: 760px) {
      .hero-split { grid-template-columns: 1fr; }
      .hero-image { min-height: 200px; order: -1; }
      .hero-text { padding: 32px 24px; }
      .hero-text h1 { font-size: 1.9rem; }
    }

    /* Sección "Información del local": columna de intro + tarjetas con icono */
    .local-info-wrap {
      display: grid;
      grid-template-columns: 0.85fr 1.4fr;
      gap: 40px;
      align-items: center;
    }
    .local-info-intro h2 {
      font-family: var(--font-d); font-size: 1.7rem; font-weight: 600;
      color: #fff; letter-spacing: -0.01em; margin-bottom: 10px;
    }
    .local-info-intro p {
      color: rgba(255,255,255,0.68); font-size: 0.9rem; line-height: 1.6; max-width: 320px;
    }
    .local-info-items {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
      gap: 16px;
    }
    .local-info-card {
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.14);
      border-radius: var(--radius);
      padding: 18px;
      transition: background var(--tr);
    }
    .local-info-card:hover { background: rgba(255,255,255,0.11); }
    .local-info-icon {
      width: 38px; height: 38px; border-radius: 10px;
      background: rgba(255,255,255,0.14);
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 14px;
    }
    .local-info-icon svg { width: 18px; height: 18px; stroke: #fff; }
    .local-info-label {
      font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.06em;
      color: rgba(255,255,255,0.55); margin-bottom: 5px; font-weight: 600;
    }
    .local-info-value {
      font-family: var(--font-d); font-size: 1.05rem; font-weight: 600; color: #fff;
    }
    @media (max-width: 760px) {
      .local-info-wrap { grid-template-columns: 1fr; }
    }

    /* Tarjeta destacada tipo "Crear diseño" */
    .create-card {
      background: var(--bg-2);
      border: 1px solid var(--border);
      border-radius: var(--radius);
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
    .create-btn:hover { opacity: 0.9; }

    /* Encabezado catálogo */
    .catalog-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 24px;
      flex-wrap: wrap;
      gap: 12px;
    }
    .catalog-header h2 { font-family: var(--font-d); font-size: 1.6rem; font-weight: 800; color: var(--text-1); letter-spacing: -0.02em; }
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
      border-radius: var(--radius);
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
    .product-card:hover .product-image img { transform: scale(1.02); }
    .product-image .no-img { font-size: 48px; color: var(--text-3); }
    .badge-new {
      position: absolute;
      top: 12px; right: 12px;
      background: var(--accent);
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
    .product-colors { display: flex; gap: 8px; margin-top: 6px; margin-bottom: 12px; }
    .color-dot { width: 18px; height: 18px; border-radius: 50%; border: 1px solid var(--border-2); }
    .product-sizes { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 12px; }
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
      display: flex; align-items: center; justify-content: center; gap: 6px;
      padding: 10px; border-radius: var(--radius-sm);
      background: var(--blue); color: white;
      font-size: 0.82rem; font-weight: 600;
      transition: background var(--tr);
      margin-top: auto; text-decoration: none; border: none; cursor: pointer;
    }

    /* ========== COMPONENTES COMPARTIDOS (heredados de páginas tipo panel) ========== */
    .card {
      background: var(--bg-2); border: 1px solid var(--border);
      border-radius: var(--radius); box-shadow: var(--shadow-sm);
      transition: background var(--tr), border-color var(--tr);
    }
    .card-pad { padding: 24px 28px; }
    .sec-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 10px; }
    .sec-title {
      font-family: var(--font-d); font-size: 1.6rem; font-weight: 800;
      color: var(--text-1); letter-spacing: -0.02em;
      display: flex; align-items: center; gap: 8px;
    }
    .sec-badge {
      font-size: 0.68rem; font-weight: 600; color: var(--blue);
      background: var(--blue-soft); border: 1px solid var(--blue-border);
      padding: 2px 9px; border-radius: 20px;
    }
    .sec-link { font-size: 0.8rem; font-weight: 600; color: var(--blue); text-decoration: none; }
    .sec-link:hover { opacity: 0.75; }

    .btn-primary {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 20px; border-radius: var(--radius-sm);
      background: var(--blue); color: white; border: none;
      font-family: var(--font-b); font-size: 0.88rem; font-weight: 600;
      cursor: pointer; text-decoration: none;
      box-shadow: 0 4px 14px var(--blue-shadow);
      transition: all var(--tr);
    }
    .btn-primary:hover { background: var(--blue-h); transform: translateY(-1px); }
    .btn-primary svg { width: 16px; height: 16px; }
    .btn-secondary {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 20px; border-radius: var(--radius-sm);
      background: var(--bg-3); color: var(--text-2);
      border: 1px solid var(--border);
      font-family: var(--font-b); font-size: 0.88rem; font-weight: 500;
      cursor: pointer; text-decoration: none; transition: all var(--tr);
    }
    .btn-secondary:hover { border-color: var(--blue-border); color: var(--blue); }

    .est { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
    .est::before { content:''; width:5px; height:5px; border-radius:50%; }
    .est-recibido   { background:#FEF3C7; color:#92400E; } .est-recibido::before   { background:#F59E0B; }
    .est-produccion { background:var(--blue-soft); color:var(--blue); } .est-produccion::before { background:var(--blue); }
    .est-listo      { background:#DCFCE7; color:#15803D; } .est-listo::before      { background:#22C55E; }
    .est-entregado  { background:var(--bg-3); color:var(--text-3); } .est-entregado::before  { background:var(--text-3); }
    .est-pendiente  { background:#FEE2E2; color:#991B1B; } .est-pendiente::before  { background:#EF4444; }
    .est-verificado { background:#DCFCE7; color:#15803D; } .est-verificado::before { background:#22C55E; }

    .badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 0.73rem; font-weight: 600; white-space: nowrap; }
    .badge-success { background: #DCFCE7; color: #15803D; }
    .badge-warning { background: #FEF3C7; color: #92400E; }
    .badge-info    { background: #DBEAFE; color: #1E40AF; }
    .badge-danger  { background: #FFE4E2; color: #B91C1C; }
    .badge-neutral { background: var(--bg-3); color: var(--text-2); border: 1px solid var(--border); }
    [data-theme="dark"] .badge-success { background: #14532d; color: #bbf7d0; }
    [data-theme="dark"] .badge-warning { background: #78350f; color: #fde68a; }
    [data-theme="dark"] .badge-info    { background: #1e3a8a; color: #bfdbfe; }
    [data-theme="dark"] .badge-danger  { background: #7f1d1d; color: #fecaca; }

    .empty-state { padding: 48px; text-align: center; }
    .empty-state svg { width: 40px; height: 40px; stroke: var(--border-2); margin: 0 auto 10px; display: block; }
    .empty-state p { font-size: 0.83rem; color: var(--text-3); }

    .reveal { opacity: 0; transform: translateY(14px); transition: opacity 0.5s ease, transform 0.5s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    /* Footer: banda oscura de marca, a todo el ancho del navegador */
    .main-footer {
      margin-top: 50px;
      padding: 40px 32px 20px;
      background: var(--sidebar-bg);
      transition: background var(--tr);
    }
    .footer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 30px;
      margin-bottom: 30px;
      max-width: 1400px;
      margin-left: auto;
      margin-right: auto;
    }
    .footer-col h4 {
      font-size: 0.85rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.5px; margin-bottom: 16px; color: #fff;
    }
    .footer-col ul { list-style: none; }
    .footer-col li { margin-bottom: 8px; }
    .footer-col a { text-decoration: none; font-size: 0.8rem; color: var(--sidebar-txt); transition: color 0.2s; }
    .footer-col a:hover { color: var(--accent); }
    .footer-bottom {
      display: flex; justify-content: space-between; align-items: center;
      flex-wrap: wrap; gap: 12px; padding-top: 20px;
      border-top: 1px solid rgba(255,255,255,0.12);
      font-size: 0.7rem; color: var(--sidebar-txt);
      max-width: 1400px;
      margin-left: auto;
      margin-right: auto;
    }
    .social-icons { display: flex; gap: 10px; }
    .social-icons a {
      width: 32px; height: 32px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
      font-size: 15px; text-decoration: none;
      transition: transform var(--tr), opacity var(--tr);
    }
    .social-icons a:hover { transform: translateY(-2px); opacity: 0.85; }
    .social-icons .ig { color: #F472B6; }
    .social-icons .fb { color: #60A5FA; }
    .social-icons .x  { color: #fff; }

    /* ========== BOTÓN FLOTANTE DE WHATSAPP ========== */
    .whatsapp-wrap {
      position: fixed; bottom: 24px; right: 24px; z-index: 300;
    }
    .whatsapp-float {
      width: 56px; height: 56px; border-radius: 50%;
      background: #25D366; border: none; color: white;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; box-shadow: var(--shadow-lg);
      transition: transform var(--tr);
    }
    .whatsapp-float:hover { transform: scale(1.06); }
    .whatsapp-float svg { width: 28px; height: 28px; }
    .whatsapp-panel {
      position: absolute; bottom: calc(100% + 14px); right: 0;
      width: 280px; background: var(--bg-2); border: 1px solid var(--border);
      border-radius: var(--radius); box-shadow: var(--shadow-lg);
      padding: 18px; display: none;
    }
    .whatsapp-wrap.open .whatsapp-panel { display: block; }
    .whatsapp-panel-titulo {
      font-family: var(--font-d); font-size: 1rem; font-weight: 800;
      color: var(--text-1); margin-bottom: 14px;
    }
    .wa-row {
      display: flex; align-items: center; gap: 10px;
      font-size: 0.82rem; color: var(--text-2); margin-bottom: 10px;
    }
    .wa-row svg { width: 16px; height: 16px; color: var(--text-3); flex-shrink: 0; }
    .wa-btn-enviar {
      display: flex; align-items: center; justify-content: center; gap: 8px;
      width: 100%; margin-top: 6px; padding: 10px; border-radius: var(--radius-sm);
      background: #25D366; color: white; font-weight: 600; font-size: 0.85rem;
      text-decoration: none; transition: opacity var(--tr);
    }
    .wa-btn-enviar:hover { opacity: 0.9; }
    .wa-btn-enviar svg { width: 16px; height: 16px; }

    @media (max-width: 900px) {
      .search-box { display: none; }
      .main-content { padding: 20px 16px; }
      .topbar-link { font-size: 0.75rem; }
      .filtros-panel { width: 88vw; left: -8px; }
    }
    @media (max-width: 550px) {
      .whatsapp-wrap { bottom: 16px; right: 16px; }
      .whatsapp-panel { width: 84vw; }
    }
    @media (max-width: 550px) {
      .topbar { padding: 0 12px; gap: 10px; }
      .topbar-brand img { height: 44px; }
      .topbar-link { display: none; }
    }

    @stack('estilos')
  </style>
</head>
<body>

@php $promoInfo = \App\Models\InformacionLocal::actual(); @endphp
<div class="promo-strip">
  {{ $promoInfo->nombre_local ?: 'Leo José' }}@if($promoInfo->descripcion) &nbsp;·&nbsp; {{ $promoInfo->descripcion }} @endif
</div>

@include('cliente.componentes.topbar-cliente')

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
        <a href="#" class="ig" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="#" class="fb" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="x" aria-label="X"><i class="fab fa-twitter"></i></a>
      </div>
    </div>
  </footer>
</div>

@include('cliente.componentes.whatsapp-flotante')

<script>
  // Tema (la detección/aplicación inicial ya corrió en el <head>, ver arriba)
  const html = document.documentElement;

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

  // reveal scroll
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.07 });
  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

  // Vista previa de archivos subidos (comprobantes, fotos, etc.). Si se pasa
  // dropAreaId, esa zona de arrastre se OCULTA y se reemplaza por una tarjeta
  // de vista previa más grande (imagen o ícono de PDF) con opción de cambiarla.
  function previsualizarArchivo(input, previewBoxId, dropAreaId) {
    const box = document.getElementById(previewBoxId);
    if (!box) return;
    const dropArea = dropAreaId ? document.getElementById(dropAreaId) : null;
    const file = input.files && input.files[0];

    if (!file) {
      box.style.display = 'none';
      box.innerHTML = '';
      if (dropArea) dropArea.style.display = 'flex';
      return;
    }

    if (dropArea) dropArea.style.display = 'none';
    box.style.display = 'block';

    const tamano = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
    const idInput = input.id;

    const renderizar = (miniaturaHtml) => {
      box.innerHTML = `
        <div style="display:flex;gap:16px;align-items:center;padding:16px;border:1.5px solid var(--border);border-radius:12px;background:var(--bg-3);">
          ${miniaturaHtml}
          <div style="flex:1;min-width:0;">
            <div style="font-weight:700;font-size:0.9rem;color:var(--text-1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${file.name}</div>
            <div style="font-size:0.78rem;color:var(--text-3);margin-top:2px;">${tamano}</div>
            ${idInput ? `<button type="button" onclick="quitarArchivoSeleccionado('${idInput}','${previewBoxId}'${dropAreaId ? `,'${dropAreaId}'` : ''})"
              style="margin-top:8px;background:none;border:none;color:var(--blue);font-weight:600;font-size:0.8rem;cursor:pointer;padding:0;text-decoration:underline;">
              Cambiar archivo
            </button>` : ''}
          </div>
        </div>`;
    };

    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = e => {
        renderizar(`<img src="${e.target.result}" alt="Vista previa" style="width:110px;height:110px;object-fit:cover;border-radius:10px;border:1px solid var(--border);flex-shrink:0;">`);
      };
      reader.readAsDataURL(file);
    } else {
      renderizar(`<div style="width:110px;height:110px;border-radius:10px;background:var(--bg-2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg viewBox="0 0 24 24" style="width:44px;height:44px;stroke:#EF4444;fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>`);
    }
  }

  function quitarArchivoSeleccionado(inputId, previewBoxId, dropAreaId) {
    const input = document.getElementById(inputId);
    if (input) input.value = '';
    const box = document.getElementById(previewBoxId);
    if (box) { box.style.display = 'none'; box.innerHTML = ''; }
    if (dropAreaId) {
      const area = document.getElementById(dropAreaId);
      if (area) area.style.display = 'flex';
    }
  }
</script>

@stack('scripts')

<!-- Lightbox: ver en grande fotos de productos y comprobantes de pago -->
<div class="lightbox-overlay" id="lightbox-overlay" onclick="cerrarLightbox()">
  <button type="button" class="lightbox-cerrar" onclick="cerrarLightbox()" aria-label="Cerrar">&times;</button>
  <img id="lightbox-img" src="" alt="Vista ampliada" onclick="event.stopPropagation()">
</div>
<style>
  .lightbox-overlay {
    display: none; position: fixed; inset: 0; z-index: 3000;
    background: rgba(0,0,0,0.85);
    align-items: center; justify-content: center;
    padding: 40px; cursor: zoom-out;
  }
  .lightbox-overlay.open { display: flex; }
  .lightbox-overlay img {
    max-width: 92vw; max-height: 92vh; border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    cursor: default;
  }
  .lightbox-cerrar {
    position: absolute; top: 20px; right: 28px;
    background: rgba(255,255,255,0.12); border: none; color: white;
    width: 40px; height: 40px; border-radius: 50%; font-size: 1.6rem; line-height: 1;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: background 0.15s;
  }
  .lightbox-cerrar:hover { background: rgba(255,255,255,0.25); }
</style>
<script>
  function abrirLightbox(src) {
    if (!src) return;
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-overlay').classList.add('open');
  }
  function cerrarLightbox() {
    document.getElementById('lightbox-overlay').classList.remove('open');
    document.getElementById('lightbox-img').src = '';
  }
  document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarLightbox(); });
</script>

</body>
</html>
