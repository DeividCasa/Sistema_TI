<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
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
      box-shadow: 0 4px 14px rgba(37,99,235,0.25);
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

    .sec-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .sec-title {
      font-family: var(--font-d); font-size: 1rem; font-weight: 800;
      color: var(--text-1); letter-spacing: -0.015em;
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

    /* tablas */
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

  {{-- ══ TOPBAR ══ --}}
  <header class="topbar">
    <div class="topbar-brand">
      <img src="{{ asset('images/logo.png') }}" width="110" height="99" alt="" >    
    </div>

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

@stack('scripts')

  <script>
    // modo nocturno
    const html = document.documentElement;
    const saved = localStorage.getItem('lj-theme');
    if (saved) html.setAttribute('data-theme', saved);
    else if (window.matchMedia('(prefers-color-scheme: dark)').matches)
      html.setAttribute('data-theme', 'dark');

    function toggleTheme() {
      const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      html.setAttribute('data-theme', next);
      localStorage.setItem('lj-theme', next);
    }

    // reveal scroll
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.07 });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
  </script>

</body>
</html>