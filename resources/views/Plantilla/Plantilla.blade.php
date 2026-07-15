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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('titulo', 'Leo José')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    /* ══════════════════════
       VARIABLES MODO CLARO
    ══════════════════════ */
    :root {
      --bg:           #F1F5F9;
      --bg-2:         #FFFFFF;
      --bg-3:         #F8FAFC;
      --border:       #E2E8F0;
      --border-2:     #CBD5E1;
      --text-1:       #0F172A;
      --text-2:       #475569;
      --text-3:       #240677;
      --blue:         #240677;
      --blue-h:       #1D4ED8;
      --blue-soft:    #EFF6FF;
      --blue-border:  #BFDBFE;
      --blue-light:   #60A5FA;
      --blue-shadow:  rgba(36,6,119,0.22);
      --sidebar-bg:   #0F172A;
      --sidebar-txt:  rgba(255,255,255,0.55);
      --sidebar-act:  #FFFFFF;
      --sidebar-hover:rgba(255,255,255,0.06);
      --sidebar-actbg:rgba(255,255,255,0.1);
      --shadow-sm:    0 1px 3px rgba(0,0,0,0.06);
      --shadow-md:    0 4px 16px rgba(0,0,0,0.08);
      --shadow-lg:    0 12px 40px rgba(0,0,0,0.1);
      --radius:       14px;
      --font-d:       'Outfit', sans-serif;
      --font-b:       'DM Sans', sans-serif;
      --nav-h:        60px;
      --sidebar-w:    240px;
      --tr:           0.22s cubic-bezier(.4,0,.2,1);
    }

    /* ══════════════════════
       VARIABLES MODO OSCURO
    ══════════════════════ */
    [data-theme="dark"] {
      --bg:           #0B1220;
      --bg-2:         #141B2E;
      --bg-3:         #1C2540;
      --border:       #263349;
      --border-2:     #34405E;
      --text-1:       #F1F5F9;
      --text-2:       #A9B4C7;
      --text-3:       #71829C;
      --blue:         #8B7CF6;
      --blue-h:       #A296FF;
      --blue-soft:    rgba(139,124,246,0.14);
      --blue-border:  rgba(139,124,246,0.32);
      --blue-shadow:  rgba(139,124,246,0.35);
      --sidebar-bg:   #070B15;
      --sidebar-actbg:rgba(139,124,246,0.2);
      --shadow-sm:    0 1px 3px rgba(0,0,0,0.35);
      --shadow-md:    0 4px 18px rgba(0,0,0,0.45);
      --shadow-lg:    0 14px 44px rgba(0,0,0,0.55);
    }

    html, body { height: 100%; }
    body {
      font-family: var(--font-b);
      background: var(--bg);
      color: var(--text-1);
      transition: background var(--tr), color var(--tr);
    }

    /* ══ TOPBAR ══ */
    .topbar {
      position: fixed; top: 0; left: 0; right: 0; z-index: 200;
      height: var(--nav-h);
      background: var(--bg-2);
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center;
      padding: 0 20px;
      gap: 12px;
      box-shadow: var(--shadow-sm);
      transition: background var(--tr), border-color var(--tr);
    }
    .topbar-brand {
      display: flex; align-items: center; gap: 10px;
      text-decoration: none; width: var(--sidebar-w); flex-shrink: 0;
    }
    .brand-icon {
      width: 34px; height: 34px; border-radius: 9px;
      background: var(--blue);
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .brand-icon svg { width: 17px; height: 17px; stroke: white; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .brand-name { font-family: var(--font-d); font-weight: 800; font-size: 1rem; color: var(--text-1); letter-spacing: -0.02em; transition: color var(--tr); }

    .topbar-title { flex: 1; font-family: var(--font-d); font-weight: 700; font-size: 0.95rem; color: var(--text-1); transition: color var(--tr); }

    .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }

    /* botón modo nocturno */
    .btn-theme {
      width: 36px; height: 36px; border-radius: 9px;
      background: var(--bg-3); border: 1px solid var(--border);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--text-2); transition: all var(--tr);
    }
    .btn-theme:hover { background: var(--blue-soft); border-color: var(--blue-border); color: var(--blue); }
    .btn-theme svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .icon-moon { display: block; }
    .icon-sun  { display: none;  }
    [data-theme="dark"] .icon-moon { display: none;  }
    [data-theme="dark"] .icon-sun  { display: block; }

    /* avatar usuario */
    .nav-avatar {
      width: 34px; height: 34px; border-radius: 50%;
      background: linear-gradient(135deg, var(--blue), var(--blue-light));
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-d); font-weight: 800; font-size: 0.8rem; color: white;
      cursor: pointer;
    }

    /* logout */
    .btn-logout {
      display: flex; align-items: center; gap: 6px;
      padding: 7px 14px; border-radius: 9px;
      border: 1px solid var(--border); background: var(--bg-3);
      font-family: var(--font-b); font-size: 0.8rem; font-weight: 500;
      color: var(--text-2); cursor: pointer; text-decoration: none;
      transition: all var(--tr);
    }
    .btn-logout svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .btn-logout:hover { border-color: #FCA5A5; background: #FEF2F2; color: #DC2626; }

    /* ══ SIDEBAR ══ */
    .sidebar {
      position: fixed; top: var(--nav-h); left: 0; bottom: 0;
      width: var(--sidebar-w);
      background: var(--sidebar-bg);
      display: flex; flex-direction: column;
      padding: 16px 12px;
      z-index: 100; overflow-y: auto;
      transition: background var(--tr);
    }
    .sidebar-label {
      font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em;
      text-transform: uppercase; color: rgba(255,255,255,0.22);
      padding: 0 10px; margin: 16px 0 6px;
    }
    .nav-item {
      display: flex; align-items: center; gap: 10px;
      padding: 9px 10px; border-radius: 9px;
      text-decoration: none; color: var(--sidebar-txt);
      font-size: 0.84rem; font-weight: 500;
      transition: all var(--tr); margin-bottom: 2px;
      position: relative;
    }
    .nav-item:hover { background: var(--sidebar-hover); color: rgba(255,255,255,0.9); }
    .nav-item.active { background: var(--sidebar-actbg); color: var(--sidebar-act); font-weight: 600; }
    .nav-item.active::before {
      content: ''; position: absolute; left: 0; top: 20%; bottom: 20%;
      width: 3px; border-radius: 0 3px 3px 0; background: var(--blue-light);
    }
    .nav-item svg { width: 17px; height: 17px; stroke: currentColor; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }
    .nav-badge {
      margin-left: auto; font-size: 0.65rem; font-weight: 700;
      padding: 2px 7px; border-radius: 10px; background: var(--blue); color: white;
    }
    .sidebar-sep { height: 1px; background: rgba(255,255,255,0.06); margin: 8px 0; }
    .sidebar-foot { margin-top: auto; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.06); }

    /* ══ LAYOUT ══ */
    .main-wrap {
      margin-top: var(--nav-h);
      margin-left: var(--sidebar-w);
      min-height: calc(100vh - var(--nav-h));
      display: flex; flex-direction: column;
    }
    .main-content { flex: 1; padding: 28px 32px; display: block; width: 100%; }

    /* ══ FOOTER ══ */
    .main-footer {
      padding: 16px 32px;
      border-top: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      font-size: 0.75rem; color: var(--text-3);
      background: var(--bg-2);
      transition: background var(--tr), border-color var(--tr);
    }

    

    .btn-primary {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 20px; border-radius: 9px;
      background: var(--blue); color: white; border: none;
      font-family: var(--font-b); font-size: 0.88rem; font-weight: 600;
      cursor: pointer; text-decoration: none;
      box-shadow: 0 4px 14px var(--blue-shadow);
      transition: all var(--tr);
    }
    .btn-primary:hover { background: var(--blue-h); transform: translateY(-1px); }
    .btn-primary svg { width: 16px; height: 16px; stroke: white; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .btn-secondary {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 20px; border-radius: 9px;
      background: var(--bg-3); color: var(--text-2);
      border: 1px solid var(--border);
      font-family: var(--font-b); font-size: 0.88rem; font-weight: 500;
      cursor: pointer; text-decoration: none; transition: all var(--tr);
    }
    .btn-secondary:hover { border-color: var(--blue-border); color: var(--blue); }

    .sec-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 10px; }
    .sec-title {
      font-family: var(--font-d); font-size: 1.6rem; font-weight: 800;
      color: var(--text-1); letter-spacing: -0.02em;
      display: flex; align-items: center; gap: 8px;
      transition: color var(--tr);
    }
    .sec-badge {
      font-size: 0.68rem; font-weight: 600; color: var(--blue);
      background: var(--blue-soft); border: 1px solid var(--blue-border);
      padding: 2px 9px; border-radius: 20px;
    }
    .sec-link { font-size: 0.8rem; font-weight: 600; color: var(--blue); text-decoration: none; }
    .sec-link:hover { opacity: 0.75; }

    /* tarjeta genérica de contenido */
    .card {
      background: var(--bg-2); border: 1px solid var(--border);
      border-radius: var(--radius); box-shadow: var(--shadow-sm);
      transition: background var(--tr), border-color var(--tr);
    }
    .card-pad { padding: 24px 28px; }

    /* tabla de listados admin, un solo diseño para todas las vistas */
    .admin-table { width: 100%; border-collapse: collapse; font-size: 0.86rem; }
    .admin-table th {
      background: var(--bg-3); text-align: left; padding: 12px 16px;
      color: var(--text-2); font-weight: 600; font-size: 0.7rem;
      text-transform: uppercase; letter-spacing: 0.04em;
      border-bottom: 1px solid var(--border);
    }
    .admin-table td {
      padding: 10px 16px; border-top: 1px solid var(--border);
      color: var(--text-2); vertical-align: middle;
    }
    .admin-table tbody tr:hover td { background: var(--bg-3); }
    .admin-table .cell-strong { font-weight: 600; color: var(--text-1); }
    .admin-table .cell-muted { font-size: 0.75rem; color: var(--text-3); }
    .admin-table .cell-empty { padding: 32px; text-align: center; color: var(--text-3); }
    .admin-table .cell-actions a, .admin-table .cell-actions button {
      color: var(--blue); text-decoration: none; font-weight: 600; font-size: 0.82rem;
      background: none; border: none; cursor: pointer; padding: 0; margin-right: 12px;
    }
    .admin-table .cell-actions .link-danger { color: #EF4444; }
    .admin-table img.cell-thumb { width: 56px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); }

    /* badges de estado semánticos, reutilizables en cualquier tabla/tarjeta */
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

    .btn-marcar-pagado {
      display: inline-block; padding: 3px 9px; border-radius: 6px;
      background: transparent; color: #15803D; border: 1px solid #bbf7d0;
      font-size: 0.7rem; font-weight: 600; cursor: pointer; transition: all 0.15s;
    }
    .btn-marcar-pagado:hover { background: #15803D; color: white; border-color: #15803D; }

    /* tablas (grid, componente antiguo, se mantiene por compatibilidad) */
    .tabla-box {
      background: var(--bg-2); border: 1px solid var(--border);
      border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow-sm);
      transition: background var(--tr), border-color var(--tr);
    }
    .tabla-head {
      display: grid; padding: 11px 20px;
      background: var(--bg-3); border-bottom: 1px solid var(--border);
      font-size: 0.68rem; font-weight: 700; color: var(--text-3);
      letter-spacing: 0.06em; text-transform: uppercase;
      transition: background var(--tr), border-color var(--tr), color var(--tr);
    }
    .tabla-row {
      display: grid; padding: 13px 20px;
      border-bottom: 1px solid var(--border);
      align-items: center; transition: background var(--tr);
    }
    .tabla-row:last-child { border-bottom: none; }
    .tabla-row:hover { background: var(--bg-3); }
    .t-code  { font-family: var(--font-d); font-weight: 700; font-size: 0.83rem; color: var(--blue); }
    .t-text  { font-size: 0.83rem; font-weight: 500; color: var(--text-1); transition: color var(--tr); }
    .t-sub   { font-size: 0.8rem; color: var(--text-2); transition: color var(--tr); }
    .t-muted { font-size: 0.78rem; color: var(--text-3); transition: color var(--tr); }

    /* badges de estado */
    .est {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 4px 10px; border-radius: 20px;
      font-size: 0.7rem; font-weight: 600;
    }
    .est::before { content:''; width:5px; height:5px; border-radius:50%; }
    .est-recibido   { background:#FEF3C7; color:#92400E; } .est-recibido::before   { background:#F59E0B; }
    .est-produccion { background:var(--blue-soft); color:var(--blue); } .est-produccion::before { background:var(--blue); }
    .est-listo      { background:#DCFCE7; color:#15803D; } .est-listo::before      { background:#22C55E; }
    .est-entregado  { background:var(--bg-3); color:var(--text-3); } .est-entregado::before  { background:var(--text-3); }
    .est-pendiente  { background:#FEE2E2; color:#991B1B; } .est-pendiente::before  { background:#EF4444; }
    .est-verificado { background:#DCFCE7; color:#15803D; } .est-verificado::before { background:#22C55E; }

    /* empty state */
    .empty-state { padding: 48px; text-align: center; }
    .empty-state svg { width: 40px; height: 40px; stroke: var(--border-2); fill: none; stroke-width: 1.5; stroke-linecap: round; stroke-linejoin: round; margin: 0 auto 10px; display: block; }
    .empty-state p { font-size: 0.83rem; color: var(--text-3); }

    /* animacion reveal */
    .reveal { opacity: 0; transform: translateY(14px); transition: opacity 0.5s ease, transform 0.5s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    @media (max-width: 900px) {
      :root { --sidebar-w: 0px; }
      .sidebar { display: none; }
      .topbar-brand { width: auto; }
      .main-content { padding: 20px 16px; }
    }
  </style>

  @stack('estilos')
</head>
<body>

  {{-- ══ TOPBAR ══ (las vistas de cliente pueden sobrescribir esta sección
       con @section('topbar') ... @endsection para usar el topbar con
       enlaces + menú de cuenta, igual al de la página de inicio) --}}
  @section('topbar')
  <header class="topbar">
    <a class="topbar-brand" href="{{ route('admin.inicio') }}">
      <img src="{{ asset('images/logo.png') }}" alt="Leo José" style="display:block;height:42px;width:auto;">
    </a>

    <span class="topbar-title">@yield('page-title', 'Inicio')</span>

    <div class="topbar-right">
      <button class="btn-theme" onclick="toggleTheme()" title="Cambiar tema">
        <svg class="icon-moon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg class="icon-sun"  viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      </button>
      <div class="nav-avatar">{{ strtoupper(substr(session('usuario_nombre', 'U'), 0, 1)) }}</div>
      <span style="font-size:0.82rem;font-weight:600;color:var(--text-2);">{{ session('usuario_nombre', '') }}</span>
    <a href="{{ route('logout') }}" class="btn-logout">Salir</a>
      @stack('topbar-acciones')
    </div>
  </header>
  @show

<aside class="sidebar" style="@yield('sidebar-display', 'display:none')">
    @stack('sidebar-menu')
    <div class="sidebar-foot">
        @stack('sidebar-foot')
    </div>
</aside>

<div class="main-wrap" style="margin-left: @yield('sidebar-margin', '0px')">
    <main class="main-content">
        @yield('contenido')
    </main>
    <footer class="main-footer">
        <span>© 2026 Creaciones Leo José de Salcedo</span>
        <span>Laravel 11 · Three.js · IA</span>
    </footer>
</div>

<script>
  // Vista previa de archivos subidos (fotos de productos, etc.). Si se pasa
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

  <script>
    // modo nocturno (la detección/aplicación inicial ya corrió en el <head>, ver arriba)
    const html = document.documentElement;

    function toggleTheme() {
      const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      html.setAttribute('data-theme', next);
      localStorage.setItem('lj-theme', next);
    }

    // menú de cuenta (topbar de cliente)
    function toggleAccountMenu() {
      document.getElementById('account-menu-wrap')?.classList.toggle('open');
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
  </script>

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