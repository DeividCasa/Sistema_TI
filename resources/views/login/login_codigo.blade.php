<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Código de acceso — Leo José</title>
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
      --red-400: #F87171; --red-500: #EF4444; --red-50: #FEF2F2;
      --green-500: #22C55E; --green-50: #F0FDF4; --green-200: #BBF7D0;
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
    .logo { display: flex; align-items: center; gap: 10px; }
    .logo-box {
      width: 38px; height: 38px; border-radius: 10px;
      background: rgba(255,255,255,0.12);
      border: 1px solid rgba(255,255,255,0.18);
      display: flex; align-items: center; justify-content: center;
    }
    .logo-box svg { width: 20px; height: 20px; stroke: white; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .logo-name { font-family: var(--font-d); font-weight: 800; font-size: 1.05rem; color: white; letter-spacing: -0.02em; }
    .left-mid { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 40px 0; }
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
    .left-h { font-family: var(--font-d); font-size: 2.6rem; font-weight: 800; color: white; line-height: 1.1; letter-spacing: -0.035em; margin-bottom: 16px; }
    .left-h em { font-style: normal; color: var(--blue-400); }
    .left-p { font-size: 0.92rem; color: rgba(255,255,255,0.55); line-height: 1.75; max-width: 300px; }
    .steps { display: flex; flex-direction: column; gap: 0; margin-top: 32px; }
    .step-row { display: flex; align-items: flex-start; gap: 14px; }
    .step-col { display: flex; flex-direction: column; align-items: center; }
    .step-dot {
      width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-d); font-weight: 700; font-size: 0.78rem;
    }
    .step-dot.done { background: var(--green-500); color: white; font-size: 1rem; }
    .step-dot.on  { background: white; color: var(--blue-800); }
    .step-line { width: 1px; height: 22px; background: rgba(255,255,255,0.12); margin: 3px 0; }
    .step-label { padding-top: 5px; }
    .step-label strong { display: block; font-size: 0.85rem; font-weight: 600; color: white; margin-bottom: 1px; }
    .step-label span  { font-size: 0.76rem; color: rgba(255,255,255,0.4); }
    .step-label.done strong { color: rgba(255,255,255,0.45); text-decoration: line-through; }
    .deco-jersey { position: absolute; bottom: -30px; right: -20px; opacity: 0.06; z-index: 0; pointer-events: none; }
    .deco-jersey svg { width: 260px; height: 260px; }
    .right {
      display: flex; align-items: center; justify-content: center;
      padding: 48px 52px;
      background:
        linear-gradient(rgba(248,250,252,0.55), rgba(248,250,252,0.72)),
        url('{{ asset('images/fondo.png') }}') center / cover no-repeat;
    }
    .card {
      width: 100%; max-width: 380px;
      background: white; border-radius: 20px;
      padding: 40px 36px;
      box-shadow: 0 8px 40px rgba(30,58,138,0.08), 0 1px 3px rgba(0,0,0,0.04);
      border: 1px solid var(--gray-100);
      animation: slideup 0.5s cubic-bezier(.16,1,.3,1) both;
    }
    @keyframes slideup { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    .email-chip {
      display: inline-flex; align-items: center; gap: 7px;
      background: var(--green-50); border: 1px solid var(--green-200);
      border-radius: 20px; padding: 5px 12px;
      font-size: 0.78rem; font-weight: 500; color: #15803D;
      margin-bottom: 22px;
    }
    .email-chip svg { width: 13px; height: 13px; stroke: #16A34A; fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    .card-icon {
      width: 52px; height: 52px; border-radius: 14px;
      background: var(--blue-50); border: 1px solid var(--blue-100);
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 22px;
    }
    .card-icon svg { width: 26px; height: 26px; stroke: var(--blue-600); fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .card h2 { font-family: var(--font-d); font-size: 1.55rem; font-weight: 800; color: var(--gray-800); letter-spacing: -0.025em; margin-bottom: 6px; }
    .card p  { font-size: 0.85rem; color: var(--gray-400); line-height: 1.6; margin-bottom: 28px; }
    .field { margin-bottom: 18px; }
    .field label { display: block; font-size: 0.78rem; font-weight: 600; color: var(--gray-500); letter-spacing: 0.03em; text-transform: uppercase; margin-bottom: 10px; text-align: center; }
    .codigo-input {
      width: 100%;
      padding: 14px;
      border: 1.5px solid var(--gray-200); border-radius: 10px;
      font-family: var(--font-d); font-size: 1.7rem; font-weight: 700; color: var(--gray-800);
      background: white; outline: none;
      text-align: center; letter-spacing: 0.5em;
      transition: border-color 0.18s, box-shadow 0.18s;
    }
    .codigo-input::placeholder { color: var(--gray-200); letter-spacing: 0.5em; }
    .codigo-input:focus { border-color: var(--blue-400); box-shadow: 0 0 0 3px rgba(96,165,250,0.18); }
    .codigo-input.is-error { border-color: var(--red-400); background: var(--red-50); box-shadow: 0 0 0 3px rgba(248,113,113,0.15); }
    .err { display: flex; align-items: center; gap: 5px; margin-top: 8px; font-size: 0.78rem; color: var(--red-500); font-weight: 500; justify-content: center; }
    .err::before { content: '!'; width: 14px; height: 14px; border-radius: 50%; background: var(--red-500); color: white; font-size: 0.65rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .info { display: flex; align-items: center; gap: 5px; margin: 0 0 18px; font-size: 0.8rem; color: #15803D; font-weight: 500; justify-content: center; background: var(--green-50); border: 1px solid var(--green-200); border-radius: 8px; padding: 8px; }
    .btn {
      width: 100%; padding: 13px;
      background: var(--blue-600); color: white;
      border: none; border-radius: 10px;
      font-family: var(--font-d); font-size: 0.95rem; font-weight: 700;
      cursor: pointer; transition: all 0.2s;
      box-shadow: 0 4px 14px rgba(37,99,235,0.3);
      display: flex; align-items: center; justify-content: center; gap: 8px;
      letter-spacing: -0.01em; text-decoration: none;
    }
    .btn svg { width: 17px; height: 17px; stroke: white; fill: none; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
    .btn:hover { background: var(--blue-700); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,0.38); }
    .btn:active { transform: translateY(0); }
    .resend { text-align: center; margin-top: 18px; font-size: 0.83rem; color: var(--gray-400); }
    .resend button { background: none; border: none; color: var(--blue-600); font-weight: 600; cursor: pointer; font-size: 0.83rem; font-family: var(--font-b); padding: 0; }
    .resend button:hover { text-decoration: underline; }
    .back { display: block; text-align: center; margin-top: 18px; font-size: 0.82rem; color: var(--gray-400); text-decoration: none; transition: color 0.2s; }
    .back:hover { color: var(--blue-600); }
    @media (max-width: 760px) {
      body { grid-template-columns: 1fr; overflow: auto; }
      .left { display: none; }
      .right { padding: 32px 20px; }
    }
  </style>
</head>
<body>

<div class="left">
  <div class="left-top">
    <div class="logo">
      <div class="logo-box"><svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5M2 17l10 5 10-5M2 12l10 5 10-5"/></svg></div>
      <span class="logo-name">Leo José</span>
    </div>
  </div>

  <div class="left-mid">
    <div class="left-tag"><span></span>Verificación de administrador</div>
    <h1 class="left-h">Confirma<br>que eres<br><em>tú</em></h1>
    <p class="left-p">Por seguridad, el acceso al panel de administración requiere un código enviado a tu correo en cada inicio de sesión.</p>
  </div>

  <div class="left-bot">
    <div class="steps">
      <div class="step-row">
        <div class="step-col"><div class="step-dot done">✓</div><div class="step-line"></div></div>
        <div class="step-label done"><strong>Correo electrónico</strong><span>Verificado correctamente</span></div>
      </div>
      <div class="step-row">
        <div class="step-col"><div class="step-dot done">✓</div><div class="step-line"></div></div>
        <div class="step-label done"><strong>Contraseña</strong><span>Verificada correctamente</span></div>
      </div>
      <div class="step-row">
        <div class="step-col"><div class="step-dot on">3</div></div>
        <div class="step-label"><strong>Código de acceso</strong><span>Revisa tu correo</span></div>
      </div>
    </div>
  </div>

  <div class="deco-jersey">
    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
      <path d="M60 30 L20 60 L35 75 L50 65 L50 170 L150 170 L150 65 L165 75 L180 60 L140 30 Q120 20 100 22 Q80 20 60 30Z" fill="white"/>
    </svg>
  </div>
</div>

<div class="right">
  <div class="card">

    <div class="card-icon">
      <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
    </div>

    <div class="email-chip">
      <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
      {{ $email }}
    </div>

    <h2>Ingresa el código</h2>
    <p>Te enviamos un código de 6 dígitos a tu correo. Ingrésalo para completar el acceso.</p>

    @if (session('info'))
      <div class="info">{{ session('info') }}</div>
    @endif

    <form action="{{ route('login.verificar-codigo') }}" method="POST">
      @csrf
      <div class="field">
        <label for="codigo">Código de acceso</label>
        <input
          type="text"
          id="codigo"
          name="codigo"
          inputmode="numeric"
          maxlength="6"
          placeholder="000000"
          class="codigo-input {{ $errors->has('codigo') ? 'is-error' : '' }}"
          autofocus
          autocomplete="one-time-code"
        >
        @error('codigo')
          <div class="err">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn">
        Verificar código
        <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </button>
    </form>

    <div class="resend">
      ¿No recibiste el código?
      <form action="{{ route('login.reenviar-codigo') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Reenviar</button>
      </form>
    </div>

    <a href="{{ route('login.paso1') }}" class="back">← Usar otro correo</a>

  </div>
</div>

<script>
  const codigoInput = document.getElementById('codigo');
  codigoInput.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 6);
  });
</script>

</body>
</html>
