<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('titulo', 'Leo José')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/css/leojoma-overrides.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --bg:           #F7F7F3;
      --bg-2:         #FFFFFF;
      --bg-3:         #F0F1EB;
      --border:       #E4E4DD;
      --border-2:     #D2D2C6;
      --text-1:       #16181A;
      --text-2:       #53565B;
      --text-3:       #90928C;
      --blue:         #16A34A;
      --blue-h:       #12813B;
      --blue-soft:    #E7F8ED;
      --blue-border:  #B8E9C6;
      --blue-light:   #34C468;
      --red-50:       #FEF2F2;
      --red-400:      #F87171;
      --red-500:      #EF4444;
      --ink:          #14161A;
      --shadow-sm:    0 1px 3px rgba(20,22,26,0.08);
      --shadow-md:    0 8px 24px rgba(20,22,26,0.1);
      --shadow-lg:    0 20px 50px rgba(20,22,26,0.16);
      --radius:       14px;
      --font-d:       'Playfair Display', serif;
      --font-b:       'Poppins', sans-serif;
    }
    html, body { height: 100%; }
    body {
      font-family: var(--font-b);
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1.1fr 1fr;
      color: var(--text-1);
    }

    /* ── PANEL IZQUIERDO ── */
    .left {
      background: var(--ink);
      position: relative;
      display: flex; flex-direction: column;
      justify-content: space-between;
      padding: 48px 52px;
      overflow: hidden;
    }
    .left::before {
      content: '';
      position: absolute; inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 110% 110%, rgba(22,163,74,0.28) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at -10% -10%, rgba(52,196,104,0.12) 0%, transparent 55%);
    }
    .left::after {
      content: '';
      position: absolute; inset: 0;
      background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
      background-size: 28px 28px;
    }
    .left-top, .left-mid, .left-bot { position: relative; z-index: 1; }
    .left-mid { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 40px 0; }

    .logo { display: block; }
    .logo img { display: block; height: 44px; width: auto; }

    .left-tag {
      display: inline-flex; align-items: center; gap: 6px;
      background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
      border-radius: 20px; padding: 5px 12px;
      font-size: 0.72rem; font-weight: 600; color: var(--blue-light);
      letter-spacing: 0.06em; text-transform: uppercase;
      margin-bottom: 20px; width: fit-content;
    }
    .left-tag span { width: 6px; height: 6px; border-radius: 50%; background: var(--blue-light); animation: blink 2s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

    .left-h { font-family: var(--font-d); font-size: 2.4rem; font-weight: 700; color: white; line-height: 1.1; letter-spacing: -0.02em; margin-bottom: 16px; }
    .left-h em { font-style: normal; color: var(--blue-light); }
    .left-p { font-size: 0.92rem; color: rgba(255,255,255,0.55); line-height: 1.75; max-width: 300px; }

    .benefits { margin-top: 32px; display: flex; flex-direction: column; gap: 12px; }
    .benefit-item { display: flex; align-items: center; gap: 12px; }
    .benefit-icon { width: 28px; height: 28px; border-radius: 8px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .benefit-icon svg { width: 14px; height: 14px; stroke: var(--blue-light); fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .benefit-text { font-size: 0.85rem; color: rgba(255,255,255,0.7); }

    .deco { position: absolute; bottom: -30px; right: -20px; opacity: 0.06; pointer-events: none; }
    .deco svg { width: 260px; height: 260px; }

    /* ── PANEL DERECHO ── */
    .right {
      display: flex; align-items: center; justify-content: center;
      padding: 40px 48px;
      background:
        linear-gradient(rgba(250,248,245,0.92), rgba(250,248,245,0.92)),
        url('{{ asset('images/fondo.png') }}') center / cover no-repeat;
      overflow-y: auto;
    }
    .card {
      width: 100%; max-width: 400px;
      background: var(--bg-2); border-radius: 20px;
      padding: 36px 32px;
      box-shadow: var(--shadow-lg);
      border: 1px solid var(--border);
      animation: slideup 0.5s cubic-bezier(.16,1,.3,1) both;
    }
    @keyframes slideup { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

    .card-icon {
      width: 48px; height: 48px; border-radius: 13px;
      background: var(--blue-soft); border: 1px solid var(--blue-border);
      display: flex; align-items: center; justify-content: center; margin-bottom: 20px;
    }
    .card-icon svg { width: 24px; height: 24px; stroke: var(--blue); fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

    .card h2 { font-family: var(--font-d); font-size: 1.45rem; font-weight: 700; color: var(--text-1); letter-spacing: -0.02em; margin-bottom: 6px; }
    .card p  { font-size: 0.83rem; color: var(--text-3); line-height: 1.6; margin-bottom: 24px; }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    .field { margin-bottom: 16px; }
    .field label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-2); letter-spacing: 0.03em; text-transform: uppercase; margin-bottom: 6px; }
    .field input {
      width: 100%; padding: 11px 14px;
      border: 1.5px solid var(--border); border-radius: 10px;
      font-family: var(--font-b); font-size: 0.9rem; color: var(--text-1);
      background: var(--bg-2); outline: none;
      transition: border-color 0.18s, box-shadow 0.18s;
    }
    .field input:focus { border-color: var(--border-2); box-shadow: 0 0 0 3px rgba(20,22,26,0.05); }
    .field input.is-error { border-color: var(--red-400); background: var(--red-50); }
    .field-error { font-size: 0.76rem; color: var(--red-500); margin-top: 5px; font-weight: 500; }
    .field-hint { font-size: 0.76rem; color: var(--text-3); margin-top: 5px; }

    .input-wrap { position: relative; }
    .input-wrap input { padding-right: 42px; }
    .toggle-btn {
      position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
      background: none; border: none; cursor: pointer; padding: 4px;
      color: var(--text-3); display: flex; align-items: center;
      transition: color 0.18s;
    }
    .toggle-btn:hover { color: var(--blue); }
    .toggle-btn svg { width: 17px; height: 17px; stroke: currentColor; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

    .spinner {
      position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
      width: 16px; height: 16px; border-radius: 50%;
      border: 2px solid var(--border-2); border-top-color: var(--blue);
      animation: spin 0.7s linear infinite; display: none;
    }
    @keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }

    .btn {
      width: 100%; padding: 13px;
      background: var(--blue); color: white; border: none; border-radius: 10px;
      font-family: var(--font-d); font-size: 0.95rem; font-weight: 700;
      cursor: pointer; transition: all 0.2s;
      box-shadow: 0 4px 14px var(--blue-shadow, rgba(22,163,74,0.3));
      display: flex; align-items: center; justify-content: center; gap: 8px;
      margin-top: 8px;
    }
    .btn svg { width: 17px; height: 17px; stroke: white; fill: none; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
    .btn:hover { background: var(--blue-h); transform: translateY(-1px); }

    .login-link { text-align: center; font-size: 0.83rem; color: var(--text-3); margin-top: 20px; }
    .login-link a { color: var(--blue); font-weight: 600; text-decoration: none; }
    .login-link a:hover { text-decoration: underline; }
    .back-link { display: block; text-align: center; margin-top: 18px; font-size: 0.82rem; color: var(--text-3); text-decoration: none; transition: color 0.2s; }
    .back-link:hover { color: var(--blue); }
    .resend { text-align: center; margin-top: 16px; font-size: 0.83rem; color: var(--text-3); }
    .resend button { background: none; border: none; color: var(--blue); font-weight: 600; cursor: pointer; font-size: 0.83rem; font-family: var(--font-b); padding: 0; }
    .resend button:hover { text-decoration: underline; }

    /* chip de correo verificado */
    .email-chip {
      display: inline-flex; align-items: center; gap: 7px;
      background: var(--blue-soft); border: 1px solid var(--blue-border);
      border-radius: 20px; padding: 5px 12px;
      font-size: 0.78rem; font-weight: 500; color: var(--blue-h);
      margin-bottom: 22px;
    }
    .email-chip svg { width: 13px; height: 13px; stroke: var(--blue); fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }

    /* código de verificación (6 dígitos) */
    .codigo-input {
      width: 100%; padding: 14px;
      border: 1.5px solid var(--border); border-radius: 10px;
      font-family: var(--font-d); font-size: 1.6rem; font-weight: 700; color: var(--text-1);
      background: var(--bg-2); outline: none;
      text-align: center; letter-spacing: 0.5em;
      transition: border-color 0.18s, box-shadow 0.18s;
    }
    .codigo-input::placeholder { color: var(--border-2); letter-spacing: 0.5em; }
    .codigo-input:focus { border-color: var(--border-2); box-shadow: 0 0 0 3px rgba(20,22,26,0.05); }
    .codigo-input.is-error { border-color: var(--red-400); background: var(--red-50); }

    /* recordarme */
    .remember { display: flex; align-items: center; gap: 8px; font-size: 0.83rem; color: var(--text-2); margin-bottom: 20px; cursor: pointer; user-select: none; }
    .remember input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--blue); cursor: pointer; flex-shrink: 0; }

    /* fuerza de contraseña */
    .strength-bar { display: flex; gap: 4px; margin-top: 8px; }
    .strength-bar span { flex: 1; height: 3px; border-radius: 2px; background: var(--border); transition: background 0.3s; }
    .strength-label { font-size: 0.73rem; color: var(--text-3); margin-top: 4px; }

    /* usuario detectado */
    .user-card {
      background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.13);
      border-radius: 14px; padding: 14px 18px;
      display: flex; align-items: center; gap: 14px;
      margin-top: 28px;
    }
    .user-avatar {
      width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
      background: linear-gradient(135deg, var(--blue-light), white);
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-d); font-weight: 800; font-size: 1.1rem;
      color: var(--ink);
    }
    .user-info strong { display: block; font-size: 0.88rem; font-weight: 600; color: white; }
    .user-info span { font-size: 0.78rem; color: rgba(255,255,255,0.45); }

    /* indicador de pasos */
    .steps { display: flex; flex-direction: column; gap: 0; margin-top: 32px; }
    .step-row { display: flex; align-items: flex-start; gap: 14px; }
    .step-col { display: flex; flex-direction: column; align-items: center; }
    .step-dot {
      width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-d); font-weight: 700; font-size: 0.78rem;
    }
    .step-dot.done { background: var(--blue-light); color: white; font-size: 1rem; }
    .step-dot.on  { background: white; color: var(--ink); }
    .step-dot.off { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.35); }
    .step-line { width: 1px; height: 22px; background: rgba(255,255,255,0.12); margin: 3px 0; }
    .step-label { padding-top: 5px; }
    .step-label strong { display: block; font-size: 0.85rem; font-weight: 600; color: white; margin-bottom: 1px; }
    .step-label span  { font-size: 0.76rem; color: rgba(255,255,255,0.4); }
    .step-label.done strong { color: rgba(255,255,255,0.45); text-decoration: line-through; }
    .step-label.off strong { color: rgba(255,255,255,0.35); }

    @media (max-width: 760px) {
      body { grid-template-columns: 1fr; }
      .left { display: none; }
      .right { padding: 32px 20px; }
    }

  </style>

  @stack('estilos')
</head>
<body>

<!-- IZQUIERDA -->
<div class="left">
  <div class="left-top">
    <a class="logo" href="{{ route('inicio') }}">
      <img src="{{ asset('images/logo.png') }}" alt="Leo José">
    </a>
  </div>

  <div class="left-mid">
    @yield('left')
  </div>

  <div class="deco">
    <svg viewBox="0 0 200 200">
      <path d="M60 30 L20 60 L35 75 L50 65 L50 170 L150 170 L150 65 L165 75 L180 60 L140 30 Q120 20 100 22 Q80 20 60 30Z" fill="white"/>
    </svg>
  </div>
</div>

<!-- DERECHA -->
<div class="right">
  <div class="card">
    @yield('card')
  </div>
</div>

@yield('modales')

<script>
  function togglePass(inputId, btn) {
    const inp = document.getElementById(inputId);
    const mostrar = inp.type === 'password';
    inp.type = mostrar ? 'text' : 'password';
    btn.querySelector('svg').innerHTML = mostrar
      ? '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
      : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
      iziToast.success({ title: 'Listo', message: @json(session('success')), position: 'topRight', timeout: 5000, close: true });
    @endif
    @if(session('info'))
      iziToast.info({ title: 'Aviso', message: @json(session('info')), position: 'topRight', timeout: 5000, close: true });
    @endif
    @if(session('error'))
      iziToast.error({ title: 'Error', message: @json(session('error')), position: 'topRight', timeout: 6000, close: true });
    @endif
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
      mostrarLoader();
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

</body>
</html>
