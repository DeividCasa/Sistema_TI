<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contraseña — Leo José</title>
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

    /* ─── IZQUIERDA ─── */
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

    /* usuario detectado */
    .user-card {
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.13);
      border-radius: 14px; padding: 14px 18px;
      display: flex; align-items: center; gap: 14px;
      margin-top: 28px; backdrop-filter: blur(6px);
    }
    .user-avatar {
      width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
      background: linear-gradient(135deg, var(--blue-400), white);
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-d); font-weight: 800; font-size: 1.1rem;
      color: var(--blue-900);
    }
    .user-info strong { display: block; font-size: 0.88rem; font-weight: 600; color: white; }
    .user-info span { font-size: 0.78rem; color: rgba(255,255,255,0.45); }

    /* pasos */
    .steps { display: flex; flex-direction: column; gap: 0; margin-top: 32px; }
    .step-row { display: flex; align-items: flex-start; gap: 14px; }
    .step-col { display: flex; flex-direction: column; align-items: center; }
    .step-dot {
      width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-d); font-weight: 700; font-size: 0.78rem;
    }
    .step-dot.done {
      background: var(--green-500); color: white; font-size: 1rem;
    }
    .step-dot.on  { background: white; color: var(--blue-800); }
    .step-line { width: 1px; height: 22px; background: rgba(255,255,255,0.12); margin: 3px 0; }
    .step-label { padding-top: 5px; }
    .step-label strong { display: block; font-size: 0.85rem; font-weight: 600; color: white; margin-bottom: 1px; }
    .step-label span  { font-size: 0.76rem; color: rgba(255,255,255,0.4); }
    .step-label.done strong { color: rgba(255,255,255,0.45); text-decoration: line-through; }

    .deco-jersey { position: absolute; bottom: -30px; right: -20px; opacity: 0.06; z-index: 0; pointer-events: none; }
    .deco-jersey svg { width: 260px; height: 260px; }

    /* ─── DERECHA ─── */
    .right {
      display: flex; align-items: center; justify-content: center;
      padding: 48px 52px;
      background:
        linear-gradient(rgba(248,250,252,0.55), rgba(248,250,252,0.72)),
        url('{{ asset('images/fondo.png') }}') center / cover no-repeat;
    }

    .card {
      width: 100%; max-width: 360px;
      background: white; border-radius: 20px;
      padding: 40px 36px;
      box-shadow: 0 8px 40px rgba(30,58,138,0.08), 0 1px 3px rgba(0,0,0,0.04);
      border: 1px solid var(--gray-100);
      animation: slideup 0.5s cubic-bezier(.16,1,.3,1) both;
    }
    @keyframes slideup { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }

    /* chip del correo verificado */
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

    /* campo */
    .field { margin-bottom: 18px; }
    .field label { display: block; font-size: 0.78rem; font-weight: 600; color: var(--gray-500); letter-spacing: 0.03em; text-transform: uppercase; margin-bottom: 7px; }
    .input-wrap { position: relative; }
    .input-wrap .ico-left {
      position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
      width: 17px; height: 17px; stroke: var(--gray-400); fill: none;
      stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
      pointer-events: none;
    }
    .toggle-btn {
      position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
      background: none; border: none; cursor: pointer; padding: 4px;
      color: var(--gray-400); display: flex; align-items: center;
      transition: color 0.18s;
    }
    .toggle-btn:hover { color: var(--blue-600); }
    .toggle-btn svg { width: 17px; height: 17px; stroke: currentColor; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

    .input-wrap input {
      width: 100%;
      padding: 12px 40px 12px 40px;
      border: 1.5px solid var(--gray-200); border-radius: 10px;
      font-family: var(--font-b); font-size: 0.93rem; color: var(--gray-800);
      background: white; outline: none;
      transition: border-color 0.18s, box-shadow 0.18s;
      letter-spacing: 0.05em;
    }
    .input-wrap input::placeholder { color: var(--gray-400); letter-spacing: 0; }
    .input-wrap input:focus { border-color: var(--blue-400); box-shadow: 0 0 0 3px rgba(96,165,250,0.18); }
    .input-wrap input.is-error { border-color: var(--red-400); background: var(--red-50); box-shadow: 0 0 0 3px rgba(248,113,113,0.15); }

    /* fuerza contraseña */
    .strength-bar { display: flex; gap: 4px; margin-top: 8px; }
    .strength-bar span {
      flex: 1; height: 3px; border-radius: 2px;
      background: var(--gray-200); transition: background 0.3s;
    }
    .strength-label { font-size: 0.73rem; color: var(--gray-400); margin-top: 4px; }

    .err { display: flex; align-items: center; gap: 5px; margin-top: 6px; font-size: 0.78rem; color: var(--red-500); font-weight: 500; }
    .err::before { content: '!'; width: 14px; height: 14px; border-radius: 50%; background: var(--red-500); color: white; font-size: 0.65rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    /* recordar */
    .remember {
      display: flex; align-items: center; gap: 8px;
      font-size: 0.83rem; color: var(--gray-500);
      margin-bottom: 20px; cursor: pointer;
      user-select: none;
    }
    .remember input[type="checkbox"] {
      width: 16px; height: 16px; accent-color: var(--blue-600);
      cursor: pointer; flex-shrink: 0;
      padding: 0; border: none; background: none; box-shadow: none;
    }

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
    <div class="left-tag"><span></span>Casi listo</div>
    <h1 class="left-h">Un paso<br>más y<br><em>entras</em></h1>
    <p class="left-p">Tu correo fue verificado correctamente. Solo falta tu contraseña para acceder.</p>

    <!-- usuario detectado (cuando conectes la BD aquí irá el nombre real) -->
    <div class="user-card">
      <div class="user-avatar">U</div>
      <div class="user-info">
        <strong>Usuario</strong>
        <span>Cuenta verificada ✓</span>
      </div>
    </div>
  </div>

  <div class="left-bot">
    <div class="steps">
      <div class="step-row">
        <div class="step-col">
          <div class="step-dot done">✓</div>
          <div class="step-line"></div>
        </div>
        <div class="step-label done">
          <strong>Correo electrónico</strong>
          <span>Verificado correctamente</span>
        </div>
      </div>
      <div class="step-row">
        <div class="step-col">
          <div class="step-dot on">2</div>
        </div>
        <div class="step-label">
          <strong>Contraseña</strong>
          <span>Ingresa tu clave de acceso</span>
        </div>
      </div>
    </div>
  </div>

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
      <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
    </div>

    <!-- chip correo verificado -->
    <div class="email-chip">
      <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
      correo verificado
    </div>

    <h2>Tu contraseña</h2>
    <p>Paso 2 de 2 — Ingresa tu contraseña para acceder a tu cuenta.</p>

    <form action="{{ route('login.verificar-contrasena') }}" method="POST">
      @csrf
      <div class="field">
        <label for="password">Contraseña</label>
        <div class="input-wrap">
          <svg class="ico-left" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="••••••••"
            class="{{ $errors->has('password') ? 'is-error' : '' }}"
            autofocus
            autocomplete="current-password"
            oninput="checkStrength(this.value)"
          >
          <button type="button" class="toggle-btn" onclick="togglePass()" title="Ver contraseña">
            <svg id="eye-svg" viewBox="0 0 24 24">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>

        <!-- barra de fuerza -->
        <div class="strength-bar">
          <span id="s1"></span>
          <span id="s2"></span>
          <span id="s3"></span>
          <span id="s4"></span>
        </div>
        <div class="strength-label" id="s-label"></div>

        @error('password')
          <div class="err">{{ $message }}</div>
        @enderror
        <div class="err" id="password-client-err" style="display:none;">Mínimo 6 caracteres.</div>
      </div>

      <label class="remember">
        <input type="checkbox" name="remember">
        Recordarme en este dispositivo
      </label>

      <button type="submit" class="btn">
        ingresar
        <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </button>
    </form>

    <a href="{{ route('login.paso1') }}" class="back">← Usar otro correo</a>

  </div>
</div>

<script>
  // Toggle ver/ocultar contraseña
  let visible = false;
  function togglePass() {
    visible = !visible;
    const inp = document.getElementById('password');
    inp.type = visible ? 'text' : 'password';
    document.getElementById('eye-svg').innerHTML = visible
      ? '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
      : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
  }

  // Barra de fuerza de contraseña
  function checkStrength(val) {
    const bars   = [s1, s2, s3, s4];
    const labels = ['', 'Débil', 'Regular', 'Buena', 'Fuerte'];
    const colors = ['', '#EF4444', '#F59E0B', '#3B82F6', '#22C55E'];
    let score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    bars.forEach((b, i) => {
      b.style.background = i < score ? colors[score] : '#E2E8F0';
    });
    document.getElementById('s-label').textContent = val.length ? labels[score] : '';
    document.getElementById('s-label').style.color  = colors[score];
  }

  // Validación en vivo: longitud mínima antes de enviar
  const passwordInput = document.getElementById('password');
  const passwordErr = document.getElementById('password-client-err');

  function validarPassword() {
    const valido = passwordInput.value.length >= 6;
    passwordInput.classList.toggle('is-error', passwordInput.value.length > 0 && !valido);
    passwordErr.style.display = (passwordInput.value.length > 0 && !valido) ? 'flex' : 'none';
    return valido;
  }

  passwordInput.addEventListener('input', validarPassword);
  passwordInput.addEventListener('blur', validarPassword);

  passwordInput.closest('form').addEventListener('submit', function (e) {
    if (!validarPassword()) {
      e.preventDefault();
      passwordInput.focus();
    }
  });
</script>

</body>
</html>