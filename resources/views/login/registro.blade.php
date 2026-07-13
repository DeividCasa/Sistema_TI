<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro — Leo José</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
      --blue:      #2563EB; --blue-h:   #1D4ED8; --blue-800: #1E40AF;
      --blue-900:  #1E3A8A; --blue-50:  #EFF6FF; --blue-100: #DBEAFE;
      --blue-200:  #BFDBFE; --blue-400: #60A5FA;
      --gray-50:   #F8FAFC; --gray-100: #F1F5F9; --gray-200: #E2E8F0;
      --gray-400:  #94A3B8; --gray-500: #64748B; --gray-600: #475569; --gray-800: #1E293B;
      --red-50:    #FEF2F2; --red-400:  #F87171; --red-500:  #EF4444;
      --green-50:  #F0FDF4; --green-200:#BBF7D0; --green-500:#22C55E;
      --font-d:    'Outfit', sans-serif;
      --font-b:    'DM Sans', sans-serif;
    }
    html, body { height: 100%; }
    body {
      font-family: var(--font-b);
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1.1fr 1fr;
      background: white;
    }

    /* ── PANEL IZQUIERDO ── */
    .left {
      background: var(--blue-900);
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
        radial-gradient(ellipse 80% 60% at 110% 110%, rgba(37,99,235,0.5) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at -10% -10%, rgba(96,165,250,0.2) 0%, transparent 55%);
    }
    .left::after {
      content: '';
      position: absolute; inset: 0;
      background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
      background-size: 28px 28px;
    }
    .left-top, .left-mid, .left-bot { position: relative; z-index: 1; }
    .left-mid { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 40px 0; }

    .logo { display: flex; align-items: center; gap: 10px; }
    .logo-box {
      width: 38px; height: 38px; border-radius: 10px;
      background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);
      display: flex; align-items: center; justify-content: center;
    }
    .logo-box svg { width: 20px; height: 20px; stroke: white; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .logo-name { font-family: var(--font-d); font-weight: 800; font-size: 1.05rem; color: white; letter-spacing: -0.02em; }

    .left-tag {
      display: inline-flex; align-items: center; gap: 6px;
      background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
      border-radius: 20px; padding: 5px 12px;
      font-size: 0.72rem; font-weight: 600; color: var(--blue-200);
      letter-spacing: 0.06em; text-transform: uppercase;
      margin-bottom: 20px; width: fit-content;
    }
    .left-tag span { width: 6px; height: 6px; border-radius: 50%; background: var(--green-500); animation: blink 2s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

    .left-h { font-family: var(--font-d); font-size: 2.4rem; font-weight: 800; color: white; line-height: 1.1; letter-spacing: -0.035em; margin-bottom: 16px; }
    .left-h em { font-style: normal; color: var(--blue-400); }
    .left-p { font-size: 0.92rem; color: rgba(255,255,255,0.55); line-height: 1.75; max-width: 300px; }

    .benefits { margin-top: 32px; display: flex; flex-direction: column; gap: 12px; }
    .benefit-item { display: flex; align-items: center; gap: 12px; }
    .benefit-icon { width: 28px; height: 28px; border-radius: 8px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .benefit-icon svg { width: 14px; height: 14px; stroke: var(--blue-400); fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .benefit-text { font-size: 0.85rem; color: rgba(255,255,255,0.7); }

    .deco { position: absolute; bottom: -30px; right: -20px; opacity: 0.06; pointer-events: none; }
    .deco svg { width: 260px; height: 260px; }

    /* ── PANEL DERECHO ── */
    .right {
      display: flex; align-items: center; justify-content: center;
      padding: 40px 48px;
      background: var(--gray-50);
      overflow-y: auto;
    }

    .card {
      width: 100%; max-width: 400px;
      background: white; border-radius: 20px;
      padding: 36px 32px;
      box-shadow: 0 8px 40px rgba(30,58,138,0.08);
      border: 1px solid var(--gray-100);
      animation: slideup 0.5s cubic-bezier(.16,1,.3,1) both;
    }
    @keyframes slideup { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

    .card-icon {
      width: 48px; height: 48px; border-radius: 13px;
      background: var(--blue-50); border: 1px solid var(--blue-100);
      display: flex; align-items: center; justify-content: center; margin-bottom: 20px;
    }
    .card-icon svg { width: 24px; height: 24px; stroke: var(--blue); fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

    .card h2 { font-family: var(--font-d); font-size: 1.45rem; font-weight: 800; color: var(--gray-800); letter-spacing: -0.025em; margin-bottom: 6px; }
    .card p  { font-size: 0.83rem; color: var(--gray-400); line-height: 1.6; margin-bottom: 24px; }

    {{-- success --}}
    .alert-success {
      background: var(--green-50); border: 1px solid var(--green-200);
      color: #15803D; padding: 10px 14px; border-radius: 10px;
      font-size: 0.82rem; font-weight: 500; margin-bottom: 20px;
    }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    .field { margin-bottom: 16px; }
    .field label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--gray-500); letter-spacing: 0.03em; text-transform: uppercase; margin-bottom: 6px; }
    .field input {
      width: 100%; padding: 11px 14px;
      border: 1.5px solid var(--gray-200); border-radius: 10px;
      font-family: var(--font-b); font-size: 0.9rem; color: var(--gray-800);
      background: white; outline: none;
      transition: border-color 0.18s, box-shadow 0.18s;
    }
    .field input:focus { border-color: var(--blue-400); box-shadow: 0 0 0 3px rgba(96,165,250,0.18); }
    .field input.is-error { border-color: var(--red-400); background: var(--red-50); }
    .field-error { font-size: 0.76rem; color: var(--red-500); margin-top: 5px; font-weight: 500; }

    .btn {
      width: 100%; padding: 13px;
      background: var(--blue); color: white; border: none; border-radius: 10px;
      font-family: var(--font-d); font-size: 0.95rem; font-weight: 700;
      cursor: pointer; transition: all 0.2s;
      box-shadow: 0 4px 14px rgba(37,99,235,0.3);
      display: flex; align-items: center; justify-content: center; gap: 8px;
      margin-top: 8px;
    }
    .btn svg { width: 17px; height: 17px; stroke: white; fill: none; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
    .btn:hover { background: var(--blue-h); transform: translateY(-1px); }

    .login-link { text-align: center; font-size: 0.83rem; color: var(--gray-400); margin-top: 20px; }
    .login-link a { color: var(--blue); font-weight: 600; text-decoration: none; }
    .login-link a:hover { text-decoration: underline; }

    @media (max-width: 760px) {
      body { grid-template-columns: 1fr; }
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
    <div class="left-tag"><span></span>Únete ahora</div>
    <h1 class="left-h">Crea tu<br>cuenta<br><em>gratis</em></h1>
    <p class="left-p">Diseña uniformes deportivos únicos, haz seguimiento de tus pedidos y mucho más.</p>

    <div class="benefits">
      <div class="benefit-item">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        </div>
        <span class="benefit-text">Diseña tu uniforme en 3D</span>
      </div>
      <div class="benefit-item">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
        </div>
        <span class="benefit-text">Genera diseños con IA</span>
      </div>
      <div class="benefit-item">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <span class="benefit-text">Sigue el estado de tus pedidos</span>
      </div>
    </div>
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

    <div class="card-icon">
      <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
    </div>

    <h2>Crear cuenta</h2>
    <p>Completa el formulario para registrarte.</p>

    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('registro.store') }}" method="POST">
      @csrf

      <div class="grid-2">
        <div class="field">
          <label>Nombre</label>
          <input type="text" name="nombre" value="{{ old('nombre') }}"
            placeholder="Juan" class="{{ $errors->has('nombre') ? 'is-error' : '' }}">
          @error('nombre') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
          <label>Apellido</label>
          <input type="text" name="apellido" value="{{ old('apellido') }}"
            placeholder="Pérez" class="{{ $errors->has('apellido') ? 'is-error' : '' }}">
          @error('apellido') <div class="field-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="field">
        <label>Correo electrónico</label>
        <input type="email" name="email" value="{{ old('email') }}"
          placeholder="tucorreo@ejemplo.com" class="{{ $errors->has('email') ? 'is-error' : '' }}">
        @error('email') <div class="field-error">{{ $message }}</div> @enderror
      </div>

      <div class="grid-2">
        <div class="field">
          <label>Teléfono</label>
          <input type="text" name="telefono" value="{{ old('telefono') }}"
            placeholder="0999999999">
        </div>
        <div class="field">
          <label>Ciudad</label>
          <input type="text" name="ciudad" value="{{ old('ciudad') }}"
            placeholder="Quito">
        </div>
      </div>

      <div class="grid-2">
        <div class="field">
          <label>Contraseña</label>
          <input type="password" name="password"
            placeholder="••••••••" class="{{ $errors->has('password') ? 'is-error' : '' }}">
          @error('password') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
          <label>Confirmar</label>
          <input type="password" name="password_confirmation"
            placeholder="••••••••">
        </div>
      </div>

      <button type="submit" class="btn">
        Crear cuenta
        <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </button>
    </form>

    <div class="login-link">
      ¿Ya tienes cuenta? <a href="{{ route('login.paso1') }}">Inicia sesión</a>
    </div>

  </div>
</div>

</body>
</html>