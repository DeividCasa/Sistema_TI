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
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/css/leojoma-overrides.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    /* ══════════════════════
       VARIABLES MODO CLARO
    ══════════════════════ */
    :root {
      --bg:           #F7F7F3;
      --bg-2:         #FFFFFF;
      --bg-3:         #F0F1EB;
      --border:       #E4E4DD;
      --border-2:     #D2D2C6;
      --text-1:       #16181A;
      --text-2:       #53565B;
      --text-3:       #90928C;
      --blue:         #1F2937;
      --blue-h:       #111827;
      --blue-soft:    #F3F4F6;
      --blue-border:  #D1D5DB;
      --blue-light:   #6B7280;
      --blue-shadow:  rgba(31,41,55,0.22);
      --accent:       #FF5A3C;
      --accent-soft:  #FFE9E3;
      --accent-border:#FFC3B2;
      --ink:          #14161A;
      --sidebar-bg:   #14161A;
      --sidebar-txt:  rgba(255,255,255,0.62);
      --sidebar-act:  #FFFFFF;
      --sidebar-hover:rgba(255,255,255,0.08);
      --sidebar-actbg:rgba(255,255,255,0.10);
      --shadow-sm:    0 1px 3px rgba(20,22,26,0.08);
      --shadow-md:    0 8px 24px rgba(20,22,26,0.1);
      --shadow-lg:    0 20px 50px rgba(20,22,26,0.16);
      --radius:       14px;
      --radius-sm:    10px;
      --font-d:       'Playfair Display', serif;
      --font-b:       'Poppins', sans-serif;
      --nav-h:        60px;
      --sidebar-w:    240px;
      --tr:           0.22s cubic-bezier(.4,0,.2,1);
    }

    /* ══════════════════════
       VARIABLES MODO OSCURO
    ══════════════════════ */
    [data-theme="dark"] {
      --bg:           #121314;
      --bg-2:         #1B1C1F;
      --bg-3:         #232427;
      --border:       #333438;
      --border-2:     #45464B;
      --text-1:       #F5F6F3;
      --text-2:       #B8BAB6;
      --text-3:       #85878A;
      --blue:         #9CA3AF;
      --blue-h:       #D1D5DB;
      --blue-soft:    rgba(156,163,175,0.14);
      --blue-border:  rgba(156,163,175,0.32);
      --blue-shadow:  rgba(156,163,175,0.35);
      --accent:       #FF7A5C;
      --accent-soft:  rgba(255,90,60,0.16);
      --accent-border:rgba(255,90,60,0.35);
      --ink:          #0B0C0D;
      --sidebar-bg:   #0B0C0D;
      --sidebar-actbg:rgba(255,255,255,0.10);
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
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      padding: 10px 20px; border-radius: 9px;
      background: var(--blue); color: white; border: none;
      font-family: var(--font-b); font-size: 0.88rem; font-weight: 600;
      cursor: pointer; text-decoration: none;
      box-shadow: 0 4px 14px var(--blue-shadow);
      transition: all var(--tr);
    }
    .btn-primary:hover { background: var(--blue-h); transform: translateY(-1px); }
    .btn-primary svg { width: 16px; height: 16px; stroke: white; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    /* Botón de aprobar/verificar: verde siempre (acción de éxito), sin
       importar el color de acento del panel — distinto de btn-primary. */
    .btn-verificar {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      padding: 10px 20px; border-radius: 9px; width: 100%;
      background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0;
      font-family: var(--font-b); font-size: 0.85rem; font-weight: 600;
      cursor: pointer; text-decoration: none; transition: all var(--tr);
    }
    .btn-verificar:hover { background: #BBF7D0; }
    [data-theme="dark"] .btn-verificar { background: rgba(34,197,94,0.16); color: #4ADE80; border-color: rgba(34,197,94,0.35); }
    [data-theme="dark"] .btn-verificar:hover { background: rgba(34,197,94,0.26); }

    .btn-secondary {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
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
      background: var(--bg-3); text-align: left; padding: 12px 12px;
      color: var(--text-2); font-weight: 600; font-size: 0.7rem;
      text-transform: uppercase; letter-spacing: 0.04em;
      border-bottom: 1px solid var(--border);
    }
    .admin-table td {
      padding: 10px 12px; border-top: 1px solid var(--border);
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

    .admin-table .cell-actions .btn-marcar-pagado {
      display: inline-block; padding: 3px 9px; border-radius: 6px; margin-right: 0;
      background: transparent; color: var(--accent); border: 1px solid var(--accent-border);
      font-size: 0.7rem; font-weight: 600; cursor: pointer; transition: all 0.15s;
    }
    .admin-table .cell-actions .btn-marcar-pagado:hover { background: var(--accent); color: white; border-color: var(--accent); }

    /* botones de canal de notificación (WhatsApp / Correo) en detalle de pedido */
    .canal-toggles { display: flex; gap: 10px; flex-wrap: wrap; }
    .canal-toggle-input { position: absolute; opacity: 0; width: 0; height: 0; }
    .canal-toggle-btn {
      display: inline-flex; align-items: center; gap: 7px;
      padding: 9px 16px; border-radius: 8px; border: 1.5px solid var(--border);
      background: var(--bg-2); color: var(--text-2); font-size: 0.85rem; font-weight: 600;
      cursor: pointer; transition: all 0.15s; user-select: none;
    }
    .canal-toggle-btn svg { width: 16px; height: 16px; flex-shrink: 0; }
    .canal-toggle-input:focus-visible + .canal-toggle-btn { outline: 2px solid var(--blue); outline-offset: 2px; }
    .canal-toggle-whatsapp { border-color: #25D366; color: #25D366; }
    .canal-toggle-email { border-color: #EA4335; color: #EA4335; }
    .canal-toggle-whatsapp:hover { background: #25D36622; }
    .canal-toggle-email:hover { background: #EA433522; }
    .canal-toggle-input:checked + .canal-toggle-whatsapp { background: #25D366; color: #fff; }
    .canal-toggle-input:checked + .canal-toggle-email { background: #EA4335; color: #fff; }

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

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
  <style>
    .iziToast-question-actions button { cursor: pointer; }
  </style>

  {{-- DataTables + exportación (Excel/PDF/CSV) para las tablas del panel admin --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.min.css">
  <style>
    .dt-container { color: var(--text-2); font-family: var(--font-b); font-size: 0.85rem; }
    table.dataTable { border-collapse: collapse !important; }

    /* Barra de herramientas: botones de exportar a la izquierda, buscador a la
       derecha, en la misma fila; separada de la tabla con un borde. */
    .dt-layout-table { margin-top: 4px; }
    .dt-layout-row:has(.dt-buttons, .dt-search) {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 10px;
      padding-bottom: 14px; margin-bottom: 14px; border-bottom: 1px solid var(--border);
    }
    .dt-search { display: flex; align-items: center; gap: 6px; }
    .dt-search input {
      background: var(--bg-3); border: 1px solid var(--border); border-radius: 999px;
      color: var(--text-1); padding: 7px 14px; outline: none; min-width: 220px;
    }
    .dt-length select {
      background: var(--bg-3); border: 1px solid var(--border); border-radius: 10px;
      color: var(--text-1); padding: 5px 10px; outline: none;
    }
    /* El CSS base de DataTables trae "div.dt-container .dt-paging .dt-paging-button
       { color:inherit !important }" — más específico que un simple ".dt-paging
       .dt-paging-button", así que hay que igualar esa especificidad (mismo
       "div.dt-container..." al inicio) para que el número de la página actual
       no quede invisible (texto heredado gris sobre fondo oscuro). */
    div.dt-container .dt-paging .dt-paging-button {
      color: var(--text-2) !important; border-radius: 10px !important; border: 1px solid transparent !important;
    }
    div.dt-container .dt-paging .dt-paging-button.current,
    div.dt-container .dt-paging .dt-paging-button.current:hover {
      background: var(--blue) !important; color: #fff !important; border-color: var(--blue) !important;
    }
    div.dt-container .dt-paging .dt-paging-button:hover:not(.disabled):not(.current) {
      background: var(--bg-3) !important; color: var(--text-1) !important;
    }

    /* Botones de exportar: píldora suave (fondo tenue + texto/ícono de color,
       sin relleno sólido) con un color e ícono propio de cada formato
       (verde=Excel, rojo=PDF, azul=CSV), para reconocerlos de un vistazo
       sin que se vean duros/fríos. */
    .dt-buttons { display: flex; gap: 8px; flex-wrap: wrap; }
    .dt-buttons .dt-button {
      display: inline-flex; align-items: center; gap: 7px;
      border: 1.5px solid transparent;
      border-radius: 999px; padding: 8px 18px; font-size: 0.8rem; font-weight: 700;
      margin-right: 0; cursor: pointer; transition: all 0.15s;
    }
    .dt-buttons .dt-button svg { flex-shrink: 0; }
    .dt-btn-excel { background: #E3F3EA !important; border-color: #A9DABF !important; color: #1F7A4D !important; }
    .dt-btn-excel:hover { background: #CFEBDB !important; border-color: #7FC79E !important; transform: translateY(-1px); }
    .dt-btn-pdf { background: #FCE8E6 !important; border-color: #F3B4AE !important; color: #C13A2E !important; }
    .dt-btn-pdf:hover { background: #FAD6D2 !important; border-color: #EC968D !important; transform: translateY(-1px); }
    .dt-btn-csv { background: #E4EDFC !important; border-color: #AFC7F2 !important; color: #2E5CB8 !important; }
    .dt-btn-csv:hover { background: #D1E0FA !important; border-color: #8FADEC !important; transform: translateY(-1px); }
    [data-theme="dark"] .dt-btn-excel { background: rgba(34,167,101,0.16) !important; border-color: rgba(34,167,101,0.4) !important; color: #6FDCA0 !important; }
    [data-theme="dark"] .dt-btn-excel:hover { background: rgba(34,167,101,0.26) !important; }
    [data-theme="dark"] .dt-btn-pdf { background: rgba(224,90,79,0.16) !important; border-color: rgba(224,90,79,0.4) !important; color: #F3948A !important; }
    [data-theme="dark"] .dt-btn-pdf:hover { background: rgba(224,90,79,0.26) !important; }
    [data-theme="dark"] .dt-btn-csv { background: rgba(74,124,224,0.16) !important; border-color: rgba(74,124,224,0.4) !important; color: #9CB8F2 !important; }
    [data-theme="dark"] .dt-btn-csv:hover { background: rgba(74,124,224,0.26) !important; }

    /* Tabla: encabezado, franjas alternas y hover consistentes con el resto del panel */
    table.dataTable thead th {
      border-bottom: 1px solid var(--border) !important;
      background: var(--bg-3); color: var(--text-2);
    }
    table.dataTable > tbody > tr > td { border-top: 1px solid var(--border) !important; }
    table.dataTable > tbody > tr.odd  > td { background: var(--bg-2); }
    table.dataTable > tbody > tr.even > td { background: var(--bg-3); }
    table.dataTable > tbody > tr:hover > td { background: var(--blue-soft) !important; }
    .dt-info { color: var(--text-3); font-size: 0.8rem; }
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
      <div class="nav-user-menu" style="position:relative;">
        <button type="button" onclick="toggleMenuUsuario(event)" style="display:flex;align-items:center;gap:8px;background:none;border:none;padding:0;cursor:pointer;">
          <div class="nav-avatar">
            <svg viewBox="0 0 24 24" style="width:17px;height:17px;stroke:white;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;">
              <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <span style="font-size:0.82rem;font-weight:600;color:var(--text-2);">{{ session('usuario_nombre', '') }}</span>
        </button>
        <div id="menu-usuario" style="display:none;position:absolute;right:0;top:calc(100% + 8px);min-width:200px;background:var(--bg-2);border:1px solid var(--border);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,0.14);z-index:500;overflow:hidden;">
          <button type="button" onclick="abrirModalPassword()" style="display:flex;align-items:center;gap:8px;width:100%;padding:11px 14px;background:none;border:none;text-align:left;font-family:var(--font-b);font-size:0.85rem;color:var(--text-1);cursor:pointer;">
            <svg viewBox="0 0 24 24" style="width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0;">
              <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
            </svg>
            Cambiar contraseña
          </button>
        </div>
      </div>
    <a href="{{ route('logout') }}" class="btn-logout">Salir</a>
      @stack('topbar-acciones')
    </div>
  </header>
  @show

  {{-- ── MODAL: cambiar contraseña del admin ── --}}
  <div id="modal-password-overlay" style="display:{{ $errors->has('password_actual') || $errors->has('password_nuevo') ? 'flex' : 'none' }};position:fixed;inset:0;z-index:4000;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;padding:20px;" onclick="if(event.target===this) cerrarModalPassword()">
    <div style="background:var(--bg-2);border-radius:14px;max-width:400px;width:100%;padding:24px;">
      <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:18px;">Cambiar contraseña</div>
      <form action="{{ route('admin.cuenta.password.update') }}" method="POST" id="form-cambiar-password">
        @csrf
        @method('PUT')

        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:0.76rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:6px;">Contraseña actual</label>
          <div style="position:relative;">
            <input type="password" id="pw-actual" name="password_actual" style="width:100%;padding:10px 42px 10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-3);color:var(--text-1);outline:none;">
            <button type="button" onclick="toggleModalPass('pw-actual', this)" title="Ver contraseña" tabindex="-1" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;color:var(--text-3);display:flex;align-items:center;">
              <svg viewBox="0 0 24 24" style="width:17px;height:17px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('password_actual')<div style="color:#EF4444;font-size:0.76rem;margin-top:5px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:0.76rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:6px;">Nueva contraseña</label>
          <div style="position:relative;">
            <input type="password" id="pw-nuevo" name="password_nuevo" minlength="8" style="width:100%;padding:10px 42px 10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-3);color:var(--text-1);outline:none;">
            <button type="button" onclick="toggleModalPass('pw-nuevo', this)" title="Ver contraseña" tabindex="-1" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;color:var(--text-3);display:flex;align-items:center;">
              <svg viewBox="0 0 24 24" style="width:17px;height:17px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('password_nuevo')<div style="color:#EF4444;font-size:0.76rem;margin-top:5px;">{{ $message }}</div>@enderror
          <div id="pw-nuevo-hint" style="font-size:0.74rem;color:var(--text-3);margin-top:5px;">Mínimo 8 caracteres, con mayúscula, minúscula, número y símbolo (ej: Ejemplo123$).</div>
        </div>

        <div style="margin-bottom:20px;">
          <label style="display:block;font-size:0.76rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:6px;">Confirmar nueva contraseña</label>
          <div style="position:relative;">
            <input type="password" id="pw-confirmar" name="password_nuevo_confirmation" minlength="8" style="width:100%;padding:10px 42px 10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-3);color:var(--text-1);outline:none;">
            <button type="button" onclick="toggleModalPass('pw-confirmar', this)" title="Ver contraseña" tabindex="-1" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;color:var(--text-3);display:flex;align-items:center;">
              <svg viewBox="0 0 24 24" style="width:17px;height:17px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <div id="pw-confirmar-error" style="display:none;color:#EF4444;font-size:0.76rem;margin-top:5px;">Las contraseñas no coinciden.</div>
        </div>

        <div style="display:flex;gap:10px;">
          <button type="button" onclick="cerrarModalPassword()" class="btn-secondary" style="flex:1;justify-content:center;">Cancelar</button>
          <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">Guardar</button>
        </div>
      </form>
    </div>
  </div>

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
        <div style="display:flex;gap:16px;align-items:center;padding:16px;border:1.5px solid var(--border);border-radius:12px;background:var(--bg-3);max-width:100%;box-sizing:border-box;overflow:hidden;">
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

<script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>

{{-- DataTables + exportación (Excel/PDF/CSV) — el build del CDN requiere jQuery global --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.12/build/pdfmake.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.12/build/vfs_fonts.js"></script>
<script>
  // Config compartida para inicializar DataTables en las tablas del panel admin.
  // Uso: crearDataTable('#miTabla', { ordenarDesde: 0 })
  const DT_IDIOMA_ES = 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json';

  // Íconos SVG propios (no dependen de una fuente externa como Font Awesome,
  // así se ven siempre igual sin importar el navegador) — uno distinto por
  // formato, no solo el color, para que se distingan de un vistazo.
  const DT_ICONO_EXCEL = '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>';
  const DT_ICONO_PDF = '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/><line x1="9" y1="18" x2="13" y2="18"/></svg>';
  const DT_ICONO_CSV = '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><line x1="8" y1="21" x2="10" y2="21"/></svg>';

  function crearDataTable(selector, opciones = {}) {
    const tabla = document.querySelector(selector);
    if (!tabla || typeof DataTable === 'undefined') return null;
    return new DataTable(selector, Object.assign({
      language: { url: DT_IDIOMA_ES },
      pageLength: 10,
      // Botones de exportar a la izquierda, buscador a la derecha, misma fila.
      layout: { topStart: 'buttons', topEnd: 'search' },
      // Sin esto, DataTables reordena todo por la primera columna (código)
      // apenas carga, descartando el orden que ya manda el servidor (más
      // reciente/nuevo primero) — con order:[] se respeta tal cual llega.
      order: [],
      buttons: [
        { extend: 'excelHtml5', text: DT_ICONO_EXCEL + ' Excel', className: 'dt-button dt-btn-excel' },
        { extend: 'pdfHtml5', text: DT_ICONO_PDF + ' PDF', className: 'dt-button dt-btn-pdf', orientation: 'landscape' },
        { extend: 'csvHtml5', text: DT_ICONO_CSV + ' CSV', className: 'dt-button dt-btn-csv' },
      ],
      columnDefs: [{ orderable: false, targets: -1 }],
    }, opciones));
  }
</script>
<script>
  // ── Notificaciones flash (éxito / error / validación) vía iziToast
  document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
      iziToast.success({ title: 'Listo', message: @json(session('success')), position: 'topRight', timeout: 5000, close: true });
    @endif
    @if(session('error'))
      iziToast.error({ title: 'Error', message: @json(session('error')), position: 'topRight', timeout: 6000, close: true });
    @endif
    @if($errors->any())
      iziToast.error({ title: 'Revisa lo siguiente', message: @json(implode(' · ', $errors->all())), position: 'topRight', timeout: 7000, close: true });
    @endif
  });

  function toggleMenuUsuario(e) {
    e.stopPropagation();
    const menu = document.getElementById('menu-usuario');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
  }
  document.addEventListener('click', function (e) {
    const menu = document.getElementById('menu-usuario');
    if (menu && menu.style.display === 'block' && !e.target.closest('.nav-user-menu')) {
      menu.style.display = 'none';
    }
  });

  function abrirModalPassword() {
    document.getElementById('menu-usuario').style.display = 'none';
    document.getElementById('modal-password-overlay').style.display = 'flex';
  }
  function cerrarModalPassword() {
    document.getElementById('modal-password-overlay').style.display = 'none';
  }
  function toggleModalPass(inputId, btn) {
    const inp = document.getElementById(inputId);
    const mostrar = inp.type === 'password';
    inp.type = mostrar ? 'text' : 'password';
    btn.querySelector('svg').innerHTML = mostrar
      ? '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
      : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
  }

  document.getElementById('form-cambiar-password')?.addEventListener('submit', function (e) {
    const nuevo = document.getElementById('pw-nuevo');
    const confirmar = document.getElementById('pw-confirmar');
    const hint = document.getElementById('pw-nuevo-hint');
    const errorConfirmar = document.getElementById('pw-confirmar-error');
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/;

    let valido = true;

    if (!passwordRegex.test(nuevo.value)) {
      hint.style.color = '#EF4444';
      valido = false;
    } else {
      hint.style.color = 'var(--text-3)';
    }

    if (confirmar.value !== nuevo.value) {
      errorConfirmar.style.display = 'block';
      valido = false;
    } else {
      errorConfirmar.style.display = 'none';
    }

    if (!valido) {
      e.preventDefault();
    }
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') cerrarModalPassword();
  });

  document.addEventListener('DOMContentLoaded', function () {
    @if(session('whatsapp_url'))
      iziToast.success({
        title: 'WhatsApp listo',
        message: 'El mensaje está listo para enviar.',
        position: 'topRight',
        timeout: false,
        close: true,
        buttons: [
          ['<button>Abrir WhatsApp</button>', function (instance, toast) {
            window.open(@json(session('whatsapp_url')), '_blank');
            instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
          }],
        ],
      });
    @endif
  });

  // ── Confirmaciones vía iziToast en vez de confirm() nativo.
  // Cualquier <form data-confirm="mensaje"> pide confirmación antes de enviarse.
  document.addEventListener('submit', function (event) {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm') || form.dataset.confirmado) {
      return;
    }
    event.preventDefault();
    iziToast.question({
      timeout: false,
      close: false,
      overlay: true,
      displayMode: 'once',
      id: 'confirmacion',
      zindex: 999,
      title: 'Confirmar',
      message: form.getAttribute('data-confirm'),
      position: 'center',
      buttons: [
        ['<button><b>Sí, continuar</b></button>', function (instance, toast) {
          instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
          form.dataset.confirmado = '1';
          form.submit();
        }, true],
        ['<button>Cancelar</button>', function (instance, toast) {
          instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
        }],
      ],
    });
  });
</script>

<style>
  .global-loader-overlay {
    position: fixed; inset: 0; z-index: 99999;
    background: rgba(255,255,255,.55);
    display: none; align-items: center; justify-content: center;
  }
  .global-loader-overlay.visible { display: flex; }
  .global-loader-spinner {
    width: 42px; height: 42px; border-radius: 50%;
    border: 4px solid rgba(0,0,0,.12);
    border-top-color: var(--accent, #FF5A3C);
    animation: global-loader-spin .7s linear infinite;
  }
  @keyframes global-loader-spin { to { transform: rotate(360deg); } }
  @media (prefers-color-scheme: dark) {
    .global-loader-overlay { background: rgba(20,20,20,.55); }
    .global-loader-spinner { border-color: rgba(255,255,255,.15); border-top-color: var(--accent, #FF7A5C); }
  }
</style>
<div class="global-loader-overlay" id="global-loader"><div class="global-loader-spinner"></div></div>
<script>
  (function () {
    const loader = document.getElementById('global-loader');
    if (!loader) return;
    function mostrarLoader() { loader.classList.add('visible'); }
    document.addEventListener('submit', function (event) {
      const form = event.target;
      if (!(form instanceof HTMLFormElement)) return;
      if (form.hasAttribute('data-confirm') && !form.dataset.confirmado) return;
      // El chequeo de defaultPrevented se difiere con setTimeout para que corra
      // DESPUÉS de cualquier otro listener 'submit' registrado en la página (sin
      // importar el orden en que se hayan registrado) — así, si algún validador
      // o un envío por fetch() cancela el submit, el loader nunca llega a
      // mostrarse. Mostrarlo antes de saberlo lo dejaría girando para siempre,
      // porque solo se oculta con el evento 'pageshow' de una navegación real.
      setTimeout(function () {
        if (!event.defaultPrevented) mostrarLoader();
      }, 0);
    });
    document.addEventListener('click', function (event) {
      const link = event.target.closest('a[href]');
      if (!link || event.defaultPrevented || event.ctrlKey || event.metaKey || event.shiftKey) return;
      const href = link.getAttribute('href');
      if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.target === '_blank' || link.hasAttribute('download')) return;
      mostrarLoader();
    });
    window.addEventListener('pageshow', function () { loader.classList.remove('visible'); });
  })();
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
    <div class="lightbox-contenido" onclick="event.stopPropagation()">
      <img id="lightbox-img" src="" alt="Vista ampliada">
      <div class="lightbox-zoom-controles">
        <button type="button" onclick="alejarLightbox()" aria-label="Alejar">&minus;</button>
        <button type="button" onclick="acercarLightbox()" aria-label="Acercar">&plus;</button>
      </div>
    </div>
  </div>
  <style>
    .lightbox-overlay {
      display: none; position: fixed; inset: 0; z-index: 3000;
      background: rgba(0,0,0,0.85);
      align-items: center; justify-content: center;
      padding: 40px; cursor: zoom-out;
      overflow: auto;
    }
    .lightbox-overlay.open { display: flex; }
    .lightbox-contenido {
      display: flex; flex-direction: row; align-items: center; gap: 120px;
      max-width: 92vw; max-height: 92vh;
    }
    .lightbox-overlay img {
      max-width: 80vw; max-height: 92vh; border-radius: 12px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.5);
      cursor: default;
      transition: transform 0.15s;
    }
    .lightbox-cerrar {
      position: absolute; top: 20px; right: 28px;
      background: rgba(255,255,255,0.12); border: none; color: white;
      width: 40px; height: 40px; border-radius: 50%; font-size: 1.6rem; line-height: 1;
      cursor: pointer; display: flex; align-items: center; justify-content: center;
      transition: background 0.15s;
    }
    .lightbox-cerrar:hover { background: rgba(255,255,255,0.25); }
    .lightbox-zoom-controles {
      display: flex; flex-direction: column; gap: 10px; flex-shrink: 0;
    }
    .lightbox-zoom-controles button {
      background: rgba(255,255,255,0.12); border: none; color: white;
      width: 40px; height: 40px; border-radius: 50%; font-size: 1.4rem; line-height: 1;
      cursor: pointer; display: flex; align-items: center; justify-content: center;
      transition: background 0.15s;
    }
    .lightbox-zoom-controles button:hover { background: rgba(255,255,255,0.25); }
  </style>
  <script>
    let lightboxEscala = 1;
    function abrirLightbox(src) {
      if (!src) return;
      lightboxEscala = 1;
      const img = document.getElementById('lightbox-img');
      img.src = src;
      img.style.transform = 'scale(1)';
      document.getElementById('lightbox-overlay').classList.add('open');
    }
    function cerrarLightbox() {
      document.getElementById('lightbox-overlay').classList.remove('open');
      document.getElementById('lightbox-img').src = '';
    }
    function acercarLightbox() {
      lightboxEscala = Math.min(lightboxEscala + 0.5, 4);
      document.getElementById('lightbox-img').style.transform = `scale(${lightboxEscala})`;
    }
    function alejarLightbox() {
      lightboxEscala = Math.max(lightboxEscala - 0.5, 1);
      document.getElementById('lightbox-img').style.transform = `scale(${lightboxEscala})`;
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarLightbox(); });
  </script>

</body>
</html>