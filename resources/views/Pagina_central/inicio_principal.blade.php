<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>Leo José | Catálogo Deportivo</title>
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
      --blue:         #2563EB;
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
      --bg:           #0B1120;
      --bg-2:         #111827;
      --bg-3:         #1A2235;
      --border:       #1E2D45;
      --border-2:     #2A3F5F;
      --text-1:       #F1F5F9;
      --text-2:       #94A3B8;
      --text-3:       #475569;
      --sidebar-bg:   #080E1A;
      --sidebar-actbg:rgba(37,99,235,0.2);
      --blue-soft:    rgba(37,99,235,0.12);
      --blue-border:  rgba(37,99,235,0.3);
      --shadow-sm:    0 1px 3px rgba(0,0,0,0.3);
      --shadow-md:    0 4px 16px rgba(0,0,0,0.4);
      --shadow-lg:    0 12px 40px rgba(0,0,0,0.5);
    }

    html, body { height: 100%; scroll-behavior: smooth; }
    body {
      font-family: var(--font-b);
      background: var(--bg);
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
      backdrop-filter: blur(0px);
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
    }
    .btn-logout {
      background: var(--bg-3); border: 1px solid var(--border);
      padding: 7px 14px; border-radius: 30px;
      font-size: 0.75rem; font-weight: 600; color: var(--text-2);
      text-decoration: none; transition: all var(--tr);
    }
    .btn-logout:hover { border-color: #FCA5A5; background: #FEF2F2; color: #DC2626; }

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

    /* ========== MAIN CONTENT ========== */
    .main-wrap {
      margin-top: var(--nav-h);
      margin-left: var(--sidebar-w);
      min-height: calc(100vh - var(--nav-h));
      display: flex; flex-direction: column;
    }
    .main-content {
      flex: 1;
      padding: 28px 32px;
    }

    /* Tarjetas destacadas (Uniformes + Crea diseño) */
    .featured-row {
      display: flex;
      gap: 24px;
      margin-bottom: 40px;
      flex-wrap: wrap;
    }
    .feature-card {
      flex: 1;
      background: var(--bg-2);
      border-radius: 20px;
      padding: 24px 28px;
      border: 1px solid var(--border);
      box-shadow: var(--shadow-sm);
      transition: all 0.25s ease;
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
    }
    .feature-card:hover {
      transform: translateY(-4px);
      border-color: var(--blue-border);
      box-shadow: var(--shadow-md);
    }
    .feature-info h3 {
      font-size: 1.4rem; font-weight: 700; margin-bottom: 8px;
      color: var(--text-1);
    }
    .feature-info p {
      font-size: 0.85rem; color: var(--text-2); max-width: 280px;
    }
    .feature-link {
      margin-top: 14px; font-weight: 600; color: var(--blue);
      font-size: 0.85rem; display: inline-flex; align-items: center; gap: 4px;
    }
    .feature-icon {
      width: 70px; height: 70px; background: var(--blue-soft);
      border-radius: 40px; display: flex; align-items: center; justify-content: center;
      font-size: 32px; color: var(--blue);
      transition: 0.2s;
    }
    .feature-card:hover .feature-icon {
      background: var(--blue);
      color: white;
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
    .catalog-header p {
      color: var(--text-2); font-size: 0.85rem;
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
      border-radius: 18px;
      overflow: hidden;
      border: 1px solid var(--border);
      transition: all 0.25s ease;
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-md);
      border-color: var(--blue-border);
    }
    .product-image {
      position: relative;
      background: var(--bg-3);
      height: 260px;
      overflow: hidden;
    }
    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s;
    }
    .product-card:hover .product-image img {
      transform: scale(1.02);
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
      min-height: 44px;
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
    }
    .color-dot {
      width: 18px; height: 18px;
      border-radius: 50%;
      border: 1px solid var(--border-2);
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
      .featured-row { flex-direction: column; }
      .products-grid { gap: 16px; }
      .main-content { padding: 20px 16px; }
      .search-box { max-width: 200px; }
    }
    @media (max-width: 550px) {
      .topbar { padding: 0 12px; gap: 10px; }
      .brand-name { font-size: 1rem; }
      .search-box { display: none; }
    }
  </style>
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
  <div class="topbar-brand">
    <div class="brand-icon"><i class="fas fa-crown"></i></div>
    <span class="brand-name">Leo José</span>
  </div>
  <div class="search-box">
    <i class="fas fa-search"></i>
    <input type="text" placeholder="Buscar productos...">
  </div>
  <div class="topbar-right">
    <button class="btn-theme" onclick="toggleTheme()">
      <i class="fas fa-moon icon-moon"></i>
      <i class="fas fa-sun icon-sun"></i>
    </button>
    <div class="nav-avatar">LJ</div>
    <a href="/login" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
  </div>
</header>

<!-- SIDEBAR FILTROS -->
<aside class="sidebar">
  <h5><i class="fas fa-sliders-h" style="margin-right: 6px;"></i> Filtros</h5>
  <div class="filter-group">
    <h6><i class="fas fa-layer-group"></i> Categorías</h6>
    <ul>
      <li><i class="fas fa-futbol"></i> Fútbol</li>
      <li><i class="fas fa-basketball-ball"></i> Basketball</li>
      <li><i class="fas fa-running"></i> Running</li>
      <li><i class="fas fa-dumbbell"></i> Fitness</li>
      <li><i class="fas fa-heartbeat"></i> Training</li>
    </ul>
  </div>
  <div class="filter-group">
    <h6><i class="fas fa-chart-simple"></i> Tallas</h6>
    <ul><li>S</li><li>M</li><li>L</li><li>XL</li><li>XXL</li></ul>
  </div>
  <div class="filter-group">
    <h6><i class="fas fa-palette"></i> Colores</h6>
    <ul><li>Negro</li><li>Blanco</li><li>Azul</li><li>Rojo</li></ul>
  </div>
</aside>

<div class="main-wrap">
  <main class="main-content">

    <!-- TARJETAS DESTACADAS (Uniformes escolares + Crea diseño) -->
    <div class="featured-row">
      <div class="feature-card">
        <div class="feature-info">
          <h3><i class="fas fa-graduation-cap" style="color: var(--blue); margin-right: 8px;"></i> Uniformes Escolares</h3>
          <p>Colección premium para instituciones. Calidad y personalización garantizadas.</p>
          <div class="feature-link">Solicitar cotización <i class="fas fa-arrow-right"></i></div>
        </div>
        <div class="feature-icon"><i class="fas fa-school"></i></div>
      </div>
      <div class="feature-card">
        <div class="feature-info">
          <h3><i class="fas fa-palette"></i> Crea tu propio diseño</h3>
          <p>Diseña tu indumentaria única: colores, logos, nombres. Todo hecho a medida.</p>
          <div class="feature-link">Empezar ahora <i class="fas fa-arrow-right"></i></div>
        </div>
        <div class="feature-icon"><i class="fas fa-pencil-ruler"></i></div>
      </div>
    </div>

    <!-- ENCABEZADO CATÁLOGO -->
    <div class="catalog-header">
      <div>
        <h2>Colección Performance</h2>
        <p>Jerseys y prendas técnicas de última generación</p>
      </div>
      <div class="product-count"><i class="fas fa-tshirt"></i> 24 productos</div>
    </div>

{{-- ── PLANTILLAS ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
  <div>
    <h2 style="font-size:1.4rem;font-weight:700;color:var(--text-1);">Plantillas disponibles</h2>
    <p style="color:var(--text-2);font-size:0.85rem;">{{ $plantillas->count() }} modelos disponibles</p>
  </div>
</div>

<div class="products-grid">
  @forelse($plantillas as $plantilla)
    <div class="product-card">
      <div class="product-image">
        @if($plantilla->imagen_preview)
          <img src="{{ asset('storage/'.$plantilla->imagen_preview) }}"
               alt="{{ $plantilla->nombre }}">
        @else
          <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-tshirt" style="font-size:48px;color:var(--text-3);"></i>
          </div>
        @endif
        <span class="badge-new" style="text-transform:capitalize;">{{ $plantilla->tipo_prenda }}</span>
      </div>
      <div class="product-body">
        <div class="product-name">{{ $plantilla->nombre }}</div>
        <div style="font-size:0.78rem;color:var(--text-3);margin-bottom:10px;text-transform:capitalize;">
          {{ $plantilla->tipo_prenda }}
        </div>
        <a href="#" style="display:flex;align-items:center;justify-content:center;gap:6px;
          padding:10px;border-radius:10px;background:var(--blue);color:white;
          font-size:0.82rem;font-weight:600;text-decoration:none;margin-top:auto;
          transition:background var(--tr);"
          onmouseover="this.style.background='var(--blue-h)'"
          onmouseout="this.style.background='var(--blue)'">
          <i class="fas fa-shopping-cart"></i> Personalizar
        </a>
      </div>
    </div>
  @empty
    <div style="grid-column:1/-1;text-align:center;padding:48px;background:var(--bg-2);
      border-radius:var(--radius);border:1px solid var(--border);">
      <i class="fas fa-tshirt" style="font-size:40px;color:var(--text-3);display:block;margin-bottom:12px;"></i>
      <p style="color:var(--text-3);font-size:0.88rem;">No hay plantillas disponibles aún.</p>
    </div>
  @endforelse
</div> 
  </main>

  <!-- FOOTER COMPLETO -->
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
  // Toggle dark/light mode
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
</script>
</body>
</html>