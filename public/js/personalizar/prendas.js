/* ═══════════════════════════════════════════════════════════════
   PRENDAS — modeloCompleto.glb
   Camiseta: 6 zonas | Pantaloneta: 2 zonas | Medias: 2 zonas
   ═══════════════════════════════════════════════════════════════ */

const estado = {
  // Camiseta
  colorFrente            : '#ffd835',
  colorAtras             : '#ffd835',
  colorMangas            : '#f56b00',
  colorParteAbajoMangas  : '#2f2f2f',
  colorCuello            : '#2f2f2f',
  colorParteAbajoCamiseta: '#2f2f2f',
  // Chompa — claves propias para que sus colores NUNCA se mezclen con
  // los de la camiseta al cambiar de prenda (antes reutilizaban las
  // mismas claves de arriba y el color se "heredaba" entre prendas).
  chompaColorFrente      : '#ffd835',
  chompaColorAtras       : '#ffd835',
  chompaColorMangas      : '#f56b00',
  chompaColorMangaIzq    : '#f56b00',
  chompaColorCierre      : '#2f2f2f',
  chompaColorBolsillo    : '#2f2f2f',
  chompaColorCapucha     : '#2f2f2f',
  chompaColorParteAbajo  : '#0d47a1',
  // Pantalón deportivo (accesorio independiente que acompaña a la chompa)
  chompaColorPantalon    : '#2f2f2f',
  // Pantaloneta
  colorPantaloneta       : '#1565c0',
  colorParteAbajoPant    : '#0d47a1',
  // Medias
  colorMedias            : '#b71c1c',
  colorPartearribaMedias : '#7f0000',
  // Texto
  colorTexto             : '#000000',
};

let vistaActual      = 'frente';
let tipoPrendaActual = 'camiseta';

function crearCanvasData(vistas) {
  return Object.keys(vistas).reduce((acc, v) => { acc[v] = null; return acc; }, {});
}

/* ═══════════════════════════════════════════════════════════════
   MAPEO MESHES → ZONAS
   meshMap: nombre del mesh en Three.js → clave de vista
   Un color puede controlar varios meshes (mangas = der + izq)
   ═══════════════════════════════════════════════════════════════ */
/* Mapa de color → nombres de NODO exactos que reporta Three.js
   (Three.js ELIMINA los puntos de los nombres: .001 → 001) */
const MESH_COLORES = {
  colorFrente            : ['Soccer_Outfit_Kit_01_1001001'],  // camisetaFrente
  colorAtras             : ['Soccer_Outfit_Kit_01_1001002'],  // camisetaAtras
  colorMangas            : ['Soccer_Outfit_Kit_01_1001003', 'Soccer_Outfit_Kit_01_1001005'], // mangaDer + mangaIzq
  colorParteAbajoMangas  : ['Soccer_Outfit_Kit_01_1001004', 'Soccer_Outfit_Kit_01_1001006'], // parteabajoMangaDer + Izq
  colorCuello            : ['Soccer_Outfit_Kit_01_1001007'],  // cuello
  colorParteAbajoCamiseta: ['Soccer_Outfit_Kit_01_1001008'],  // parteabajoCamiseta
  colorPantaloneta       : ['Soccer_Outfit_Kit_01_1002004'],  // pantaloneta
  colorParteAbajoPant    : ['Soccer_Outfit_Kit_01_1002003'],  // parteabajoPantaloneta
  colorMedias            : ['Soccer_Outfit_Kit_01_1002001'],  // medias
  colorPartearribaMedias : ['Soccer_Outfit_Kit_01_1002002'],  // partearribaMedias
};

/* Nodos base a ocultar (son meshes estructurales con pocos vértices) */
const MESHES_OCULTOS = ['Soccer_Outfit_Kit_01_1001', 'Soccer_Outfit_Kit_01_1002'];

const PRENDAS = {
  camiseta: {
    label       : 'Camiseta',
    ruta        : RUTAS_MODELO.completo,
    svg         : getSVGporVista,
    vistaInicial: 'frente',
    vistas: {
      'frente'             : { key: 'colorFrente',            label: 'Delantera',        tab: 'Delantera',    key3d: 'colorFrente'            },
      'atras'              : { key: 'colorAtras',             label: 'Trasera',           tab: 'Trasera',      key3d: 'colorAtras'             },
      'mangas'             : { key: 'colorMangas',            label: 'Mangas',            tab: 'Mangas',       key3d: 'colorMangas',            soloColor: true },
      'parte-abajo-mangas' : { key: 'colorParteAbajoMangas',  label: 'Parte baja mangas', tab: 'Baja mangas',  key3d: 'colorParteAbajoMangas',  soloColor: true },
      'cuello'             : { key: 'colorCuello',            label: 'Cuello',            tab: 'Cuello',       key3d: 'colorCuello',            soloColor: true },
      'parte-abajo-cam'    : { key: 'colorParteAbajoCamiseta',label: 'Parte baja',        tab: 'Parte baja',   key3d: 'colorParteAbajoCamiseta',soloColor: true },
    },
    zonasDiseno: {
      'frente'             : { x: 92,  y: 42,  w: 296, h: 380 },
      'atras'              : { x: 92,  y: 38,  w: 296, h: 380 },
      'mangas'             : { x: 7,   y: 394, w: 389, h: 96  },
    },
    ajusteTextura: {
      'frente'             : { mirrorX:false, invertY:false, scaleX:1, scaleY:1.3, offsetX:-123, offsetY:106 },
      'atras'              : { mirrorX:false, invertY:false, scaleX:1,   scaleY:1.38,   offsetX:120, offsetY:105 },
      'mangas'             : { mirrorX:false, invertY:false, scaleX:1.0, scaleY:1.0, offsetX:0, offsetY:0 },
      'parte-abajo-mangas' : { mirrorX:false, invertY:false, scaleX:1,   scaleY:1,   offsetX:0,   offsetY:0   },
      'cuello'             : { mirrorX:false, invertY:false, scaleX:1,   scaleY:1,   offsetX:0,   offsetY:0   },
      'parte-abajo-cam'    : { mirrorX:false, invertY:false, scaleX:1,   scaleY:1,   offsetX:0,   offsetY:0   },
    },
  },
  chompa: {
    label       : 'Chompa',
    ruta        : RUTAS_MODELO.chompa,
    svg         : getSVGporVista,
    vistaInicial: 'frente',
    vistas: {
      'frente'        : { key: 'chompaColorFrente',        label: 'Frente',        tab: 'Frente',      key3d: 'chompaColorFrente'        },
      'atras'         : { key: 'chompaColorAtras',         label: 'Atras',         tab: 'Atras',       key3d: 'chompaColorAtras'         },
      'manga-derecha' : { key: 'chompaColorMangas',        label: 'Manga derecha', tab: 'Manga Der.',  key3d: 'chompaColorMangas'        },
      'manga-izquierda':{ key: 'chompaColorMangaIzq',      label: 'Manga izquierda',tab:'Manga Izq.',  key3d: 'chompaColorMangaIzq'      },
      'cierre'        : { key: 'chompaColorCierre',        label: 'Cierre',        tab: 'Cierre',      key3d: 'chompaColorCierre',        soloColor: true },
      'bolsillo'      : { key: 'chompaColorBolsillo',      label:'Bolsillo',     tab: 'Bolsillo',    key3d: 'chompaColorBolsillo' },
      'capucha'       : { key: 'chompaColorCapucha',       label:'Capucha',    tab: 'Capucha',     key3d: 'chompaColorCapucha' },
      'parte-abajo'   : { key: 'chompaColorParteAbajo',    label: 'Parte abajo',   tab: 'Parte abajo', key3d: 'chompaColorParteAbajo',soloColor: true },
    },
    meshMap: {
      'WORLD_ZIP_HOODIE001': 'manga-derecha',
      'WORLD_ZIP_HOODIE002': 'manga-izquierda',
      'WORLD_ZIP_HOODIE003': 'frente',
      'WORLD_ZIP_HOODIE004': 'atras',
      'WORLD_ZIP_HOODIE005': 'cierre',
      'WORLD_ZIP_HOODIE006': 'bolsillo',
      'WORLD_ZIP_HOODIE007': 'capucha',
      'WORLD_ZIP_HOODIE008': 'parte-abajo',
    },
    zonasDiseno: {
      'frente'        : { x: 110, y: 82,  w: 260, h: 358 },
      'atras'         : { x: 110, y: 82,  w: 260, h: 358 },
      'manga-derecha' : { x: 112, y: 36,  w: 280, h: 390 },
      'manga-izquierda':{ x: 88,  y: 36,  w: 280, h: 390 },
      'bolsillo'      : { x: 100, y: 190, w: 265, h: 150 },
      'capucha'       : { x: 116, y: 58,  w: 248, h: 272 },
    },
    /* Dirección fija (en espacio mundo) que usa la cámara de calibración
       del warp UV (ver construirCamaraFrontalMesh en three-viewer.js) para
       cada panel. Sin esto, la dirección se adivina promediando las
       normales de la malla, lo cual falla en chompa.glb: para "frente" esa
       normal promedio da (-0.75,-0.24,0.62) en vez de (0,0,1) —el panel
       tiene curvatura y triangulación despareja—, así que la cámara de
       calibración terminaba mirando casi de lado y el diseño se veía
       rotado/deformado en el visor 3D. Los paneles frontales/traseros son,
       en la práctica, planos que el cliente ve de frente o de espaldas —
       igual que la cámara real del visor— así que se fuerza ese eje.
       Mangas no se incluyen aquí: al colgar en ángulo, la normal promedio
       ya da un resultado razonable. */
    direccionesCalibracion: {
      'frente'  : [0, 0, 1],
      'atras'   : [0, 0, -1],
      'bolsillo': [0, 0, 1],
      'capucha' : [0, 0, 1],
    },
    ajusteTextura: {
      'frente'        : { mirrorX:false, invertY:false, scaleX:1, scaleY:1, offsetX:0, offsetY:0 },
      'atras'         : { mirrorX:false, invertY:false, scaleX:1, scaleY:1, offsetX:0, offsetY:0 },
      'manga-derecha' : { mirrorX:false, invertY:false, scaleX:1, scaleY:1, offsetX:0, offsetY:0 },
      'manga-izquierda':{ mirrorX:false, invertY:false, scaleX:1, scaleY:1, offsetX:0, offsetY:0 },
      'cierre'        : { mirrorX:false, invertY:false, scaleX:1, scaleY:1, offsetX:0, offsetY:0 },
      'bolsillo'      : { mirrorX:false, invertY:false, scaleX:1, scaleY:1, offsetX:0, offsetY:0 },
      'capucha'       : { mirrorX:false, invertY:false, scaleX:1, scaleY:1, offsetX:0, offsetY:0 },
      'parte-abajo'   : { mirrorX:false, invertY:false, scaleX:1, scaleY:1, offsetX:0, offsetY:0 },
    },
  },
};

/* ═══════════════════════════════════════════════════════════════
   SVGs POR VISTA — Camiseta con todas las zonas coloreadas
   ═══════════════════════════════════════════════════════════════ */
function getSVGporVista(vista, color) {
  const W = CANVAS_W, H = CANVAS_H;
  const c = color || '#ffffff';

  if (tipoPrendaActual === 'chompa') {
    const svgsChompa = {
      'frente': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
        <defs><filter id="sh"><feDropShadow dx="0" dy="5" stdDeviation="9" flood-opacity=".16"/></filter></defs>
        <path d="M150 62 Q170 25 210 36 Q240 48 270 36 Q310 25 330 62 Q302 92 240 96 Q178 92 150 62 Z" fill="${estado.chompaColorCapucha}" filter="url(#sh)"/>
        <path d="M92 92 Q55 168 34 328 Q78 356 122 332 L145 108 Z" fill="${estado.chompaColorMangas}" filter="url(#sh)"/>
        <path d="M388 92 Q425 168 446 328 Q402 356 358 332 L335 108 Z" fill="${estado.chompaColorMangaIzq}" filter="url(#sh)"/>
        <path d="M138 82 Q190 58 240 88 Q290 58 342 82 L370 440 L110 440 Z" fill="${c}" filter="url(#sh)"/>
        <rect x="234" y="92" width="12" height="340" rx="5" fill="${estado.chompaColorCierre}"/>
        <path d="M142 292 Q190 270 232 302 L224 370 Q176 388 132 354 Z" fill="${estado.chompaColorBolsillo}" opacity=".95"/>
        <path d="M248 302 Q290 270 338 292 L348 354 Q304 388 256 370 Z" fill="${estado.chompaColorBolsillo}" opacity=".95"/>
        <rect x="112" y="424" width="256" height="34" rx="10" fill="${estado.chompaColorParteAbajo}"/>
      </svg>`,
      'atras': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
        <defs><filter id="sh"><feDropShadow dx="0" dy="5" stdDeviation="9" flood-opacity=".16"/></filter></defs>
        <path d="M148 58 Q170 18 210 34 Q240 46 270 34 Q310 18 332 58 Q312 118 240 132 Q168 118 148 58 Z" fill="${estado.chompaColorCapucha}" filter="url(#sh)"/>
        <path d="M92 92 Q55 168 34 328 Q78 356 122 332 L145 108 Z" fill="${estado.chompaColorMangaIzq}" filter="url(#sh)"/>
        <path d="M388 92 Q425 168 446 328 Q402 356 358 332 L335 108 Z" fill="${estado.chompaColorMangas}" filter="url(#sh)"/>
        <path d="M138 82 Q190 52 240 72 Q290 52 342 82 L370 440 L110 440 Z" fill="${c}" filter="url(#sh)"/>
        <rect x="112" y="424" width="256" height="34" rx="10" fill="${estado.chompaColorParteAbajo}"/>
      </svg>`,
      'manga-derecha': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
        <defs><filter id="sh"><feDropShadow dx="0" dy="5" stdDeviation="9" flood-opacity=".16"/></filter></defs>
        <path d="M155 70 Q242 36 322 78 L392 392 Q288 426 168 392 L112 110 Q128 82 155 70 Z" fill="${c}" filter="url(#sh)"/>
        <rect x="168" y="376" width="214" height="30" rx="12" fill="${estado.chompaColorParteAbajo}" opacity=".9"/>
      </svg>`,
      'manga-izquierda': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
        <defs><filter id="sh"><feDropShadow dx="0" dy="5" stdDeviation="9" flood-opacity=".16"/></filter></defs>
        <path d="M325 70 Q238 36 158 78 L88 392 Q192 426 312 392 L368 110 Q352 82 325 70 Z" fill="${c}" filter="url(#sh)"/>
        <rect x="98" y="376" width="214" height="30" rx="12" fill="${estado.chompaColorParteAbajo}" opacity=".9"/>
      </svg>`,
      'cierre': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
        <defs><filter id="sh"><feDropShadow dx="0" dy="6" stdDeviation="12" flood-opacity=".15"/></filter></defs>
        <rect x="224" y="80" width="32" height="350" rx="10" fill="${c}" filter="url(#sh)"/>
        <text x="240" y="470" text-anchor="middle" font-family="DM Sans,sans-serif" font-size="13" fill="#94A3B8" font-weight="600">Cierre</text>
      </svg>`,
      'bolsillo': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
        <defs><filter id="sh"><feDropShadow dx="0" dy="6" stdDeviation="12" flood-opacity=".15"/></filter></defs>
        <path d="M100 190 Q188 150 240 205 Q292 150 380 190 L365 340 Q300 390 240 350 Q180 390 115 340 Z" fill="${c}" filter="url(#sh)"/>
        <text x="240" y="430" text-anchor="middle" font-family="DM Sans,sans-serif" font-size="13" fill="#94A3B8" font-weight="600">Bolsillo</text>
      </svg>`,
      'capucha': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
        <defs><filter id="sh"><feDropShadow dx="0" dy="6" stdDeviation="12" flood-opacity=".15"/></filter></defs>
        <path d="M116 190 Q128 70 240 58 Q352 70 364 190 Q340 300 240 330 Q140 300 116 190 Z" fill="${c}" filter="url(#sh)"/>
        <path d="M160 190 Q175 112 240 104 Q305 112 320 190 Q300 262 240 280 Q180 262 160 190 Z" fill="rgba(0,0,0,.12)"/>
        <text x="240" y="430" text-anchor="middle" font-family="DM Sans,sans-serif" font-size="13" fill="#94A3B8" font-weight="600">Capucha</text>
      </svg>`,
      'parte-abajo': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
        <defs><filter id="sh"><feDropShadow dx="0" dy="6" stdDeviation="12" flood-opacity=".15"/></filter></defs>
        <rect x="80" y="130" width="320" height="180" rx="24" fill="${c}" filter="url(#sh)"/>
        <text x="240" y="365" text-anchor="middle" font-family="DM Sans,sans-serif" font-size="13" fill="#94A3B8" font-weight="600">Parte abajo</text>
      </svg>`,
    };
    return svgsChompa[vista] || svgsChompa.frente;
  }

  /* ── CAMISETA — SVGs con todas las zonas coloreadas ── */
  const e = estado;
  const svgs = {
    'frente': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
      <defs><filter id="sh"><feDropShadow dx="0" dy="4" stdDeviation="8" flood-opacity=".13"/></filter></defs>
      <!-- Parte baja manga izquierda -->
      <polygon points="30,170 0,210 92,202 95,165" fill="${e.colorParteAbajoMangas}" filter="url(#sh)"/>
      <!-- Manga izquierda -->
      <polygon points="30,80 0,170 92,165 115,95" fill="${e.colorMangas}" filter="url(#sh)"/>
      <!-- Parte baja manga derecha -->
      <polygon points="450,170 480,210 388,202 385,165" fill="${e.colorParteAbajoMangas}" filter="url(#sh)"/>
      <!-- Manga derecha -->
      <polygon points="450,80 480,170 388,165 365,95" fill="${e.colorMangas}" filter="url(#sh)"/>
      <!-- Cuello -->
      <path d="M 190 45 Q 240 75 290 45 Q 268 100 240 105 Q 212 100 190 45 Z" fill="${e.colorCuello}"/>
      <!-- Parte baja camiseta -->
      <path d="M 90 420 L 90 460 L 390 460 L 390 420 Z" fill="${e.colorParteAbajoCamiseta}" filter="url(#sh)"/>
      <!-- Cuerpo (frente) -->
      <path d="M 100 75 Q 160 28 190 42 Q 215 78 240 80 Q 265 78 290 42 Q 320 28 380 75 L 390 420 L 90 420 Z"
            fill="${c}" filter="url(#sh)"/>
    </svg>`,

    'atras': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
      <defs><filter id="sh"><feDropShadow dx="0" dy="4" stdDeviation="8" flood-opacity=".13"/></filter></defs>
      <polygon points="30,170 0,210 92,202 95,165" fill="${e.colorParteAbajoMangas}" filter="url(#sh)"/>
      <polygon points="30,80 0,170 92,165 115,95" fill="${e.colorMangas}" filter="url(#sh)"/>
      <polygon points="450,170 480,210 388,202 385,165" fill="${e.colorParteAbajoMangas}" filter="url(#sh)"/>
      <polygon points="450,80 480,170 388,165 365,95" fill="${e.colorMangas}" filter="url(#sh)"/>
      <rect x="190" y="38" width="100" height="22" rx="6" fill="${e.colorCuello}"/>
      <path d="M 90 420 L 90 460 L 390 460 L 390 420 Z" fill="${e.colorParteAbajoCamiseta}" filter="url(#sh)"/>
      <path d="M 100 75 Q 160 28 190 38 L 290 38 Q 320 28 380 75 L 390 420 L 90 420 Z"
            fill="${c}" filter="url(#sh)"/>
    </svg>`,

    'mangas': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
      <defs><filter id="sh"><feDropShadow dx="0" dy="4" stdDeviation="8" flood-opacity=".13"/></filter></defs>
      <!-- Manga izquierda -->
      <path d="M 60 40 Q 120 10 180 40 L 210 290 Q 150 320 80 290 Z" fill="${c}" filter="url(#sh)"/>
      <rect x="82" y="282" width="126" height="22" rx="8" fill="${e.colorParteAbajoMangas}"/>
      <!-- Manga derecha -->
      <path d="M 420 40 Q 360 10 300 40 L 270 290 Q 330 320 400 290 Z" fill="${c}" filter="url(#sh)"/>
      <rect x="272" y="282" width="126" height="22" rx="8" fill="${e.colorParteAbajoMangas}"/>
    </svg>`,

    'parte-abajo-mangas': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
      <defs><filter id="sh"><feDropShadow dx="0" dy="6" stdDeviation="12" flood-opacity=".15"/></filter></defs>
      <!-- Puño manga izquierda -->
      <path d="M 60 150 L 30 230 Q 130 260 200 230 L 200 150 Q 130 120 60 150 Z"
            fill="${c}" filter="url(#sh)"/>
      <!-- Puño manga derecha -->
      <path d="M 420 150 L 450 230 Q 350 260 280 230 L 280 150 Q 350 120 420 150 Z"
            fill="${c}" filter="url(#sh)"/>
      <text x="240" y="380" text-anchor="middle" font-family="DM Sans,sans-serif" font-size="13" fill="#94A3B8" font-weight="600">Parte baja mangas</text>
    </svg>`,

    'cuello': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
      <defs><filter id="sh"><feDropShadow dx="0" dy="6" stdDeviation="12" flood-opacity=".15"/></filter></defs>
      <path d="M 140 140 Q 240 200 340 140 Q 300 280 240 300 Q 180 280 140 140 Z"
            fill="${c}" filter="url(#sh)"/>
      <path d="M 155 155 Q 240 210 325 155" fill="none" stroke="rgba(0,0,0,.1)" stroke-width="2" stroke-dasharray="5,4"/>
      <text x="240" y="420" text-anchor="middle" font-family="DM Sans,sans-serif" font-size="13" fill="#94A3B8" font-weight="600">Cuello</text>
    </svg>`,

    'parte-abajo-cam': `<svg width="${W}" height="${H}" viewBox="0 0 480 520" xmlns="http://www.w3.org/2000/svg">
      <defs><filter id="sh"><feDropShadow dx="0" dy="6" stdDeviation="12" flood-opacity=".15"/></filter></defs>
      <rect x="80" y="160" width="320" height="120" rx="16" fill="${c}" filter="url(#sh)"/>
      <line x1="100" y1="200" x2="380" y2="200" stroke="rgba(0,0,0,.15)" stroke-width="2" stroke-dasharray="8,6"/>
      <text x="240" y="360" text-anchor="middle" font-family="DM Sans,sans-serif" font-size="13" fill="#94A3B8" font-weight="600">Parte baja camiseta</text>
    </svg>`,
  };

  return svgs[vista] || svgs['frente'];
}

/* ── Inicializar canvasData ── */
Object.values(PRENDAS).forEach(p => { p.canvasData = crearCanvasData(p.vistas); });

let VISTAS          = PRENDAS[tipoPrendaActual].vistas;
let canvasData      = PRENDAS[tipoPrendaActual].canvasData;
let TEXTURA_3D_AJUSTE = PRENDAS[tipoPrendaActual].ajusteTextura;
let ZONAS_DISENO_2D = PRENDAS[tipoPrendaActual].zonasDiseno;

const PALETA_STD = [
  '#1a237e','#0d47a1','#1565c0','#1976d2',
  '#b71c1c','#c62828','#e53935','#e57373',
  '#1b5e20','#2e7d32','#388e3c','#66bb6a',
  '#e65100','#ef6c00','#f57c00','#ffb74d',
  '#4a148c','#6a1b9a','#7b1fa2','#ce93d8',
  '#000000','#212121','#424242','#616161',
  '#9e9e9e','#bdbdbd','#e0e0e0','#ffffff',
  '#fdd835','#f9a825','#ff8f00','#00838f',
];

const FABRIC_PROPS = ['id','tipo','name','fontFamily','fontWeight','fontStyle','textDecoration','textBaseline'];

function vistaPermiteDiseno(vista = vistaActual) { return !VISTAS[vista]?.soloColor; }
function puntoInicialDiseno(vista = vistaActual) {
  const zona = ZONAS_DISENO_2D[vista];
  if (!zona) return { x: CANVAS_W/2, y: CANVAS_H/2 };
  return { x: zona.x + zona.w/2, y: zona.y + zona.h/2 };
}
function esObjetoSistema(obj) { return obj && (obj.id === 'silueta' || obj.id === 'zona-diseno'); }

if (window.fabric && fabric.Text) {
  // Sobreescribir la propiedad para que siempre devuelva 'alphabetic'
  // y nunca 'alphabetical' que es inválido en Canvas API
  ['Text','IText','Textbox'].forEach(tipo => {
    if (fabric[tipo]) {
      fabric[tipo].prototype.textBaseline = 'alphabetic';
      // Interceptar set para corregir valores inválidos
      const _orig = fabric[tipo].prototype.set;
      fabric[tipo].prototype.set = function(key, value) {
        if (key === 'textBaseline' && value === 'alphabetical') value = 'alphabetic';
        if (typeof key === 'object' && key.textBaseline === 'alphabetical') key.textBaseline = 'alphabetic';
        return _orig.call(this, key, value);
      };
    }
  });
}
function normalizarTextBaseline(obj) {
  if (!obj) return;
  if (['text','i-text','textbox'].includes(obj.type)) {
    // 'alphabetical' es inválido — corregir a 'alphabetic'
    const tb = obj.textBaseline;
    if (!tb || tb === 'alphabetical') obj.set('textBaseline','alphabetic');
  }
}
function normalizarTextosCanvas(ref = fabricCanvas) {
  if (!ref) return;
  ref.getObjects().forEach(normalizarTextBaseline);
}

function activarConfiguracionPrenda(tipo) {
  const p = PRENDAS[tipo] || PRENDAS.camiseta;
  VISTAS            = p.vistas;
  canvasData        = p.canvasData;
  TEXTURA_3D_AJUSTE = p.ajusteTextura;
  ZONAS_DISENO_2D   = p.zonasDiseno;
}

function renderVistaTabs() {
  const cont = document.getElementById('vista-tabs');
  cont.innerHTML = '';
  Object.entries(VISTAS).forEach(([vista, info]) => {
    const btn = document.createElement('button');
    btn.className   = 'vista-tab' + (vista === vistaActual ? ' active' : '');
    btn.type        = 'button';
    btn.dataset.vista = vista;
    btn.onclick     = () => cambiarVista(vista, btn);
    const dot = document.createElement('span');
    dot.className   = 'vista-color-dot';
    dot.id          = 'dot-' + vista;
    dot.style.background = estado[info.key];
    btn.appendChild(dot);
    btn.appendChild(document.createTextNode(info.tab || info.label));
    cont.appendChild(btn);
  });
}

function actualizarDotsVistas() {
  Object.entries(VISTAS).forEach(([vista, info]) => {
    const dot = document.getElementById('dot-' + vista);
    if (dot) dot.style.background = estado[info.key];
  });
}

function guardarCanvasActual() {
  if (fabricCanvas && canvasData && vistaActual) canvasData[vistaActual] = fabricCanvas.toJSON(FABRIC_PROPS);
}

function cargarCanvasDeVista(vista, alTerminar) {
  const info   = VISTAS[vista];
  const nombre = info?.tab || info?.label || vista;
  const prenda = PRENDAS[tipoPrendaActual]?.label || '';
  const accion = vistaPermiteDiseno(vista) ? 'arrastra los elementos libremente' : 'solo color';
  document.getElementById('canvas-label-bottom').textContent = `${prenda} — ${nombre} — ${accion}`;

  if (canvasData[vista]) {
    fabricCanvas.loadFromJSON(canvasData[vista], () => {
      normalizarTextosCanvas();
      if (!vistaPermiteDiseno(vista)) limpiarObjetosNoPermitidosEnCuello();
      refrescarSilueta();
      fabricCanvas.renderAll();
      actualizarTextura3D();
      if (alTerminar) alTerminar();
    });
    return;
  }
  fabricCanvas.clear();
  fabricCanvas.backgroundColor = '#f8fafc';
  refrescarSilueta();
  if (alTerminar) alTerminar();
}

function cambiarPrenda(tipo) {
  if (tipo === tipoPrendaActual || !PRENDAS[tipo]) return;
  guardarCanvasActual();
  tipoPrendaActual = tipo;
  activarConfiguracionPrenda(tipoPrendaActual);
  vistaActual = VISTAS[vistaActual] ? vistaActual : PRENDAS[tipoPrendaActual].vistaInicial;
  document.querySelectorAll('.prenda-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('btn-prenda-' + tipoPrendaActual)?.classList.add('active');
  renderVistaTabs();
  cargarCanvasDeVista(vistaActual);
  actualizarBadgeZona();
  actualizarDotsVistas();
  reiniciarHistorial();
  cargarModelo3D();
  if (typeof sincronizarAccesoriosConPrenda === 'function') sincronizarAccesoriosConPrenda();
}

function cambiarVista(nuevaVista, elBtn, alTerminar) {
  guardarCanvasActual();
  vistaActual = nuevaVista;
  document.querySelectorAll('.vista-tab').forEach(b => b.classList.remove('active'));
  if (elBtn) elBtn.classList.add('active');
  cargarCanvasDeVista(nuevaVista, alTerminar);
  actualizarBadgeZona();
  actualizarUIColor(estado[VISTAS[nuevaVista].key]);
}

function actualizarBadgeZona() {
  const info  = VISTAS[vistaActual];
  document.getElementById('zona-nombre').textContent = info.label;
  const color = estado[info.key];
  document.getElementById('zona-dot').style.background = color;
  actualizarUIColor(color);
}