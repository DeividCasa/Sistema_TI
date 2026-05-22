<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creaciones Leo José — Uniformes Deportivos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --blue-50:  #EFF6FF;
      --blue-100: #DBEAFE;
      --blue-200: #BFDBFE;
      --blue-400: #60A5FA;
      --blue-500: #3B82F6;
      --blue-600: #2563EB;
      --blue-700: #1D4ED8;
      --blue-800: #1E40AF;
      --blue-900: #1E3A8A;
      --gray-50:  #F8FAFC;
      --gray-100: #F1F5F9;
      --gray-200: #E2E8F0;
      --gray-400: #94A3B8;
      --gray-600: #475569;
      --gray-800: #1E293B;
      --white: #FFFFFF;
      --font-display: 'Outfit', sans-serif;
      --font-body: 'DM Sans', sans-serif;
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: var(--font-body);
      background: var(--white);
      color: var(--gray-800);
      overflow-x: hidden;
    }

    /* ── NAV ── */
    nav {
      position: fixed; top: 0; left: 0; right: 0; z-index: 100;
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 5%;
      height: 68px;
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--gray-200);
    }
    .nav-logo {
      font-family: var(--font-display);
      font-weight: 800;
      font-size: 1.15rem;
      color: var(--blue-700);
      letter-spacing: -0.02em;
      display: flex; align-items: center; gap: 8px;
    }
    .nav-logo span {
      display: inline-block;
      width: 32px; height: 32px;
      background: var(--blue-600);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
    }
    .nav-logo span svg { width: 18px; height: 18px; fill: white; }
    .nav-links {
      display: flex; gap: 32px; list-style: none;
    }
    .nav-links a {
      font-size: 0.88rem; font-weight: 500;
      color: var(--gray-600);
      text-decoration: none;
      transition: color 0.2s;
    }
    .nav-links a:hover { color: var(--blue-600); }
    .nav-cta {
      display: flex; gap: 10px; align-items: center;
    }
    .btn-outline {
      padding: 8px 20px;
      border: 1.5px solid var(--blue-600);
      border-radius: 8px;
      font-family: var(--font-body);
      font-size: 0.85rem; font-weight: 500;
      color: var(--blue-600);
      background: transparent;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-outline:hover { background: var(--blue-50); }
    .btn-primary {
      padding: 8px 22px;
      border: none;
      border-radius: 8px;
      font-family: var(--font-body);
      font-size: 0.85rem; font-weight: 600;
      color: white;
      background: var(--blue-600);
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s;
      box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }
    .btn-primary:hover { background: var(--blue-700); box-shadow: 0 4px 16px rgba(37,99,235,0.35); }

    /* ── HERO ── */
    .hero {
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      padding: 68px 5% 0;
      gap: 40px;
      background: linear-gradient(135deg, var(--white) 0%, var(--blue-50) 100%);
      position: relative;
      overflow: hidden;
    }
    .hero::before {
      content: '';
      position: absolute;
      width: 600px; height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(37,99,235,0.08) 0%, transparent 70%);
      top: -100px; right: -100px;
      pointer-events: none;
    }
    .hero::after {
      content: '';
      position: absolute;
      width: 300px; height: 300px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(96,165,250,0.1) 0%, transparent 70%);
      bottom: 80px; left: 5%;
      pointer-events: none;
    }
    .hero-content { position: relative; z-index: 1; }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--blue-100);
      color: var(--blue-700);
      font-size: 0.78rem; font-weight: 600;
      padding: 5px 12px;
      border-radius: 20px;
      margin-bottom: 24px;
      letter-spacing: 0.02em;
      text-transform: uppercase;
    }
    .hero-badge::before {
      content: '';
      width: 6px; height: 6px;
      border-radius: 50%;
      background: var(--blue-500);
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.6; transform: scale(1.4); }
    }
    .hero h1 {
      font-family: var(--font-display);
      font-size: clamp(2.4rem, 4vw, 3.6rem);
      font-weight: 800;
      line-height: 1.1;
      letter-spacing: -0.03em;
      color: var(--gray-800);
      margin-bottom: 20px;
    }
    .hero h1 .accent {
      color: var(--blue-600);
      position: relative;
    }
    .hero h1 .accent::after {
      content: '';
      position: absolute;
      bottom: 2px; left: 0; right: 0;
      height: 3px;
      background: var(--blue-400);
      border-radius: 2px;
      opacity: 0.5;
    }
    .hero-desc {
      font-size: 1.05rem;
      color: var(--gray-600);
      line-height: 1.7;
      max-width: 440px;
      margin-bottom: 36px;
    }
    .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 48px; }
    .btn-hero {
      padding: 14px 28px;
      border-radius: 10px;
      font-family: var(--font-body);
      font-size: 0.95rem; font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.25s;
      border: none;
    }
    .btn-hero-primary {
      background: var(--blue-600);
      color: white;
      box-shadow: 0 4px 20px rgba(37,99,235,0.3);
    }
    .btn-hero-primary:hover { background: var(--blue-700); transform: translateY(-1px); box-shadow: 0 6px 24px rgba(37,99,235,0.4); }
    .btn-hero-secondary {
      background: white;
      color: var(--gray-800);
      border: 1.5px solid var(--gray-200);
    }
    .btn-hero-secondary:hover { border-color: var(--blue-300); color: var(--blue-600); transform: translateY(-1px); }

    .hero-stats {
      display: flex; gap: 32px;
    }
    .stat { display: flex; flex-direction: column; }
    .stat-num {
      font-family: var(--font-display);
      font-size: 1.6rem; font-weight: 800;
      color: var(--blue-700);
      line-height: 1;
    }
    .stat-label {
      font-size: 0.8rem; color: var(--gray-400);
      margin-top: 3px; font-weight: 400;
    }

    /* ── HERO VISUAL ── */
    .hero-visual {
      position: relative; z-index: 1;
      display: flex; align-items: center; justify-content: center;
    }
    .jersey-card {
      background: white;
      border-radius: 24px;
      padding: 40px 36px;
      box-shadow: 0 24px 80px rgba(30,64,175,0.12), 0 4px 16px rgba(0,0,0,0.06);
      width: 340px;
      position: relative;
    }
    .jersey-card::before {
      content: '';
      position: absolute;
      inset: -1px;
      border-radius: 25px;
      background: linear-gradient(135deg, rgba(37,99,235,0.2), transparent 60%);
      pointer-events: none;
    }
    .jersey-svg-wrap {
      display: flex; justify-content: center; margin-bottom: 24px;
    }
    .jersey-svg-wrap svg {
      width: 180px; height: 180px;
      filter: drop-shadow(0 8px 24px rgba(37,99,235,0.2));
      animation: float 4s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
    }
    .jersey-info { text-align: center; }
    .jersey-info h3 {
      font-family: var(--font-display);
      font-weight: 700; font-size: 1.05rem;
      color: var(--gray-800); margin-bottom: 4px;
    }
    .jersey-info p { font-size: 0.82rem; color: var(--gray-400); }
    .jersey-colors {
      display: flex; justify-content: center; gap: 8px; margin-top: 16px;
    }
    .color-dot {
      width: 22px; height: 22px; border-radius: 50%;
      border: 2px solid var(--gray-200);
      cursor: pointer; transition: transform 0.2s, border-color 0.2s;
    }
    .color-dot:hover { transform: scale(1.2); border-color: var(--blue-400); }
    .color-dot.active { border-color: var(--blue-600); border-width: 3px; }

    /* Floating badges */
    .badge-float {
      position: absolute;
      background: white;
      border-radius: 12px;
      padding: 10px 14px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      display: flex; align-items: center; gap: 8px;
      font-size: 0.8rem; font-weight: 500;
      color: var(--gray-800);
      white-space: nowrap;
    }
    .badge-float .icon {
      width: 28px; height: 28px; border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px;
    }
    .badge-ia { top: 30px; right: -30px; animation: fadein 0.8s 0.3s both; }
    .badge-ia .icon { background: var(--blue-100); }
    .badge-3d { bottom: 50px; left: -40px; animation: fadein 0.8s 0.6s both; }
    .badge-3d .icon { background: #EEF2FF; }
    @keyframes fadein { from { opacity:0; transform: translateY(10px); } to { opacity:1; transform: translateY(0); } }

    /* ── SECCIÓN CÓMO FUNCIONA ── */
    .section {
      padding: 96px 5%;
    }
    .section-label {
      font-size: 0.78rem; font-weight: 700; letter-spacing: 0.08em;
      text-transform: uppercase; color: var(--blue-500);
      margin-bottom: 12px;
    }
    .section-title {
      font-family: var(--font-display);
      font-size: clamp(1.8rem, 3vw, 2.6rem);
      font-weight: 800; line-height: 1.15;
      letter-spacing: -0.025em;
      color: var(--gray-800);
      max-width: 520px;
      margin-bottom: 16px;
    }
    .section-desc {
      font-size: 1rem; color: var(--gray-600);
      line-height: 1.7; max-width: 480px; margin-bottom: 56px;
    }

    .steps-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 24px;
    }
    .step-card {
      background: var(--gray-50);
      border-radius: 16px;
      padding: 28px 24px;
      border: 1px solid var(--gray-200);
      position: relative;
      transition: all 0.25s;
      cursor: default;
    }
    .step-card:hover {
      border-color: var(--blue-200);
      background: var(--blue-50);
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(37,99,235,0.08);
    }
    .step-num {
      font-family: var(--font-display);
      font-size: 2.5rem; font-weight: 800;
      color: var(--blue-100);
      line-height: 1;
      margin-bottom: 12px;
    }
    .step-card:hover .step-num { color: var(--blue-200); }
    .step-icon {
      width: 44px; height: 44px;
      background: var(--blue-600);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 16px;
    }
    .step-icon svg { width: 22px; height: 22px; fill: white; }
    .step-card h4 {
      font-family: var(--font-display);
      font-weight: 700; font-size: 1rem;
      color: var(--gray-800); margin-bottom: 8px;
    }
    .step-card p { font-size: 0.875rem; color: var(--gray-600); line-height: 1.6; }

    /* ── FEATURES ── */
    .features-section {
      padding: 80px 5%;
      background: var(--gray-800);
      position: relative; overflow: hidden;
    }
    .features-section::before {
      content: '';
      position: absolute;
      width: 500px; height: 500px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(37,99,235,0.2) 0%, transparent 70%);
      top: -150px; right: -100px;
      pointer-events: none;
    }
    .features-section .section-label { color: var(--blue-400); }
    .features-section .section-title { color: white; }
    .features-section .section-desc { color: #94A3B8; }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }
    .feature-card {
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 16px;
      padding: 28px 24px;
      transition: all 0.25s;
    }
    .feature-card:hover {
      background: rgba(37,99,235,0.1);
      border-color: rgba(37,99,235,0.4);
      transform: translateY(-3px);
    }
    .feature-icon {
      width: 48px; height: 48px;
      background: var(--blue-600);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 18px;
    }
    .feature-icon svg { width: 24px; height: 24px; fill: white; }
    .feature-card h4 {
      font-family: var(--font-display);
      font-weight: 700; font-size: 1rem;
      color: white; margin-bottom: 8px;
    }
    .feature-card p { font-size: 0.875rem; color: #94A3B8; line-height: 1.65; }

    /* ── CTA FINAL ── */
    .cta-section {
      padding: 96px 5%;
      text-align: center;
      background: linear-gradient(135deg, var(--blue-50) 0%, white 100%);
      position: relative; overflow: hidden;
    }
    .cta-section::before {
      content: '';
      position: absolute;
      width: 400px; height: 400px; border-radius: 50%;
      background: radial-gradient(circle, rgba(37,99,235,0.07) 0%, transparent 70%);
      top: 50%; left: 50%; transform: translate(-50%, -50%);
    }
    .cta-section .section-title { max-width: 600px; margin: 0 auto 16px; }
    .cta-section .section-desc { margin: 0 auto 40px; max-width: 460px; }
    .cta-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }

    /* ── FOOTER ── */
    footer {
      background: var(--gray-800);
      color: #94A3B8;
      padding: 40px 5%;
      display: flex; justify-content: space-between; align-items: center;
      flex-wrap: wrap; gap: 16px;
    }
    footer .nav-logo { color: white; }
    footer p { font-size: 0.82rem; }

    /* ── ANIMACIONES DE ENTRADA ── */
    .reveal {
      opacity: 0; transform: translateY(24px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    @media (max-width: 768px) {
      .hero { grid-template-columns: 1fr; padding-top: 80px; }
      .hero-visual { display: none; }
      .features-grid { grid-template-columns: 1fr; }
      .nav-links { display: none; }
      footer { flex-direction: column; text-align: center; }
    }
  </style>
</head>
<body>

  <!-- NAV -->
  <nav>
    <div class="nav-logo">
      <span>
        <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
      </span>
      Leo José
    </div>
    <ul class="nav-links">
      <li><a href="#como-funciona">Cómo funciona</a></li>
      <li><a href="#caracteristicas">Características</a></li>
      <li><a href="#contacto">Contacto</a></li>
    </ul>
    <div class="nav-cta">
      <a href="{{ route('login.paso1') }}" class="btn-outline">Iniciar sesión</a>
      <a href="/register" class="btn-primary">Crear cuenta</a>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-content reveal">
      <div class="hero-badge">Tecnología 3D + Inteligencia Artificial</div>
      <h1>
        Diseña tu uniforme<br>
        <span class="accent">deportivo</span><br>
        como nunca antes
      </h1>
      <p class="hero-desc">
        Personaliza camisetas, shorts y conjuntos deportivos en tiempo real con nuestro configurador 3D. ¿Sin inspiración? Deja que la IA diseñe por ti.
      </p>
      <div class="hero-actions">
        <a href="/register" class="btn-hero btn-hero-primary">Diseñar ahora →</a>
        <a href="#como-funciona" class="btn-hero btn-hero-secondary">Ver cómo funciona</a>
      </div>
      <div class="hero-stats">
        <div class="stat">
          <span class="stat-num">3D</span>
          <span class="stat-label">Visualización inmersiva</span>
        </div>
        <div class="stat">
          <span class="stat-num">IA</span>
          <span class="stat-label">Diseño automático</span>
        </div>
        <div class="stat">
          <span class="stat-num">100%</span>
          <span class="stat-label">Personalizable</span>
        </div>
      </div>
    </div>

    <div class="hero-visual">
      <div class="jersey-card">
        <!-- Camiseta SVG ilustrativa -->
        <div class="jersey-svg-wrap">
          <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <!-- Cuerpo de la camiseta -->
            <path d="M60 30 L20 60 L35 75 L50 65 L50 170 L150 170 L150 65 L165 75 L180 60 L140 30 Q120 20 100 22 Q80 20 60 30Z"
              fill="#2563EB" stroke="#1D4ED8" stroke-width="2"/>
            <!-- Cuello -->
            <path d="M80 30 Q100 42 120 30" fill="none" stroke="#1D4ED8" stroke-width="3" stroke-linecap="round"/>
            <!-- Línea decorativa manga izq -->
            <path d="M35 75 L50 65" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
            <!-- Línea decorativa manga der -->
            <path d="M165 75 L150 65" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
            <!-- Franja central -->
            <rect x="88" y="50" width="24" height="120" rx="4" fill="rgba(255,255,255,0.12)"/>
            <!-- Número -->
            <text x="100" y="125" text-anchor="middle" font-family="Outfit, sans-serif"
              font-size="40" font-weight="800" fill="white" opacity="0.9">10</text>
            <!-- Logo área -->
            <circle cx="70" cy="75" r="14" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
            <text x="70" y="80" text-anchor="middle" font-size="11" fill="white" font-family="Outfit, sans-serif" font-weight="700">LJ</text>
          </svg>
        </div>
        <div class="jersey-info">
          <h3>Camiseta deportiva</h3>
          <p>Vista previa en tiempo real</p>
          <div class="jersey-colors">
            <div class="color-dot active" style="background:#2563EB" title="Azul"></div>
            <div class="color-dot" style="background:#DC2626" title="Rojo"></div>
            <div class="color-dot" style="background:#16A34A" title="Verde"></div>
            <div class="color-dot" style="background:#D97706" title="Dorado"></div>
            <div class="color-dot" style="background:#111827" title="Negro"></div>
          </div>
        </div>

        <!-- Badge IA -->
        <div class="badge-float badge-ia">
          <div class="icon">✨</div>
          <div>
            <div style="font-size:0.75rem;font-weight:600;color:#1e293b">Diseño con IA</div>
            <div style="font-size:0.7rem;color:#94a3b8">Generado en segundos</div>
          </div>
        </div>

        <!-- Badge 3D -->
        <div class="badge-float badge-3d">
          <div class="icon">🎮</div>
          <div>
            <div style="font-size:0.75rem;font-weight:600;color:#1e293b">Vista 3D</div>
            <div style="font-size:0.7rem;color:#94a3b8">Gira y personaliza</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CÓMO FUNCIONA -->
  <section class="section" id="como-funciona">
    <div class="section-label">Proceso</div>
    <h2 class="section-title reveal">Tu uniforme en 4 pasos simples</h2>
    <p class="section-desc reveal">Desde el diseño hasta la entrega, controlamos todo desde el sistema para que tú solo te preocupes de jugar.</p>

    <div class="steps-grid">
      <div class="step-card reveal">
        <div class="step-num">01</div>
        <div class="step-icon">
          <svg viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        </div>
        <h4>Elige o diseña</h4>
        <p>Selecciona una plantilla base, diseña desde cero en el configurador 3D o pídele a la IA que genere el diseño por ti.</p>
      </div>
      <div class="step-card reveal" style="transition-delay:0.1s">
        <div class="step-num">02</div>
        <div class="step-icon">
          <svg viewBox="0 0 24 24"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
        </div>
        <h4>Confirma el pedido</h4>
        <p>Especifica tallas, cantidades y datos de entrega. El sistema calcula automáticamente el precio total y el adelanto.</p>
      </div>
      <div class="step-card reveal" style="transition-delay:0.2s">
        <div class="step-num">03</div>
        <div class="step-icon">
          <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
        </div>
        <h4>Sube el comprobante</h4>
        <p>Realiza la transferencia del 50% como adelanto y sube la foto del comprobante. El admin lo verifica y da inicio a la producción.</p>
      </div>
      <div class="step-card reveal" style="transition-delay:0.3s">
        <div class="step-num">04</div>
        <div class="step-icon">
          <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </div>
        <h4>Sigue tu pedido</h4>
        <p>Monitorea en tiempo real el estado de tu uniforme: en producción, listo para entrega o entregado.</p>
      </div>
    </div>
  </section>

  <!-- CARACTERÍSTICAS -->
  <section class="features-section" id="caracteristicas">
    <div class="section-label">Características</div>
    <h2 class="section-title reveal">Todo lo que necesitas en un solo sistema</h2>
    <p class="section-desc reveal">Tecnología moderna al servicio de un negocio local.</p>

    <div class="features-grid">
      <div class="feature-card reveal">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
        </div>
        <h4>Configurador 3D</h4>
        <p>Visualiza tu uniforme en tres dimensiones, rota el modelo y personaliza colores, número y escudo en tiempo real con Three.js.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:0.1s">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
        </div>
        <h4>Generación con IA</h4>
        <p>Describe tu idea en palabras y la inteligencia artificial genera automáticamente el diseño usando Gemini y Pollinations.ai.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:0.2s">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <h4>Plantillas base</h4>
        <p>Elige entre modelos prediseñados de camisetas, shorts y conjuntos deportivos creados por el equipo de Leo José.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:0.3s">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
        </div>
        <h4>Pago con comprobante</h4>
        <p>Transfiere el 50% como adelanto y sube la foto del comprobante directamente en el sistema. Sin pasarelas de pago complicadas.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:0.4s">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <h4>Panel administrador</h4>
        <p>Gestiona pedidos, verifica comprobantes, cambia estados y genera reportes desde un dashboard seguro con login propio.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:0.5s">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </div>
        <h4>Seguimiento en vivo</h4>
        <p>El cliente ve en todo momento el estado de su pedido: recibido, en producción, listo o entregado con historial completo.</p>
      </div>
    </div>
  </section>

  <!-- CTA FINAL -->
  <section class="cta-section" id="contacto">
    <div class="section-label">Empieza hoy</div>
    <h2 class="section-title reveal">¿Listo para diseñar el uniforme de tu equipo?</h2>
    <p class="section-desc reveal">Crea tu cuenta gratis, diseña en minutos y realiza tu pedido sin complicaciones.</p>
    <div class="cta-actions reveal">
      <a href="/register" class="btn-hero btn-hero-primary">Crear cuenta gratis →</a>
      <a href="{{ route('login.paso1') }}" class="btn-hero btn-hero-secondary">Ya tengo cuenta</a>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="nav-logo">
      <span>
        <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
      </span>
      Leo José
    </div>
    <p>© 2026 Creaciones Leo José de Salcedo. Sistema de pedidos personalizados.</p>
    <p style="font-size:0.78rem">Desarrollado con Laravel + Three.js + IA</p>
  </footer>

  <script>
    // Cambio de color en la camiseta demo
    document.querySelectorAll('.color-dot').forEach(dot => {
      dot.addEventListener('click', () => {
        document.querySelectorAll('.color-dot').forEach(d => d.classList.remove('active'));
        dot.classList.add('active');
        const color = getComputedStyle(dot).backgroundColor;
        const paths = document.querySelectorAll('.jersey-svg-wrap svg path[fill="#2563EB"], .jersey-svg-wrap svg path[fill]');
        document.querySelector('.jersey-svg-wrap svg path').setAttribute('fill', rgbToHex(color));
      });
    });

    function rgbToHex(rgb) {
      const m = rgb.match(/\d+/g);
      if (!m) return '#2563EB';
      return '#' + m.slice(0,3).map(n => parseInt(n).toString(16).padStart(2,'0')).join('');
    }

    // Reveal on scroll
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
  </script>
</body>
</html>