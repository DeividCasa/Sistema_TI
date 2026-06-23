<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $plantilla->nombre ?? 'Crea tu diseño' }} — Editor 3D</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    *,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
    :root{
      --bg:#F1F5F9; --bg-2:#FFFFFF; --bg-3:#F8FAFC; --border:#E2E8F0; --border-2:#CBD5E1;
      --text-1:#0F172A; --text-2:#475569; --text-3:#94A3B8;
      --blue:#240677; --blue-h:#1D4ED8; --blue-soft:#EFF6FF; --blue-border:#BFDBFE; --blue-light:#60A5FA;
      --font-d:'Outfit',sans-serif; --font-b:'DM Sans',sans-serif;
      --tr:0.2s cubic-bezier(.4,0,.2,1);
      --topbar-h:54px; --rail-w:76px; --panel-w:280px; --aibar-h:230px;
    }
    [data-theme="dark"]{
      --bg:#171e2c; --bg-2:#11161f; --bg-3:#1A2235; --border:#1E2D45; --border-2:#2A3F5F;
      --text-1:#F1F5F9; --text-2:#cbd5e1; --text-3:#677b97;
      --blue-soft:rgba(37,99,235,0.14); --blue-border:rgba(37,99,235,0.3);
    }
    html,body{height:100%;overflow:hidden;background:var(--bg);color:var(--text-1);font-family:var(--font-b);}

    /* ════ TOP BAR ════ */
    .editor-topbar{
      height:var(--topbar-h); display:flex; align-items:center; gap:14px; padding:0 16px;
      background:var(--bg-2); border-bottom:1px solid var(--border);
    }
    .tb-logo{ display:flex; align-items:center; gap:8px; text-decoration:none; flex-shrink:0; }
    .tb-logo img{ width:26px; height:26px; border-radius:6px; }
    .tb-sep{ color:var(--border-2); font-size:0.85rem; }
    .tb-name{
      border:none; background:transparent; font-family:var(--font-d); font-weight:700; font-size:0.92rem;
      color:var(--text-1); padding:6px 8px; border-radius:7px; min-width:140px;
    }
    .tb-name:focus{ outline:none; background:var(--bg-3); }
    .tb-icon-btn{
      width:32px; height:32px; border-radius:8px; border:1px solid var(--border); background:var(--bg-2);
      color:var(--text-2); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all var(--tr);
    }
    .tb-icon-btn:hover{ background:var(--bg-3); color:var(--blue); }
    .tb-icon-btn:disabled{ opacity:0.35; cursor:not-allowed; }
    .tb-icon-btn:disabled:hover{ background:var(--bg-2); color:var(--text-2); }
    .tb-right{ margin-left:auto; display:flex; align-items:center; gap:10px; }
    .tb-btn{
      display:inline-flex; align-items:center; gap:7px; padding:8px 16px; border-radius:9px;
      font-family:var(--font-b); font-size:0.82rem; font-weight:600; cursor:pointer; text-decoration:none; border:1px solid transparent;
    }
    .tb-btn-ghost{ background:var(--bg-2); border-color:var(--border); color:var(--text-2); }
    .tb-btn-ghost:hover{ border-color:var(--blue-border); color:var(--blue); }
    .tb-btn-dark{ background:var(--text-1); color:var(--bg-2); }
    .tb-btn-dark:hover{ opacity:0.88; }

    /* ════ BODY LAYOUT ════ */
    .editor-body{ display:flex; height:calc(100vh - var(--topbar-h)); }

    .icon-rail{
      width:var(--rail-w); background:var(--bg-2); border-right:1px solid var(--border);
      display:flex; flex-direction:column; align-items:center; padding:14px 0; gap:4px; flex-shrink:0;
    }
    .rail-btn{
      width:60px; padding:9px 4px; border-radius:10px; border:none; background:transparent; color:var(--text-2);
      display:flex; flex-direction:column; align-items:center; gap:4px; cursor:pointer; transition:all var(--tr);
    }
    .rail-btn i{ font-size:1.05rem; }
    .rail-btn span{ font-size:0.62rem; font-weight:600; }
    .rail-btn:hover{ background:var(--bg-3); color:var(--text-1); }
    .rail-btn.active{ background:var(--blue-soft); color:var(--blue); }

    .tool-panel{
      width:var(--panel-w); background:var(--bg-2); border-right:1px solid var(--border);
      padding:18px 18px 24px; overflow-y:auto; flex-shrink:0;
      transition: margin-left var(--tr); 
    }
    .tool-panel.collapsed{ margin-left:calc(var(--panel-w) * -1); }
    .tp-title{ font-family:var(--font-d); font-weight:800; font-size:1rem; margin-bottom:4px; }
    .tp-desc{ font-size:0.78rem; color:var(--text-2); line-height:1.5; margin-bottom:18px; }

    .campo-grupo{ margin-bottom:20px; }
    .campo-label{
      display:block; font-size:0.74rem; font-weight:700; color:var(--text-2);
      text-transform:uppercase; letter-spacing:0.04em; margin-bottom:9px;
    }
    .campo-input{
      width:100%; padding:9px 12px; border:1.5px solid var(--border); border-radius:8px;
      background:var(--bg-3); color:var(--text-1); font-family:var(--font-b); font-size:0.85rem;
    }
    .campo-input:focus{ outline:none; border-color:var(--blue); }

    .chips-row{ display:flex; gap:8px; flex-wrap:wrap; }
    .chip-opcion{
      padding:8px 13px; border-radius:9px; border:1.5px solid var(--border); background:var(--bg-3);
      color:var(--text-1); font-size:0.78rem; font-weight:600; cursor:pointer; transition:all var(--tr);
      display:inline-flex; align-items:center; gap:6px;
    }
    .chip-opcion.active{ border-color:var(--blue); background:var(--blue-soft); color:var(--blue); }

    .swatch-row{ display:flex; gap:9px; flex-wrap:wrap; align-items:center; }
    .swatch{
      width:26px; height:26px; border-radius:50%; cursor:pointer; border:3px solid var(--border);
      transition:all var(--tr);
    }
    .swatch:hover{ transform:scale(1.1); }
    .swatch.active{ border-color:var(--blue); }
    .swatch-custom{
      width:26px; height:26px; border-radius:50%; border:1.5px dashed var(--border-2); cursor:pointer;
      display:flex; align-items:center; justify-content:center; color:var(--text-3); font-size:0.62rem; position:relative;
    }
    .swatch-custom input[type=color]{ position:absolute; inset:0; opacity:0; cursor:pointer; }

    .toggle-row{ display:flex; align-items:center; justify-content:space-between; padding:10px 0; }
    .toggle-row span{ font-size:0.83rem; font-weight:600; color:var(--text-1); }
    .switch{ position:relative; width:38px; height:22px; flex-shrink:0; }
    .switch input{ opacity:0; width:0; height:0; }
    .slider{ position:absolute; inset:0; background:var(--border-2); border-radius:20px; cursor:pointer; transition:all var(--tr); }
    .slider::before{ content:''; position:absolute; width:16px; height:16px; left:3px; top:3px; background:#fff; border-radius:50%; transition:all var(--tr); }
    .switch input:checked + .slider{ background:var(--blue); }
    .switch input:checked + .slider::before{ transform:translateX(16px); }

    .upload-zone{
      border:1.5px dashed var(--border-2); border-radius:10px; padding:22px 14px; text-align:center;
      cursor:pointer; color:var(--text-3); font-size:0.8rem; transition:all var(--tr);
    }
    .upload-zone:hover{ border-color:var(--blue); color:var(--blue); background:var(--blue-soft); }
    .upload-zone i{ font-size:1.4rem; display:block; margin-bottom:8px; }
    .logo-preview{ display:flex; align-items:center; gap:10px; margin-top:12px; }
    .logo-preview img{ width:42px; height:42px; object-fit:contain; border:1px solid var(--border); border-radius:8px; background:var(--bg-3); }
    .logo-preview button{ font-size:0.74rem; color:#DC2626; background:none; border:none; cursor:pointer; font-weight:600; }

    .btn-primary-full{
      width:100%; justify-content:center; display:flex; align-items:center; gap:8px;
      padding:11px 16px; border-radius:9px; background:var(--blue); color:#fff; border:none;
      font-family:var(--font-b); font-size:0.85rem; font-weight:600; cursor:pointer; margin-top:6px;
    }
    .btn-primary-full:hover{ background:var(--blue-h); }
    .btn-primary-full:disabled{ opacity:0.6; cursor:wait; }

    /* ════ CANVAS AREA ════ */
    .canvas-area{ flex:1; display:flex; flex-direction:column; position:relative; min-width:0; }
    .canvas-toolbar{
      position:absolute; top:14px; left:50%; transform:translateX(-50%); z-index:5;
      display:flex; gap:8px; background:var(--bg-2); border:1px solid var(--border); border-radius:11px;
      padding:6px; box-shadow:0 4px 14px rgba(0,0,0,0.07);
    }
    #viewer3d{ flex:1; position:relative; }
    #viewer3d canvas{ display:block; width:100%; height:100%; }

    .viewer-fab-row{
      position:absolute; bottom:18px; right:18px; display:flex; gap:8px; z-index:5;
    }
    .fab{
      width:38px; height:38px; border-radius:10px; background:var(--bg-2); border:1px solid var(--border);
      color:var(--text-2); display:flex; align-items:center; justify-content:center; cursor:pointer;
      box-shadow:0 4px 10px rgba(0,0,0,0.06); transition:all var(--tr);
    }
    .fab:hover{ color:var(--blue); border-color:var(--blue-border); }

    .viewer-hint{
      position:absolute; bottom:18px; left:18px; font-size:0.72rem; color:var(--text-3);
      background:var(--bg-2); border:1px solid var(--border); padding:6px 12px; border-radius:20px; z-index:5;
    }

    /* ════ BARRA DE IA (ABAJO) ════ */
    .ai-bar{
      position:absolute; left:0; right:0; bottom:0; height:var(--aibar-h);
      background:var(--bg-2); border-top:1px solid var(--border); box-shadow:0 -8px 24px rgba(0,0,0,0.08);
      display:flex; gap:18px; padding:18px 22px; transform:translateY(100%); transition:transform var(--tr); z-index:10;
    }
    .ai-bar.open{ transform:translateY(0); }
    .ai-bar-close{
      position:absolute; top:10px; right:14px; width:26px; height:26px; border-radius:7px; border:none;
      background:var(--bg-3); color:var(--text-2); cursor:pointer;
    }
    .ai-bar-left{ width:340px; flex-shrink:0; display:flex; flex-direction:column; }
    .ai-bar-left h3{ font-family:var(--font-d); font-size:0.95rem; font-weight:800; display:flex; align-items:center; gap:8px; margin-bottom:4px; }
    .ai-bar-left p{ font-size:0.76rem; color:var(--text-2); margin-bottom:10px; line-height:1.4; }
    .ai-bar-left textarea{
      flex:1; resize:none; border:1.5px solid var(--border); border-radius:9px; padding:10px 12px;
      font-family:var(--font-b); font-size:0.83rem; background:var(--bg-3); color:var(--text-1);
    }
    .ai-bar-left textarea:focus{ outline:none; border-color:var(--blue); }
    .ai-bar-right{
      flex:1; display:flex; align-items:center; justify-content:center; gap:16px;
      border:1.5px dashed var(--border-2); border-radius:12px; background:var(--bg-3); padding:14px; overflow:hidden;
    }
    .ai-placeholder{ text-align:center; color:var(--text-3); font-size:0.82rem; }
    .ai-placeholder i{ font-size:1.6rem; display:block; margin-bottom:8px; color:var(--border-2); }
    .ai-resultado-img{ max-height:100%; max-width:48%; border-radius:8px; border:1px solid var(--border); object-fit:contain; }
    .ai-resultado-acciones{ display:flex; flex-direction:column; gap:10px; }

    .aviso-flot{
      position:absolute; top:14px; right:14px; z-index:20; max-width:340px; padding:11px 16px; border-radius:10px;
      font-size:0.82rem; font-weight:500; box-shadow:0 6px 18px rgba(0,0,0,0.1); display:none;
    }

    @media (max-width: 980px){
      :root{ --panel-w:240px; }
      .tb-name{ min-width:90px; }
      .ai-bar{ flex-direction:column; height:auto; max-height:80vh; overflow-y:auto; }
      .ai-bar-left{ width:100%; }
    }
  </style>
</head>
<body>

  {{-- ════ TOP BAR ════ --}}
  <header class="editor-topbar">
    <a href="{{ $plantilla ? route('producto.ver', $plantilla->id) : route('cliente.inicio') }}" class="tb-logo">
      <img src="{{ asset('images/logo.png') }}" alt="">
    </a>
    <span class="tb-sep">/</span>
    <input type="text" class="tb-name" id="nombre" maxlength="150" value="{{ $plantilla->nombre ?? 'Mi diseño' }}">

    <button class="tb-icon-btn" id="btn-undo" onclick="deshacer()" title="Deshacer"><i class="fas fa-rotate-left"></i></button>
    <button class="tb-icon-btn" id="btn-redo" onclick="rehacer()" title="Rehacer"><i class="fas fa-rotate-right"></i></button>

    <div class="tb-right">
      <button type="button" class="tb-btn tb-btn-ghost" onclick="guardarDiseno()">
        <i class="fas fa-floppy-disk"></i> Guardar
      </button>
      <button type="button" class="tb-btn tb-btn-dark" onclick="guardarDiseno(true)">
        <i class="fas fa-box-open"></i> Pedir muestra
      </button>
    </div>
  </header>

  <div id="aviso-global" class="aviso-flot"></div>

  {{-- ════ CUERPO ════ --}}
  <div class="editor-body">

    {{-- RAIL DE ICONOS --}}
    <aside class="icon-rail">
      <button class="rail-btn active" data-tool="colores" onclick="seleccionarHerramienta('colores')">
        <i class="fas fa-palette"></i><span>Colores</span>
      </button>
      <button class="rail-btn" data-tool="logo" onclick="seleccionarHerramienta('logo')">
        <i class="fas fa-arrow-up-from-bracket"></i><span>Subir</span>
      </button>
      <button class="rail-btn" data-tool="texto" onclick="seleccionarHerramienta('texto')">
        <i class="fas fa-font"></i><span>Texto</span>
      </button>
      <button class="rail-btn" data-tool="ia" onclick="toggleBarraIA()">
        <i class="fas fa-wand-magic-sparkles"></i><span>IA</span>
      </button>
    </aside>

    {{-- PANEL DE HERRAMIENTA --}}
    <aside class="tool-panel" id="tool-panel">

      {{-- — PANEL COLORES — --}}
      <div id="panel-colores">
        <div class="tp-title">Colores</div>
        <div class="tp-desc">Cambia el color de cada parte de la prenda.</div>

        @if(!$plantilla)
          <div class="campo-grupo">
            <label class="campo-label">Tipo de prenda</label>
            <div class="chips-row" id="tipo-chips">
              <span class="chip-opcion active" data-tipo="camiseta" onclick="seleccionarTipo('camiseta', this)"><i class="fas fa-tshirt"></i> Camiseta</span>
              <span class="chip-opcion" data-tipo="short" onclick="seleccionarTipo('short', this)"><i class="fas fa-ruler-horizontal"></i> Short</span>
              <span class="chip-opcion" data-tipo="conjunto" onclick="seleccionarTipo('conjunto', this)"><i class="fas fa-layer-group"></i> Conjunto</span>
              <span class="chip-opcion" data-tipo="otro" onclick="seleccionarTipo('otro', this)"><i class="fas fa-shapes"></i> Otro</span>
            </div>
          </div>
        @else
          <input type="hidden" id="tipo-fijo" value="{{ $plantilla->tipo_prenda }}">
          <div class="campo-grupo">
            <span class="chip-opcion active" style="cursor:default;"><i class="fas fa-tag"></i> {{ ucfirst($plantilla->tipo_prenda) }}</span>
          </div>
        @endif

        <div class="campo-grupo" id="toggle-capucha-wrap" style="display:none;">
          <div class="toggle-row">
            <span><i class="fas fa-hat-wizard"></i> Con capucha (hoodie)</span>
            <label class="switch">
              <input type="checkbox" id="toggle-capucha" onchange="cambiarCapucha(this.checked)">
              <span class="slider"></span>
            </label>
          </div>
        </div>

        <div id="zonas-color"></div>
      </div>

      {{-- — PANEL SUBIR LOGO — --}}
      <div id="panel-logo" style="display:none;">
        <div class="tp-title">Subir imagen</div>
        <div class="tp-desc">Sube tu logo y se colocará al frente de la prenda.</div>

        <label class="upload-zone" id="upload-zone-label">
          <i class="fas fa-cloud-arrow-up"></i>
          Haz clic o arrastra tu imagen aquí (PNG, JPG)
          <input type="file" id="input-logo" accept="image/*" style="display:none;" onchange="subirLogo(event)">
        </label>

        <div class="logo-preview" id="logo-preview" style="display:none;">
          <img id="logo-preview-img" src="" alt="logo">
          <div>
            <div style="font-size:0.78rem;font-weight:600;" id="logo-preview-nombre"></div>
            <button type="button" onclick="quitarLogo()">Quitar imagen</button>
          </div>
        </div>
      </div>

      {{-- — PANEL TEXTO — --}}
      <div id="panel-texto" style="display:none;">
        <div class="tp-title">Texto</div>
        <div class="tp-desc">Agrega un texto personalizado al frente de la prenda.</div>

        <div class="campo-grupo">
          <label class="campo-label">Texto</label>
          <input type="text" id="texto" maxlength="22" placeholder="Ej: EQUIPO 10" class="campo-input" oninput="actualizarTexto()">
        </div>
        <div class="campo-grupo">
          <label class="campo-label">Color del texto</label>
          <div class="swatch-row">
            @foreach(['#FFFFFF','#0F172A','#DC2626','#F59E0B','#240677','#16A34A'] as $i => $c)
              <span class="swatch {{ $i==0 ? 'active' : '' }}" style="background:{{ $c }};" data-color="{{ $c }}" onclick="elegirColorTexto('{{ $c }}', this)"></span>
            @endforeach
            <label class="swatch-custom">
              <input type="color" id="texto-color" value="#FFFFFF" oninput="elegirColorTexto(this.value, null)">
              <i class="fas fa-plus"></i>
            </label>
          </div>
        </div>
      </div>

    </aside>

    {{-- ÁREA DEL VISOR 3D --}}
    <main class="canvas-area">
      <div id="viewer3d"></div>

      <div class="viewer-fab-row">
        <div class="fab" onclick="reiniciarVista()" title="Reiniciar vista"><i class="fas fa-arrows-rotate"></i></div>
        <div class="fab" onclick="pantallaCompleta()" title="Pantalla completa"><i class="fas fa-expand"></i></div>
      </div>
      <div class="viewer-hint"><i class="fas fa-arrows-up-down-left-right"></i> Gira la prenda · Arrastra el logo o texto para reubicarlo</div>

      {{-- BARRA DE IA (ABAJO, OCULTA POR DEFECTO) --}}
      <div class="ai-bar" id="ai-bar">
        <button class="ai-bar-close" onclick="toggleBarraIA(false)"><i class="fas fa-xmark"></i></button>

        <div class="ai-bar-left">
          <h3><i class="fas fa-wand-magic-sparkles" style="color:var(--blue);"></i> Crea tu diseño con IA</h3>
          <p>¿No puedes personalizarlo tú mismo? Descríbelo y la IA genera una imagen de referencia para tu prenda.</p>
          <textarea id="prompt-ia" placeholder="Ej: Camiseta azul marino con rayas blancas en las mangas, cuello rojo y un escudo deportivo al pecho, estilo retro."></textarea>
          <button type="button" class="btn-primary-full" id="btn-generar-ia" onclick="generarConIA()">
            <i class="fas fa-wand-magic-sparkles"></i>
            <span id="btn-generar-texto">Generar diseño con IA</span>
          </button>
        </div>

        <div class="ai-bar-right" id="ai-bar-right">
          <div class="ai-placeholder" id="ai-placeholder">
            <i class="fas fa-image"></i>
            Aquí aparecerá la imagen generada por la IA
          </div>
        </div>
      </div>
    </main>
  </div>
  {{-- ════ THREE.JS ════ --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>

  <script>
    const CSRF_TOKEN = "{{ csrf_token() }}";
    const PLANTILLA_ID = {{ $plantilla->id ?? 'null' }};
    const TIPO_FIJO = {{ $plantilla ? "'".$plantilla->tipo_prenda."'" : 'null' }};

    /* ═══════════ ESTADO GLOBAL DEL DISEÑO ═══════════ */
    let estado = {
      tipo: TIPO_FIJO || 'camiseta',
      capucha: false,
      colorA: '#240677', // cuerpo
      colorB: '#FFFFFF', // mangas / franjas / detalle
      colorC: '#16A34A', // capucha / short (conjunto)
      texto: '',
      textoColor: '#FFFFFF',
      logoDataUrl: null,
    };

    const ZONE_TO_STATE = { cuerpo:'colorA', mangas:'colorB', franjas:'colorB', detalle:'colorB', capucha:'colorC', short:'colorC' };

    const PANEL_ZONES = {
      camiseta: [ {zone:'cuerpo', label:'Color del cuerpo'}, {zone:'mangas', label:'Color de mangas y cuello'} ],
      short:    [ {zone:'cuerpo', label:'Color del short'}, {zone:'franjas', label:'Color de cintura'} ],
      conjunto: [ {zone:'cuerpo', label:'Color de camiseta'}, {zone:'mangas', label:'Color de mangas'}, {zone:'short', label:'Color del short'} ],
      otro:     [ {zone:'cuerpo', label:'Color principal'}, {zone:'detalle', label:'Color de detalles'} ],
    };

    const PRESETS = ['#240677','#0F172A','#DC2626','#16A34A','#F59E0B','#FFFFFF','#1D4ED8','#171717'];

    /* ═══════════ THREE.JS: ESCENA ═══════════ */
    let renderer, scene, camera, controls;
    let modelo3D;
    let decalTexto, decalLogo;
    const contenedor = document.getElementById('viewer3d');

    function initEscena() {
      try {
        if (typeof THREE === 'undefined') {
          throw new Error('La librería Three.js no se cargó (revisa tu conexión a internet o un bloqueador de scripts).');
        }
        if (typeof THREE.OrbitControls === 'undefined') {
          throw new Error('No se cargó el módulo OrbitControls de Three.js.');
        }

        scene = new THREE.Scene();
        // scene.add(new THREE.AxesHelper(5)); // desactivado: solo era para depurar
        camera = new THREE.PerspectiveCamera(32, contenedor.clientWidth / contenedor.clientHeight, 0.1, 100);
        camera.position.set(0,0,3);

        renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, preserveDrawingBuffer: true });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.setSize(contenedor.clientWidth || 600, contenedor.clientHeight || 500);
        contenedor.appendChild(renderer.domElement);

        scene.add(new THREE.AmbientLight(0xffffff, 0.75));
        const light1 = new THREE.DirectionalLight(0xffffff,2);
        light1.position.set(5,5,5);
        scene.add(light1);

        const light2 = new THREE.DirectionalLight(0xffffff,1);
        light2.position.set(-5,3,-5);
        scene.add(light2);
        

        controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.08;
        controls.enablePan = false;
        controls.minDistance = 3.2;
        controls.maxDistance = 9;
        controls.target.set(0, 0, 0);

        // Eventos de arrastre del logo/texto sobre la prenda 3D
        renderer.domElement.addEventListener('mousedown', iniciarArrastreDecal);
        renderer.domElement.addEventListener('mousemove', moverDecalArrastrado);
        window.addEventListener('mouseup', terminarArrastreDecal);

        renderer.domElement.addEventListener('touchstart', iniciarArrastreDecal, { passive: false });
        renderer.domElement.addEventListener('touchmove', moverDecalArrastrado, { passive: true });
        window.addEventListener('touchend', terminarArrastreDecal);

        cargarModelo();
        animar();

        new ResizeObserver(() => {
          if (!contenedor.clientWidth || !contenedor.clientHeight) return;
          camera.aspect = contenedor.clientWidth / contenedor.clientHeight;
          camera.updateProjectionMatrix();
          renderer.setSize(contenedor.clientWidth, contenedor.clientHeight);
        }).observe(contenedor);

      } catch (err) {
        console.error('Error iniciando el visor 3D:', err);
        contenedor.innerHTML = `
          <div style="height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;color:var(--text-3);padding:24px;text-align:center;">
            <i class="fas fa-triangle-exclamation" style="font-size:1.8rem;color:#DC2626;"></i>
            <div style="font-weight:700;color:var(--text-1);">No se pudo cargar el visor 3D</div>
            <div style="font-size:0.8rem;max-width:380px;">${err.message}</div>
            <button type="button" class="tb-btn tb-btn-ghost" onclick="location.reload()" style="margin-top:8px;">
              <i class="fas fa-rotate"></i> Reintentar
            </button>
          </div>`;
        mostrarAviso('No se pudo cargar el visor 3D. Revisa tu conexión a internet.', 'error');
      }
    }

    function animar() {
      requestAnimationFrame(animar);
      controls.update();
      renderer.render(scene, camera);
    }

    function crearMaterial(color) {
        return new THREE.MeshStandardMaterial({
            color: new THREE.Color(color),
            roughness: 0.82,
           metalness: 0.04
        });
    }

    function cargarModelo() {

        const loader = new THREE.GLTFLoader();

        
        loader.load(
            '/modelos/oversized_t-shirt.glb',

            function (gltf) {

                const modeloOriginal = gltf.scene;

                // Medir el modelo tal cual viene, ANTES de tocar nada
                const box = new THREE.Box3().setFromObject(modeloOriginal);
                const size = box.getSize(new THREE.Vector3());
                const center = box.getCenter(new THREE.Vector3());

                console.log('Tamaño del modelo:', size);
                console.log('Centro del modelo:', center);

                // SOLUCIÓN: el .glb trae el pivote interno desplazado (no centrado
                // en su propia geometría). En vez de mover el modelo directamente,
                // lo metemos en un grupo y movemos el modelo DENTRO del grupo.
                // Luego escalamos y posicionamos el GRUPO, que sí tiene su pivote
                // limpio en (0,0,0). Esto evita que el offset se multiplique al escalar.
                modelo3D = new THREE.Group();
                modeloOriginal.position.set(-center.x, -center.y, -center.z);
                modelo3D.add(modeloOriginal);

                const maxDim = Math.max(size.x, size.y, size.z);
                const escalaAuto = 1.6 / maxDim;
                modelo3D.scale.set(escalaAuto, escalaAuto, escalaAuto);
                modelo3D.position.set(0, 0, 0);

                scene.add(modelo3D);

                // Verificar el centro DESPUÉS de escalar y mover (debe quedar en 0,0,0)
                const boxFinal = new THREE.Box3().setFromObject(modelo3D);
                const centerFinal = boxFinal.getCenter(new THREE.Vector3());
                console.log('Centro final (debe ser ~0,0,0):', centerFinal);

                // Apuntar la cámara y los controles exactamente al centro de la prenda
                controls.target.set(0, 0, 0);
                controls.update();

                // Asignar zona editable a cada malla según el nombre que trae del .glb
                // y aplicarle un material propio (en vez de MeshNormalMaterial de depuración)
                modelo3D.traverse(function (obj) {
                    if (obj.isMesh) {
                        const nombre = obj.name.toLowerCase();

                        let zona = 'cuerpo'; // zona por defecto
                        if (nombre.includes('sleeve') || nombre.includes('manga')) zona = 'mangas';
                        else if (nombre.includes('collar') || nombre.includes('cuello')) zona = 'mangas';
                        else if (nombre.includes('hood') || nombre.includes('capucha')) zona = 'capucha';
                        else if (nombre.includes('short') || nombre.includes('pant')) zona = 'short';
                        else if (nombre.includes('detail') || nombre.includes('detalle') || nombre.includes('trim')) zona = 'detalle';
                        else if (nombre.includes('body') || nombre.includes('cuerpo') || nombre.includes('main')) zona = 'cuerpo';

                        obj.userData.zona = zona;

                        const campoColor = ZONE_TO_STATE[zona] || 'colorA';
                        obj.material = crearMaterial(estado[campoColor]);
                    }
                });

                // Pintar inmediatamente con los colores actuales del estado
                aplicarColores();

            },

            undefined,

            function (error) {
                console.error('Error cargando el modelo 3D:', error);
                mostrarAviso('No se pudo cargar el modelo 3D. Verifica que el archivo exista en /public/modelos/', 'error');
            }

        );

    }

    function pieza(geo, color, zona, pos, rot, escala, grupo) {
      const m = new THREE.Mesh(geo, crearMaterial(color));
      m.userData.zona = zona;
      if (pos) m.position.set(...pos);
      if (rot) m.rotation.set(...rot);
      if (escala) m.scale.set(...escala);
      grupo.add(m);
      return m;
    }

    
    function aplicarColores() {
      if (!modelo3D) return;
      modelo3D.traverse(obj => {
        if (obj.isMesh && obj.userData.zona) {
          const campo = ZONE_TO_STATE[obj.userData.zona];
          if (campo) obj.material.color.set(estado[campo]);
        }
      });
    }

    /* ═══════════ DECALS: TEXTO Y LOGO (ARRASTRABLES) ═══════════ */
    function quitarDecal(tipo) {
      const ref = tipo === 'texto' ? decalTexto : decalLogo;
      if (ref) { modelo3D && modelo3D.remove(ref); }
      if (tipo === 'texto') decalTexto = null; else decalLogo = null;
    }

    function actualizarDecalTexto() {
      // Si ya existe, solo actualizamos su textura y conservamos su posición actual
      const posPrevia = decalTexto ? decalTexto.position.clone() : null;
      quitarDecal('texto');
      if (!estado.texto || !estado.texto.trim()) return;

      const canvas = document.createElement('canvas');
      canvas.width = 512; canvas.height = 160;
      const ctx = canvas.getContext('2d');
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.font = '700 84px DM Sans, sans-serif';
      ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
      ctx.fillStyle = estado.textoColor;
      ctx.fillText(estado.texto.toUpperCase().slice(0, 22), canvas.width / 2, canvas.height / 2);

      const tex = new THREE.CanvasTexture(canvas);
      const mat = new THREE.MeshBasicMaterial({ map: tex, transparent: true, depthTest: false });
      const geo = new THREE.PlaneGeometry(1.5, 0.47);
      decalTexto = new THREE.Mesh(geo, mat);
      decalTexto.renderOrder = 2; // siempre encima de la tela
      decalTexto.userData.draggable = true;
      decalTexto.userData.tipoDecal = 'texto';

      if (posPrevia) {
        decalTexto.position.copy(posPrevia);
      } else {
        const yPos = estado.tipo === 'conjunto' ? 0.55 : 0.1;
        decalTexto.position.set(0, yPos, 0.66);
      }
      modelo3D.add(decalTexto);
    }

    function actualizarDecalLogo() {
      const posPrevia = decalLogo ? decalLogo.position.clone() : null;
      quitarDecal('logo');
      if (!estado.logoDataUrl) return;

      const img = new Image();
      img.onload = () => {
        const tex = new THREE.Texture(img);
        tex.needsUpdate = true;
        const ratio = img.width / img.height;
        const alto = 0.6, ancho = alto * ratio;
        const mat = new THREE.MeshBasicMaterial({ map: tex, transparent: true, depthTest: false });
        const geo = new THREE.PlaneGeometry(Math.min(ancho, 1.2), alto);
        decalLogo = new THREE.Mesh(geo, mat);
        decalLogo.renderOrder = 2;
        decalLogo.userData.draggable = true;
        decalLogo.userData.tipoDecal = 'logo';

        if (posPrevia) {
          decalLogo.position.copy(posPrevia);
        } else {
          const yPos = (estado.tipo === 'conjunto' ? 0.55 : 0.1) + 0.62;
          decalLogo.position.set(0, yPos, 0.66);
        }
        modelo3D.add(decalLogo);
      };
      img.src = estado.logoDataUrl;
    }

    /* ═══════════ ARRASTRAR DECALS SOBRE LA PRENDA (RAYCASTING) ═══════════ */
    const raycaster = new THREE.Raycaster();
    const mouseNDC = new THREE.Vector2();
    let decalArrastrado = null;

    function actualizarMouseNDC(event) {
      const rect = renderer.domElement.getBoundingClientRect();
      const clientX = event.touches ? event.touches[0].clientX : event.clientX;
      const clientY = event.touches ? event.touches[0].clientY : event.clientY;
      mouseNDC.x = ((clientX - rect.left) / rect.width) * 2 - 1;
      mouseNDC.y = -((clientY - rect.top) / rect.height) * 2 + 1;
    }

    function iniciarArrastreDecal(event) {
      if (!modelo3D) return;
      actualizarMouseNDC(event);
      raycaster.setFromCamera(mouseNDC, camera);

      // Revisamos si el clic cayó sobre uno de los decals arrastrables
      const decalsActivos = [decalTexto, decalLogo].filter(Boolean);
      if (decalsActivos.length === 0) return;

      const hits = raycaster.intersectObjects(decalsActivos, false);
      if (hits.length > 0) {
        decalArrastrado = hits[0].object;
        controls.enabled = false; // bloqueamos la rotación de cámara mientras se arrastra
        event.preventDefault();
      }
    }

    function moverDecalArrastrado(event) {
      if (!decalArrastrado) return;
      actualizarMouseNDC(event);
      raycaster.setFromCamera(mouseNDC, camera);

      // Buscamos dónde cae el rayo sobre la TELA (las mallas del modelo, no sobre el decal)
      const mallasPrenda = [];
      modelo3D.traverse(o => { if (o.isMesh && o.userData.zona) mallasPrenda.push(o); });

      const hits = raycaster.intersectObjects(mallasPrenda, false);
      if (hits.length > 0) {
        const punto = hits[0].point.clone();
        modelo3D.worldToLocal(punto); // convertir a coordenadas locales del modelo

        decalArrastrado.position.x = punto.x;
        decalArrastrado.position.y = punto.y;
        // Mantenemos un pequeño offset en Z para que no se "entierre" en la tela
        decalArrastrado.position.z = punto.z + 0.02;
      }
    }

    function terminarArrastreDecal() {
      decalArrastrado = null;
      controls.enabled = true;
    }

    /* ═══════════ UI: HERRAMIENTAS (RAIL IZQUIERDO) ═══════════ */
    function seleccionarHerramienta(tool) {
      document.querySelectorAll('.rail-btn').forEach(b => b.classList.remove('active'));
      document.querySelector('.rail-btn[data-tool="' + tool + '"]').classList.add('active');
      ['colores', 'logo', 'texto'].forEach(t => {
        document.getElementById('panel-' + t).style.display = (t === tool) ? 'block' : 'none';
      });
      document.getElementById('tool-panel').classList.remove('collapsed');
    }

    function seleccionarTipo(tipo, el) {
      guardarHistorial();
      estado.tipo = tipo;
      document.querySelectorAll('#tipo-chips .chip-opcion').forEach(c => c.classList.remove('active'));
      if (el) el.classList.add('active');
      actualizarVisibilidadCapucha();
      if (tipo !== 'camiseta' && tipo !== 'conjunto') { estado.capucha = false; document.getElementById('toggle-capucha').checked = false; }
      renderZonasColor();
      if (modelo3D) scene.remove(modelo3D);
      cargarModelo();
    }

    function cambiarCapucha(valor) {
      guardarHistorial();
      estado.capucha = valor;
      renderZonasColor();
      if (modelo3D) scene.remove(modelo3D);
      cargarModelo();
    }

    function renderZonasColor() {
      const cont = document.getElementById('zonas-color');
      cont.innerHTML = '';
      let zonas = (PANEL_ZONES[estado.tipo] || []).slice();
      if (estado.capucha && (estado.tipo === 'camiseta' || estado.tipo === 'conjunto')) {
        zonas.push({ zone: 'capucha', label: 'Color de capucha' });
      }
      zonas.forEach(z => {
        const campo = ZONE_TO_STATE[z.zone];
        const colorActual = estado[campo];
        const div = document.createElement('div');
        div.className = 'campo-grupo';
        div.innerHTML = `
          <label class="campo-label">${z.label}</label>
          <div class="swatch-row" data-campo="${campo}">
            ${PRESETS.map(c => `<span class="swatch ${c.toUpperCase() === colorActual.toUpperCase() ? 'active' : ''}" style="background:${c};" onclick="elegirColorZona('${campo}', '${c}', this)"></span>`).join('')}
            <label class="swatch-custom">
              <input type="color" value="${colorActual}" oninput="elegirColorZona('${campo}', this.value, null)">
              <i class="fas fa-plus"></i>
            </label>
          </div>`;
        cont.appendChild(div);
      });
    }

    function elegirColorZona(campo, color, el) {
      guardarHistorial();
      estado[campo] = color;
      if (el) {
        const fila = el.closest('.swatch-row');
        fila.querySelectorAll('.swatch').forEach(s => s.classList.remove('active'));
        el.classList.add('active');
      }
      aplicarColores();
    }

    /* ═══════════ TEXTO ═══════════ */
    function actualizarTexto() {
      estado.texto = document.getElementById('texto').value;
      actualizarDecalTexto();
    }
    function elegirColorTexto(color, el) {
      estado.textoColor = color;
      document.getElementById('texto-color').value = color;
      if (el) {
        el.closest('.swatch-row').querySelectorAll('.swatch').forEach(s => s.classList.remove('active'));
        el.classList.add('active');
      }
      actualizarDecalTexto();
    }

    /* ═══════════ LOGO ═══════════ */
    function subirLogo(ev) {
      const file = ev.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        estado.logoDataUrl = e.target.result;
        document.getElementById('logo-preview-img').src = e.target.result;
        document.getElementById('logo-preview-nombre').textContent = file.name;
        document.getElementById('logo-preview').style.display = 'flex';
        actualizarDecalLogo();
      };
      reader.readAsDataURL(file);
    }
    function quitarLogo() {
      estado.logoDataUrl = null;
      document.getElementById('input-logo').value = '';
      document.getElementById('logo-preview').style.display = 'none';
      quitarDecal('logo');
    }

    /* ═══════════ CONTROLES DEL VISOR ═══════════ */
    function reiniciarVista() {
      camera.position.set(0,0,10);
      controls.target.set(0, 0, 0);
      controls.update();
    }
    function pantallaCompleta() {
      const el = document.documentElement;
      if (!document.fullscreenElement) el.requestFullscreen?.();
      else document.exitFullscreen?.();
    }

    /* ═══════════ BARRA DE IA (ABAJO) ═══════════ */
    function toggleBarraIA(forzar) {
      const barra = document.getElementById('ai-bar');
      const abrir = forzar !== undefined ? forzar : !barra.classList.contains('open');
      barra.classList.toggle('open', abrir);
      document.querySelector('.rail-btn[data-tool="ia"]').classList.toggle('active', abrir);
    }

    let ultimaImagenIA = null;

    function generarConIA() {
      const prompt = document.getElementById('prompt-ia').value.trim();
      if (prompt.length < 5) {
        mostrarAviso('Describe un poco más tu diseño para que la IA pueda generarlo.', 'error');
        return;
      }
      const btn = document.getElementById('btn-generar-ia');
      const btnTexto = document.getElementById('btn-generar-texto');
      btn.disabled = true; btnTexto.textContent = 'Generando...';

      fetch("{{ route('disenios.generar-ia') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        body: new URLSearchParams({ prompt, plantilla_id: PLANTILLA_ID ?? '' }),
      })
      .then(r => r.json())
      .then(data => {
        btn.disabled = false; btnTexto.textContent = 'Generar diseño con IA';
        const right = document.getElementById('ai-bar-right');
        if (data.success) {
          ultimaImagenIA = data.imagen_url;
          right.innerHTML = `
            <img class="ai-resultado-img" src="${data.imagen_url}" alt="Resultado IA">
            <div class="ai-resultado-acciones">
              <button type="button" class="tb-btn tb-btn-ghost" onclick="generarConIA()"><i class="fas fa-rotate"></i> Generar otro</button>
              <button type="button" class="tb-btn tb-btn-dark" onclick="usarDisenoIA('${data.imagen_url}')"><i class="fas fa-check"></i> Usar como referencia</button>
            </div>`;
          mostrarAviso(data.message, 'success');
        } else if (data.pending) {
          mostrarAviso(data.message, 'info');
        } else {
          mostrarAviso(data.message || 'No se pudo generar el diseño.', 'error');
        }
      })
      .catch(() => {
        btn.disabled = false; btnTexto.textContent = 'Generar diseño con IA';
        mostrarAviso('Ocurrió un error generando el diseño con IA.', 'error');
      });
    }

    function usarDisenoIA(url) {
      mostrarAviso('Diseño de referencia guardado. Tu asesor lo usará junto a tu modelo 3D.', 'success');
    }

    /* ═══════════ GUARDAR DISEÑO ═══════════ */
    function capturarVista2D() {
      // Forzamos un render justo antes de capturar para asegurar que el frame esté actualizado
      renderer.render(scene, camera);
      return renderer.domElement.toDataURL('image/png');
    }

    function guardarDiseno(continuar) {
      // 1. Encuadramos bien la cámara antes de la captura (vista frontal limpia)
      const posPrevia = camera.position.clone();
      const targetPrevio = controls.target.clone();
      camera.position.set(0, 0, 3.2);
      controls.target.set(0, 0, 0);
      controls.update();

      // 2. Capturamos la imagen 2D del resultado actual del diseño 3D
      const imagenCaptura = capturarVista2D();

      // 3. Regresamos la cámara a como estaba para no interrumpir al usuario
      camera.position.copy(posPrevia);
      controls.target.copy(targetPrevio);
      controls.update();

      const body = new URLSearchParams({
        plantilla_id: PLANTILLA_ID ?? '',
        nombre: document.getElementById('nombre').value,
        tipo_prenda: estado.tipo,
        color_principal: estado.colorA,
        color_secundario: estado.colorB,
        color_terciario: (estado.tipo === 'conjunto' || estado.capucha) ? estado.colorC : '',
        texto: estado.texto,
        texto_color: estado.textoColor,
        // Posición del texto y del logo sobre la prenda (coordenadas locales del modelo)
        texto_pos_x: decalTexto ? decalTexto.position.x : '',
        texto_pos_y: decalTexto ? decalTexto.position.y : '',
        logo_pos_x: decalLogo ? decalLogo.position.x : '',
        logo_pos_y: decalLogo ? decalLogo.position.y : '',
        // Imagen 2D capturada del diseño 3D final (se guarda como evidencia del pedido)
        imagen_captura: imagenCaptura,
      });

      fetch("{{ route('disenios.store') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        body,
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          mostrarAviso(data.message, 'success');
          if (continuar && PLANTILLA_ID) {
            setTimeout(() => window.location.href = "{{ $plantilla ? route('producto.ver', $plantilla->id) : route('cliente.inicio') }}", 900);
          }
        } else {
          mostrarAviso('No se pudo guardar el diseño. Intenta de nuevo.', 'error');
        }
      })
      .catch(() => mostrarAviso('Ocurrió un error guardando tu diseño.', 'error'));
    }


    /* ═══════════ AVISOS ═══════════ */
    function mostrarAviso(mensaje, tipo) {
      const aviso = document.getElementById('aviso-global');
      const estilos = {
        success: 'background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;',
        info:    'background:var(--blue-soft);border:1px solid var(--blue-border);color:var(--blue);',
        error:   'background:#FEE2E2;border:1px solid #FECACA;color:#991B1B;',
      };
      aviso.style.cssText = 'position:absolute;top:14px;right:14px;z-index:20;max-width:340px;padding:11px 16px;border-radius:10px;font-size:0.82rem;font-weight:500;box-shadow:0 6px 18px rgba(0,0,0,0.1);display:block;' + estilos[tipo];
      aviso.textContent = mensaje;
      clearTimeout(window._avisoTimeout);
      window._avisoTimeout = setTimeout(() => { aviso.style.display = 'none'; }, 4500);
    }

    /* ═══════════ HISTORIAL (DESHACER / REHACER) ═══════════ */
    let historial = [JSON.stringify(estado)];
    let historialIdx = 0;

    function guardarHistorial() {
      historial = historial.slice(0, historialIdx + 1);
      historial.push(JSON.stringify(estado));
      historialIdx = historial.length - 1;
      actualizarBotonesHistorial();
    }
    function deshacer() {
      if (historialIdx <= 0) return;
      historialIdx--;
      restaurarEstado(JSON.parse(historial[historialIdx]));
    }
    function rehacer() {
      if (historialIdx >= historial.length - 1) return;
      historialIdx++;
      restaurarEstado(JSON.parse(historial[historialIdx]));
    }
    function restaurarEstado(nuevo) {
      estado.tipo = nuevo.tipo; estado.capucha = nuevo.capucha;
      estado.colorA = nuevo.colorA; estado.colorB = nuevo.colorB; estado.colorC = nuevo.colorC;
      estado.texto = nuevo.texto; estado.textoColor = nuevo.textoColor;
      document.getElementById('texto').value = estado.texto;
      document.getElementById('texto-color').value = estado.textoColor;
      const chip = document.querySelector('#tipo-chips .chip-opcion[data-tipo="' + estado.tipo + '"]');
      if (chip) { document.querySelectorAll('#tipo-chips .chip-opcion').forEach(c => c.classList.remove('active')); chip.classList.add('active'); }
      const toggle = document.getElementById('toggle-capucha');
      if (toggle) toggle.checked = estado.capucha;
      actualizarVisibilidadCapucha();
      renderZonasColor();
      scene.remove(modelo3D);
      cargarModelo();
      actualizarBotonesHistorial();
    }
    function actualizarBotonesHistorial() {
      document.getElementById('btn-undo').disabled = historialIdx <= 0;
      document.getElementById('btn-redo').disabled = historialIdx >= historial.length - 1;
    }

    /* ═══════════ INICIALIZAR ═══════════ */
    function actualizarVisibilidadCapucha() {
      const wrap = document.getElementById('toggle-capucha-wrap');
      if (wrap) wrap.style.display = (estado.tipo === 'camiseta' || estado.tipo === 'conjunto') ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
      actualizarVisibilidadCapucha();
      renderZonasColor();
      initEscena();
      actualizarBotonesHistorial();
    });

    // modo oscuro heredado (si el usuario lo tenía activo en el resto del sitio)
    const temaGuardado = localStorage.getItem('lj-theme');
    if (temaGuardado) document.documentElement.setAttribute('data-theme', temaGuardado);
  </script>
</body>
</html>