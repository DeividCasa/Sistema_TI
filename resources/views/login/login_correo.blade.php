<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión — Leo José</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
      --blue-600: #2563EB; --blue-700: #1D4ED8; --blue-800: #1E40AF;
      --blue-900: #1E3A8A; --blue-50: #EFF6FF; --blue-100: #DBEAFE;
      --blue-200: #BFDBFE; --blue-400: #60A5FA;
      --gray-50: #F8FAFC; --gray-100: #F1F5F9; --gray-200: #E2E8F0;
      --gray-400: #94A3B8; --gray-500: #64748B; --gray-600: #475569; --gray-800: #1E293B;
      --red-400: #F87171; --red-500: #EF4444; --red-50: #FEF2F2; --red-200: #FECACA;
      --font-d: 'Outfit', sans-serif;
      --font-b: 'DM Sans', sans-serif;
    }

    html, body { height: 100%; }

    body {
      font-family: var(--font-b);
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1.1fr 1fr;
      background: white;
      overflow: hidden;
    }

    /* ─── PANEL IZQUIERDO ─── */
    .left {
      background: var(--blue-900);
      position: relative;
      display: flex; flex-direction: column;
      justify-content: space-between;
      padding: 48px 52px;
      overflow: hidden;
    }

    /* fondo decorativo */
    .left::before {
      content: '';
      position: absolute; inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 110% 110%, rgba(37,99,235,0.5) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at -10% -10%, rgba(96,165,250,0.2) 0%, transparent 55%);
    }

    /* patrón de puntos */
    .left::after {
      content: '';
      position: absolute; inset: 0;
      background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
      background-size: 28px 28px;
    }

    .left-top, .left-mid, .left-bot { position: relative; z-index: 1; }

    /* logo */
    .logo {
      display: flex; align-items: center; gap: 10px;
    }
    .logo-box {
      width: 38px; height: 38px; border-radius: 10px;
      background: rgba(255,255,255,0.12);
      border: 1px solid rgba(255,255,255,0.18);
      display: flex; align-items: center; justify-content: center;
      backdrop-filter: blur(8px);
    }
    .logo-box svg { width: 20px; height: 20px; stroke: white; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .logo-name {
      font-family: var(--font-d); font-weight: 800; font-size: 1.05rem;
      color: white; letter-spacing: -0.02em;
    }

    /* texto central */
    .left-mid { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 40px 0; }
    .left-tag {
      display: inline-flex; align-items: center; gap: 6px;
      background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
      border-radius: 20px; padding: 5px 12px;
      font-size: 0.72rem; font-weight: 600; color: var(--blue-200);
      letter-spacing: 0.06em; text-transform: uppercase;
      margin-bottom: 20px; width: fit-content;
    }
    .left-tag span { width: 6px; height: 6px; border-radius: 50%; background: var(--blue-400); animation: blink 2s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

    .left-h {
      font-family: var(--font-d); font-size: 2.6rem; font-weight: 800;
      color: white; line-height: 1.1; letter-spacing: -0.035em;
      margin-bottom: 16px;
    }
    .left-h em { font-style: normal; color: var(--blue-400); }

    .left-p {
      font-size: 0.92rem; color: rgba(255,255,255,0.55);
      line-height: 1.75; max-width: 300px;
    }

    /* pasos visuales */
    .steps {
      display: flex; flex-direction: column; gap: 0;
    }
    .step-row { display: flex; align-items: flex-start; gap: 14px; }
    .step-col { display: flex; flex-direction: column; align-items: center; }
    .step-dot {
      width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-d); font-weight: 700; font-size: 0.78rem;
    }
    .step-dot.on  { background: white; color: var(--blue-800); }
    .step-dot.off { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.35); }
    .step-line { width: 1px; height: 22px; background: rgba(255,255,255,0.12); margin: 3px 0; }
    .step-label { padding-top: 5px; }
    .step-label strong { display: block; font-size: 0.85rem; font-weight: 600; color: white; margin-bottom: 1px; }
    .step-label span  { font-size: 0.76rem; color: rgba(255,255,255,0.4); }
    .step-label.off strong { color: rgba(255,255,255,0.35); }

    /* camiseta decorativa */
    .deco-jersey {
      position: absolute; bottom: -30px; right: -20px;
      opacity: 0.06; z-index: 0;
      pointer-events: none;
    }
    .deco-jersey svg { width: 260px; height: 260px; }

    /* ─── PANEL DERECHO ─── */
    .right {
      display: flex; align-items: center; justify-content: center;
      padding: 48px 52px;
      background:
        linear-gradient(rgba(248,250,252,0.85), rgba(248,250,252,0.85)),
        url('{{ asset('images/fondo.png') }}') center / cover no-repeat;
    }

    .card {
      width: 100%; max-width: 360px;
      background: white;
      border-radius: 20px;
      padding: 40px 36px;
      box-shadow: 0 8px 40px rgba(30,58,138,0.08), 0 1px 3px rgba(0,0,0,0.04);
      border: 1px solid var(--gray-100);
      animation: slideup 0.5s cubic-bezier(.16,1,.3,1) both;
    }
    @keyframes slideup { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }

    .card-icon {
      width: 52px; height: 52px; border-radius: 14px;
      background: var(--blue-50); border: 1px solid var(--blue-100);
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 22px;
    }
    .card-icon svg { width: 26px; height: 26px; stroke: var(--blue-600); fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

    .card h2 {
      font-family: var(--font-d); font-size: 1.55rem; font-weight: 800;
      color: var(--gray-800); letter-spacing: -0.025em; margin-bottom: 6px;
    }
    .card p {
      font-size: 0.85rem; color: var(--gray-400); line-height: 1.6; margin-bottom: 28px;
    }

    /* input */
    .field { margin-bottom: 18px; }
    .field label {
      display: block; font-size: 0.78rem; font-weight: 600;
      color: var(--gray-500); letter-spacing: 0.03em;
      text-transform: uppercase; margin-bottom: 7px;
    }
    .input-wrap { position: relative; }
    .input-wrap svg {
      position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
      width: 17px; height: 17px;
      stroke: var(--gray-400); fill: none; stroke-width: 1.8;
      stroke-linecap: round; stroke-linejoin: round;
      pointer-events: none;
    }
    .input-wrap input {
      width: 100%;
      padding: 12px 14px 12px 40px;
      border: 1.5px solid var(--gray-200);
      border-radius: 10px;
      font-family: var(--font-b); font-size: 0.93rem; color: var(--gray-800);
      background: white; outline: none;
      transition: border-color 0.18s, box-shadow 0.18s;
    }
    .input-wrap input::placeholder { color: var(--gray-400); }
    .input-wrap input:focus {
      border-color: var(--blue-400);
      box-shadow: 0 0 0 3px rgba(96,165,250,0.18);
    }
    .input-wrap input.is-error {
      border-color: var(--red-400);
      background: var(--red-50);
      box-shadow: 0 0 0 3px rgba(248,113,113,0.15);
    }

    .err {
      display: flex; align-items: center; gap: 5px;
      margin-top: 6px; font-size: 0.78rem; color: var(--red-500); font-weight: 500;
    }
    .err::before { content: '!'; width: 14px; height: 14px; border-radius: 50%; background: var(--red-500); color: white; font-size: 0.65rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    /* botón */
    .btn {
      width: 100%; padding: 13px;
      background: var(--blue-600); color: white;
      border: none; border-radius: 10px;
      font-family: var(--font-d); font-size: 0.95rem; font-weight: 700;
      cursor: pointer; transition: all 0.2s;
      box-shadow: 0 4px 14px rgba(37,99,235,0.3);
      display: flex; align-items: center; justify-content: center; gap: 8px;
      letter-spacing: -0.01em;
      margin-top: 4px;
    }
    .btn svg { width: 17px; height: 17px; stroke: white; fill: none; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
    .btn:hover { background: var(--blue-700); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,0.38); }
    .btn:active { transform: translateY(0); }

    .divider {
      display: flex; align-items: center; gap: 10px;
      margin: 22px 0; color: var(--gray-400); font-size: 0.78rem;
    }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--gray-200); }

    .register {
      text-align: center; font-size: 0.83rem; color: var(--gray-400);
    }
    .register a { color: var(--blue-600); font-weight: 600; text-decoration: none; }
    .register a:hover { text-decoration: underline; }

    .back {
      display: block; text-align: center; margin-top: 18px;
      font-size: 0.82rem; color: var(--gray-400);
      text-decoration: none; transition: color 0.2s;
    }
    .back:hover { color: var(--blue-600); }

    @media (max-width: 760px) {
      body { grid-template-columns: 1fr; overflow: auto; }
      .left { display: none; }
      .right { padding: 32px 20px; }
    }
  </style>
</head>
<body>

<!-- IZQUIERDA -->
<div class="left">
  <div class="left-top">
    <div class="logo">
      <div class="logo-box">
        <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
      </div>
      <span class="logo-name">Leo José</span>
    </div>
  </div>

  <div class="left-mid">
    <div class="left-tag"><span></span>Acceso seguro</div>
    <h1 class="left-h">Diseña tu<br>uniforme<br><em>ideal</em></h1>
    <p class="left-p">Ingresa a tu cuenta para crear, personalizar y hacer seguimiento de tus pedidos deportivos.</p>
  </div>

  <div class="left-bot">
    <div class="steps">
      <div class="step-row">
        <div class="step-col">
          <div class="step-dot on">1</div>
          <div class="step-line"></div>
        </div>
        <div class="step-label">
          <strong>Correo electrónico</strong>
          <span>Verificamos que tu cuenta exista</span>
        </div>
      </div>
      <div class="step-row">
        <div class="step-col">
          <div class="step-dot off">2</div>
        </div>
        <div class="step-label off">
          <strong>Contraseña</strong>
          <span>Acceso a tu panel personal</span>
        </div>
      </div>
    </div>
  </div>

  <!-- camiseta decorativa fondo -->
  <div class="deco-jersey">
    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
      <path d="M60 30 L20 60 L35 75 L50 65 L50 170 L150 170 L150 65 L165 75 L180 60 L140 30 Q120 20 100 22 Q80 20 60 30Z" fill="white"/>
    </svg>
  </div>
</div>

<!-- DERECHA -->
<div class="right">
  <div class="card">

    <div class="card-icon">
      <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
    </div>

    <h2>Ingresa tu correo</h2>
    <p>Paso 1 de 2 — Verificamos que tengas una cuenta registrada.</p>

    <form action="{{ route('login.verificar-correo') }}" method="POST">
      @csrf
      <div class="field">
        <label for="email">Correo electrónico</label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="ejemplo@correo.com"
            value="{{ old('email') }}"
            class="{{ $errors->has('email') ? 'is-error' : '' }}"
            autofocus
            autocomplete="email"
          >
        </div>
        @error('email')
          <div class="err">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn">
        Continuar
        <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </button>

    <div class="divider">o</div>

    <div class="register">
      ¿No tienes cuenta? <a href="/registro">Regístrate gratis</a>
    </div>

    <a href="/" class="back">← Volver al inicio</a>

  </div>
</div>

</body>
</html>