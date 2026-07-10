/* ═══════════════════════════════════════════════════════════════
   ACCESORIOS — Pantaloneta y Medias
   3 visores 3D verticales: camiseta arriba, pantaloneta, medias
   ═══════════════════════════════════════════════════════════════ */

// El mesh de la pantaloneta modela frente Y atrás como una sola malla que
// reutiliza el mismo espacio UV para ambas caras. Sin separarla en dos
// grupos de materiales, cualquier diseño pintado sobre ella se filtra
// también (o únicamente) a la cara trasera. Ver dividirMeshFrenteAtras().
const MESH_PANTALONETA_PRINCIPAL = 'Soccer_Outfit_Kit_01_1002004';

// El pantalón deportivo de la chompa viene en su PROPIO archivo GLB
// (pantalonDeportivo.glb) en vez de estar embebido en chompa.glb como
// pasa con pantaloneta/medias en modeloCompleto.glb. Por eso se marca
// `independiente: true`: su carga/posicionamiento en la escena 3D usa
// una ruta de código distinta (ver cargarAccesorioIndependiente).
const MESH_PANTALON_CHOMPA = 'pantalon';

const ACCESORIOS = {
  pantalon: {
    label      : 'Pantalón',
    independiente: true,
    ruta       : RUTAS_MODELO.pantalon,
    meshNames  : [MESH_PANTALON_CHOMPA],
    colorKeys  : { [MESH_PANTALON_CHOMPA]: 'chompaColorPantalon' },
    activo     : false,
    grupo      : null,
    cargando   : false,
    materiales : {},
  },
  pantaloneta: {
    label     : 'Pantaloneta',
    // Three.js elimina los puntos: .004 → 004
    meshNames : ['Soccer_Outfit_Kit_01_1002004', 'Soccer_Outfit_Kit_01_1002003'],
    colorKeys : {
      'Soccer_Outfit_Kit_01_1002004': 'colorPantaloneta',
      'Soccer_Outfit_Kit_01_1002003': 'colorParteAbajoPant',
    },
    activo    : false,
    renderer  : null, scene: null, camera: null,
    controls  : null, modelo: null, modelo3d: null,
    materiales: {},
    animFrame : null,
  },
  medias: {
    label     : 'Medias',
    meshNames : ['Soccer_Outfit_Kit_01_1002001', 'Soccer_Outfit_Kit_01_1002002'],
    colorKeys : {
      'Soccer_Outfit_Kit_01_1002001': 'colorMedias',
      'Soccer_Outfit_Kit_01_1002002': 'colorPartearribaMedias',
    },
    activo    : false,
    renderer  : null, scene: null, camera: null,
    controls  : null, modelo: null, modelo3d: null,
    materiales: {},
    animFrame : null,
  },
};

const PALETA_ACC = [
  '#1a237e','#0d47a1','#1565c0','#1976d2',
  '#b71c1c','#c62828','#e53935','#e57373',
  '#1b5e20','#2e7d32','#388e3c','#66bb6a',
  '#e65100','#ef6c00','#f57c00','#ffb74d',
  '#4a148c','#6a1b9a','#7b1fa2','#ce93d8',
  '#000000','#212121','#424242','#616161',
  '#9e9e9e','#bdbdbd','#e0e0e0','#ffffff',
  '#fdd835','#f9a825','#ff8f00','#00838f',
];

/* SVG 2D para las cards del área central */
function getSVGaccesorio(id) {
  if (id === 'pantalon') {
    const cuerpo = estado.chompaColorPantalon || '#2f2f2f';
    return `<svg viewBox="0 0 200 180" xmlns="http://www.w3.org/2000/svg">
      <defs><filter id="sh4"><feDropShadow dx="0" dy="3" stdDeviation="6" flood-opacity=".18"/></filter></defs>
      <!-- Cintura -->
      <rect x="55" y="14" width="90" height="16" rx="6" fill="${cuerpo}" filter="url(#sh4)"/>
      <!-- Cuerpo / piernas -->
      <path d="M55 28 L48 158 Q48 168 62 168 L82 168 L96 78 L104 78 L118 168 L138 168 Q152 168 152 158 L145 28 Z"
            fill="${cuerpo}" filter="url(#sh4)"/>
      <!-- Línea central -->
      <line x1="100" y1="30" x2="100" y2="78" stroke="rgba(0,0,0,.12)" stroke-width="1.5"/>
    </svg>`;
  }
  if (id === 'pantaloneta') {
    const cuerpo  = estado.colorPantaloneta    || '#1565c0';
    const banda   = estado.colorParteAbajoPant || '#0d47a1';
    return `<svg viewBox="0 0 200 180" xmlns="http://www.w3.org/2000/svg">
      <defs><filter id="sh2"><feDropShadow dx="0" dy="3" stdDeviation="6" flood-opacity=".18"/></filter></defs>
      <!-- Cintura -->
      <rect x="30" y="20" width="140" height="16" rx="6" fill="${cuerpo}" filter="url(#sh2)"/>
      <!-- Cuerpo -->
      <path d="M30 36 L20 125 Q20 140 50 140 L90 140 L100 78 L110 140 L150 140 Q180 140 180 125 L170 36 Z"
            fill="${cuerpo}" filter="url(#sh2)"/>
      <!-- Banda inferior izq -->
      <rect x="22" y="130" width="72" height="14" rx="5" fill="${banda}"/>
      <!-- Banda inferior der -->
      <rect x="106" y="130" width="72" height="14" rx="5" fill="${banda}"/>
      <!-- Línea central -->
      <line x1="100" y1="72" x2="100" y2="140" stroke="rgba(0,0,0,.1)" stroke-width="1.5"/>
    </svg>`;
  }
  // Medias
  const cuerpo = estado.colorMedias            || '#b71c1c';
  const banda  = estado.colorPartearribaMedias || '#7f0000';
  return `<svg viewBox="0 0 200 180" xmlns="http://www.w3.org/2000/svg">
    <defs><filter id="sh3"><feDropShadow dx="0" dy="3" stdDeviation="6" flood-opacity=".18"/></filter></defs>
    <!-- Media izquierda cuerpo -->
    <path d="M30 30 L30 115 Q30 142 50 152 Q70 163 80 156 Q94 148 90 130 L80 115 L75 30 Z"
          fill="${cuerpo}" filter="url(#sh3)"/>
    <!-- Banda arriba izquierda -->
    <rect x="30" y="18" width="45" height="16" rx="5" fill="${banda}"/>
    <!-- Media derecha cuerpo -->
    <path d="M170 30 L170 115 Q170 142 150 152 Q130 163 120 156 Q106 148 110 130 L120 115 L125 30 Z"
          fill="${cuerpo}" filter="url(#sh3)"/>
    <!-- Banda arriba derecha -->
    <rect x="125" y="18" width="45" height="16" rx="5" fill="${banda}"/>
  </svg>`;
}

/* ─── INIT ─── */
function initAccesorios() {
  // Crear un material por cada mesh del accesorio
  Object.entries(ACCESORIOS).forEach(([id, acc]) => {
    acc.materiales = {};
    acc.materialesAtras = {};
    Object.entries(acc.colorKeys).forEach(([meshName, colorKey]) => {
      acc.materiales[meshName] = new THREE.MeshStandardMaterial({
        color: estado[colorKey] || '#ffffff',
        roughness: 0.7,
        metalness: 0.05,
      });
      // La pantaloneta necesita un segundo material para su cara trasera
      // (ver dividirMeshFrenteAtras) — siempre color sólido, nunca el diseño.
      if (meshName === MESH_PANTALONETA_PRINCIPAL) {
        acc.materialesAtras[meshName] = new THREE.MeshStandardMaterial({
          color: estado[colorKey] || '#ffffff',
          roughness: 0.7,
          metalness: 0.05,
        });
      }
    });
  });
}

/* ═══════════════════════════════════════════════════════════════
   SEPARAR FRENTE / ATRÁS DE LA PANTALONETA
   El mesh de la pantaloneta unifica frente y atrás en una sola malla
   con UVs superpuestos: sin esto, el diseño 2D se proyecta sobre la
   cara trasera (o ambas) en vez de mostrarse solo al frente.
   Se agrupan los triángulos por el signo Z de su normal (frente ≈ +Z,
   atrás ≈ -Z en el espacio local del modelo) y se reordena el índice
   para poder asignar un material distinto a cada grupo.
   ═══════════════════════════════════════════════════════════════ */
function dividirMeshFrenteAtras(mesh) {
  if (mesh.userData.divididoFrenteAtras) return;

  const geo = mesh.geometry;
  const norm = geo.attributes.normal;
  const oldIndex = geo.index;
  if (!oldIndex || !norm) return;

  const nTris = oldIndex.count / 3;
  const frenteIdx = [], atrasIdx = [];
  for (let t = 0; t < nTris; t++) {
    const a = oldIndex.getX(t * 3), b = oldIndex.getX(t * 3 + 1), c = oldIndex.getX(t * 3 + 2);
    const nz = (norm.getZ(a) + norm.getZ(b) + norm.getZ(c)) / 3;
    (nz >= 0 ? frenteIdx : atrasIdx).push(a, b, c);
  }

  const ArrayType = oldIndex.array.constructor;
  const nuevoIndice = new ArrayType(frenteIdx.length + atrasIdx.length);
  nuevoIndice.set(frenteIdx, 0);
  nuevoIndice.set(atrasIdx, frenteIdx.length);

  geo.setIndex(new THREE.BufferAttribute(nuevoIndice, 1));
  geo.clearGroups();
  geo.addGroup(0, frenteIdx.length, 0);              // grupo 0 → material frontal
  geo.addGroup(frenteIdx.length, atrasIdx.length, 1); // grupo 1 → material trasero
  mesh.userData.divididoFrenteAtras = true;
}

/* ═══════════════════════════════════════════════════════════════
   TOGGLE
   ═══════════════════════════════════════════════════════════════ */
function toggleAccesorio(id) {
  const acc = ACCESORIOS[id];
  if (!acc) return;
  acc.activo = !acc.activo;

  /* Botón sidebar */
  document.getElementById('acc-btn-' + id)?.classList.toggle('acc-btn-active', acc.activo);

  /* Panel color sidebar */
  const panel = document.getElementById('acc-panel-' + id);
  if (panel) panel.style.display = acc.activo ? 'block' : 'none';

  /* Card 2D área central */
  const card = document.getElementById('acc-card-' + id);
  if (card) {
    card.style.display = acc.activo ? 'flex' : 'none';
    if (acc.activo) {
      if (id === 'pantaloneta') {
        // Inicializar canvas Fabric.js de pantaloneta la primera vez
        setTimeout(() => {
          if (!fabricPant) initFabricPantaloneta();
          else refrescarSiluetaPant();
        }, 60);
      } else {
        renderizarSVGaccesorio(id);
      }
    }
  }
  actualizarColumnaAcc();
  // Mostrar/ocultar selector de destino
  if (typeof actualizarSelectorDestino === 'function') actualizarSelectorDestino();

  // Mostrar/ocultar en el visor 3D principal
  if (acc.activo) {
    cargarAccesorioEnScene3D(id);
  } else {
    ocultarAccesorioEnScene3D(id);
  }
  setTimeout(reencuadrarUniforme, 350);
}

function actualizarColumnaAcc() {
  const columna = document.getElementById('acc-columna');
  if (!columna) return;
  columna.style.display = Object.values(ACCESORIOS).some(a => a.activo) ? 'flex' : 'none';
}

/* ── Cargar accesorio en el scene3d principal ── */
/* ── Usar modelo3D principal (ya en escena) para mostrar accesorios ── */
function cargarAccesorioEnScene3D(id) {
  const acc = ACCESORIOS[id];
  if (acc.independiente) { cargarAccesorioIndependiente(id); return; }

  // modelo3D ya tiene todos los meshes del GLB completo
  // Solo hay que asignar materiales y hacerlos visibles
  if (!modelo3D) {
    // Si el modelo aún no cargó, reintentar en 300ms
    setTimeout(() => cargarAccesorioEnScene3D(id), 300);
    return;
  }
  aplicarMaterialesAccesorio(id);
  reencuadrarUniforme();
}

/* ═══════════════════════════════════════════════════════════════
   ACCESORIO INDEPENDIENTE (pantalón de la chompa)
   A diferencia de pantaloneta/medias (embebidos en modeloCompleto.glb),
   el pantalón viene en su propio archivo GLB. Se carga UNA sola vez, se
   agrega directo a scene3d (no como hijo de modelo3D, que se destruye y
   recrea en cada cambio de prenda) y se reposiciona bajo la prenda
   activa cada vez que se activa.
   ═══════════════════════════════════════════════════════════════ */
function cargarAccesorioIndependiente(id) {
  const acc = ACCESORIOS[id];

  if (acc.grupo) {
    acc.grupo.visible = true;
    posicionarAccesorioIndependiente(id);
    reencuadrarUniforme();
    return;
  }
  if (acc.cargando) return;
  if (!modelo3D) {
    // Esperar a que la prenda (chompa) ya esté cargada, para poder
    // posicionar el pantalón relativo a su bounding box.
    setTimeout(() => cargarAccesorioIndependiente(id), 300);
    return;
  }

  acc.cargando = true;
  const loaderAcc = new THREE.GLTFLoader();
  loaderAcc.load(
    acc.ruta,
    gltf => {
      acc.cargando = false;
      const grupo = gltf.scene;

      grupo.traverse(obj => {
        if (!obj.isMesh) return;
        const esMio = acc.meshNames.includes(obj.name);
        obj.visible = esMio;
        if (esMio) {
          obj.castShadow = true;
          obj.receiveShadow = true;
          obj.material = acc.materiales[obj.name]
            || new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.7, metalness: 0.05 });
        }
      });

      acc.grupo = grupo;
      scene3d.add(grupo);
      posicionarAccesorioIndependiente(id);
      reencuadrarUniforme();
    },
    undefined,
    err => {
      acc.cargando = false;
      console.error('[Accesorio ' + id + '] Error cargando modelo:', err);
    }
  );
}

/* Centra el pantalón en X/Z con la prenda activa y pega su borde
   superior justo debajo del borde inferior de esa prenda (con un
   pequeño solape para que no quede un hueco visible entre ambos). */
function posicionarAccesorioIndependiente(id) {
  const acc = ACCESORIOS[id];
  if (!acc.grupo || !modelo3D) return;

  acc.grupo.position.set(0, 0, 0);
  acc.grupo.scale.setScalar(1);
  acc.grupo.updateMatrixWorld(true);

  const boxAcc = new THREE.Box3();
  acc.grupo.traverse(o => { if (o.isMesh && acc.meshNames.includes(o.name)) boxAcc.expandByObject(o); });
  if (boxAcc.isEmpty()) return;

  modelo3D.updateMatrixWorld(true);
  const boxPrenda = new THREE.Box3();
  modelo3D.traverse(o => { if (o.isMesh && o.visible) boxPrenda.expandByObject(o); });
  if (boxPrenda.isEmpty()) return;

  const sizeAcc    = new THREE.Vector3(); boxAcc.getSize(sizeAcc);
  const centerAcc  = new THREE.Vector3(); boxAcc.getCenter(centerAcc);
  const centerPrenda = new THREE.Vector3(); boxPrenda.getCenter(centerPrenda);

  const solapeY     = sizeAcc.y * 0.06;
  const destinoTopY = boxPrenda.min.y + solapeY;
  const offsetY     = destinoTopY - boxAcc.max.y;

  acc.grupo.position.set(
    centerPrenda.x - centerAcc.x,
    offsetY,
    centerPrenda.z - centerAcc.z
  );
  acc.grupo.updateMatrixWorld(true);
}

function aplicarMaterialesAccesorio(id) {
  const acc = ACCESORIOS[id];
  if (!modelo3D) return;

  modelo3D.traverse(obj => {
    if (!obj.isMesh) return;
    if (!acc.meshNames.includes(obj.name)) return;
    obj.visible = true;
    obj.castShadow = true;

    if (obj.name === MESH_PANTALONETA_PRINCIPAL && acc.materialesAtras[obj.name]) {
      dividirMeshFrenteAtras(obj);
      obj.material = [acc.materiales[obj.name], acc.materialesAtras[obj.name]];
      return;
    }

    obj.material = acc.materiales[obj.name]
      || acc.materiales[acc.meshNames[0]]
      || new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.7 });
  });
}

function ocultarAccesorioEnScene3D(id) {
  const acc = ACCESORIOS[id];
  if (acc.independiente) {
    if (acc.grupo) acc.grupo.visible = false;
    return;
  }
  if (!modelo3D) return;
  modelo3D.traverse(obj => {
    if (obj.isMesh && acc.meshNames.includes(obj.name)) obj.visible = false;
  });
}

/* Reencuadrar cámara para mostrar todo el uniforme visible */
function reencuadrarUniforme() {
  if (!scene3d || !camera3d || !controls3d) return;

  const hayAccesorios = Object.values(ACCESORIOS).some(a => a.activo);

  if (!hayAccesorios) {
    // Sin accesorios: restaurar visibilidad de meshes de la camiseta
    // y volver al centrado normal
    if (modelo3D && typeof MESH_COLORES !== 'undefined') {
      const COLORES_CAMISETA = [
        'colorFrente','colorAtras','colorMangas',
        'colorParteAbajoMangas','colorCuello','colorParteAbajoCamiseta'
      ];
      modelo3D.traverse(obj => {
        if (!obj.isMesh) return;
        let esCamiseta = false;
        for (const key of COLORES_CAMISETA) {
          if (MESH_COLORES[key] && MESH_COLORES[key].includes(obj.name)) {
            esCamiseta = true; break;
          }
        }
        // Solo mostrar meshes de camiseta; mantener ocultos los de accesorios
        const esAccesorio = Object.values(ACCESORIOS).some(acc =>
          acc.meshNames.includes(obj.name)
        );
        const esOcultoBase = (typeof MESHES_OCULTOS !== 'undefined') &&
          (MESHES_OCULTOS.includes(obj.name) ||
           (obj.parent && MESHES_OCULTOS.includes(obj.parent.name)));
        if (esOcultoBase) { obj.visible = false; return; }
        if (esAccesorio) { obj.visible = false; return; }
        if (esCamiseta)  { obj.visible = true;  return; }
      });
    }
    if (typeof centrarModelo3D === 'function') centrarModelo3D();
    return;
  }

  // Con accesorios: medir bbox de todo lo visible
  const box = new THREE.Box3();
  scene3d.traverse(obj => { if (obj.isMesh && obj.visible) box.expandByObject(obj); });
  if (box.isEmpty()) return;

  const center = new THREE.Vector3();
  const size   = new THREE.Vector3();
  box.getCenter(center);
  box.getSize(size);

  const maxDim    = Math.max(size.x, size.y, size.z);
  const fov       = camera3d.fov * (Math.PI / 180);
  const distancia = (maxDim / 2) / Math.tan(fov / 2) * 1.15;
  // Guardamos también aquí la distancia de encuadre (variable compartida
  // con three-viewer.js): capturarVistas3D() la usa para las fotos de
  // frente/atrás, y sin esto quedaba con el valor calculado solo para la
  // camiseta (más chico), dejando pantaloneta/medias diminutas y cortadas
  // en el borde inferior de la captura.
  distanciaCamara3D = distancia;

  camera3d.position.set(0, center.y, distancia);
  camera3d.lookAt(center);
  controls3d.target.copy(center);
  controls3d.minDistance = distancia * 0.2;
  controls3d.maxDistance = distancia * 5;
  controls3d.update();
}

/* ═══════════════════════════════════════════════════════════════
   SVG 2D
   ═══════════════════════════════════════════════════════════════ */
function renderizarSVGaccesorio(id) {
  const wrap = document.getElementById('acc-svg-' + id);
  if (!wrap) return;
  wrap.innerHTML = getSVGaccesorio(id);
  const svg = wrap.querySelector('svg');
  if (svg) { svg.style.width = '100%'; svg.style.height = '100%'; }
}

/* ═══════════════════════════════════════════════════════════════
   VISOR 3D INDEPENDIENTE
   ═══════════════════════════════════════════════════════════════ */
function inicializarVisorAcc(id) {
  const acc      = ACCESORIOS[id];
  const canvasEl = document.getElementById('canvas-3d-' + id);
  const wrap     = document.getElementById('visor-wrap-' + id);
  if (!canvasEl || !wrap) return;

  const W = wrap.clientWidth  || 280;
  const H = wrap.clientHeight || 150;

  /* Renderer */
  acc.renderer = new THREE.WebGLRenderer({ canvas: canvasEl, antialias: true });
  acc.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  acc.renderer.setClearColor(0x0a1628, 1);
  acc.renderer.setSize(W, H);

  /* Redimensionar si cambia el contenedor */
  new ResizeObserver(() => {
    const w = wrap.clientWidth;
    const h = wrap.clientHeight;
    if (w > 0 && h > 0 && acc.renderer) {
      acc.renderer.setSize(w, h);
      if (acc.camera) { acc.camera.aspect = w / h; acc.camera.updateProjectionMatrix(); }
    }
  }).observe(wrap);

  /* Escena */
  acc.scene = new THREE.Scene();
  acc.scene.background = new THREE.Color(0x0a1628);

  /* Cámara */
  acc.camera = new THREE.PerspectiveCamera(42, W / H, 0.01, 10000);
  acc.camera.position.set(0, 0, 5);

  /* Controls */
  acc.controls = new THREE.OrbitControls(acc.camera, canvasEl);
  acc.controls.enableDamping = true;
  acc.controls.dampingFactor = 0.08;
  acc.controls.enablePan     = false;

  /* Luces */
  acc.scene.add(new THREE.AmbientLight(0xffffff, 0.85));
  const dl = new THREE.DirectionalLight(0xffffff, 1.2);
  dl.position.set(3, 5, 4);
  acc.scene.add(dl);
  const dl2 = new THREE.DirectionalLight(0xffffff, 0.4);
  dl2.position.set(-3, 2, -3);
  acc.scene.add(dl2);

  /* Cargar modelo */
  cargarModeloVisorAcc(id);

  /* Loop de animación */
  reanudarAnimAcc(id);
}

function cargarModeloVisorAcc(id) {
  const acc  = ACCESORIOS[id];
  const ruta = RUTAS_MODELO.completo;  // Todos los meshes vienen del modelo único
  if (!acc.scene || !ruta) { console.error('[Acc] Sin escena o ruta para', id); return; }

  const loaderAcc = new THREE.GLTFLoader();
  loaderAcc.load(
    ruta,
    gltf => {
      const grupo = gltf.scene;

      /* Asignar material por zona, mostrar solo meshes del accesorio */
      let encontrados = 0;
      grupo.traverse(o => { if (o.isMesh && acc.meshNames.includes(o.name)) encontrados++; });
      console.log('[Visor ' + id + '] meshes encontrados:', encontrados);

      grupo.traverse(obj => {
        if (!obj.isMesh) return;
        const esMio = acc.meshNames.includes(obj.name);
        obj.visible = encontrados === 0 || esMio;
        if (obj.visible) {
          obj.castShadow = true;
          // Material específico por zona
          obj.material = acc.materiales[obj.name]
            || acc.materiales[acc.meshNames[0]]
            || new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.7 });
        }
      });

      /* Agregar a escena en posición 0 */
      grupo.scale.setScalar(1);
      grupo.position.set(0, 0, 0);
      acc.modelo = grupo;
      acc.scene.add(grupo);

      /* Forzar update de matrices para poder medir el bbox */
      acc.scene.updateMatrixWorld(true);

      const box = new THREE.Box3();
      grupo.traverse(o => { if (o.isMesh && o.visible) box.expandByObject(o); });

      if (box.isEmpty()) {
        console.warn('[Visor ' + id + '] bbox vacío, mostrando todo');
        grupo.traverse(o => {
          if (o.isMesh) {
            o.visible = true;
            o.material = acc.materiales[o.name] || acc.materiales[acc.meshNames[0]]
              || new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.7 });
          }
        });
        acc.scene.updateMatrixWorld(true);
        grupo.traverse(o => { if (o.isMesh && o.visible) box.expandByObject(o); });
      }

      if (!box.isEmpty()) {
        const center = new THREE.Vector3();
        const size   = new THREE.Vector3();
        box.getCenter(center);
        box.getSize(size);

        /* Escalar para llenar el visor */
        const maxDim = Math.max(size.x, size.y, size.z);
        const target = 2.0;
        const escala = target / maxDim;
        grupo.scale.setScalar(escala);

        /* Centrar */
        grupo.position.set(-center.x * escala, -center.y * escala, -center.z * escala);

        /* Ajustar cámara */
        const fov  = acc.camera.fov * (Math.PI / 180);
        const dist = (target / 2) / Math.tan(fov / 2) * 1.5;
        acc.camera.position.set(0, 0, dist);
        acc.camera.lookAt(0, 0, 0);
        acc.controls.target.set(0, 0, 0);
        acc.controls.minDistance = dist * 0.3;
        acc.controls.maxDistance = dist * 5;
        acc.controls.update();

        console.log('[Visor ' + id + '] cargado OK — escala:', escala.toFixed(4), 'dist:', dist.toFixed(3));
      }
    },
    undefined,
    err => console.error('[Visor ' + id + '] Error carga:', err)
  );
}

function reanudarAnimAcc(id) {
  const acc = ACCESORIOS[id];
  if (acc.animFrame) return;
  (function loop() {
    if (!acc.activo) { acc.animFrame = null; return; }
    acc.animFrame = requestAnimationFrame(loop);
    if (acc.controls) acc.controls.update();
    if (acc.renderer && acc.scene && acc.camera) acc.renderer.render(acc.scene, acc.camera);
  })();
}

function pausarAnimAcc(id) {
  const acc = ACCESORIOS[id];
  if (acc.animFrame) { cancelAnimationFrame(acc.animFrame); acc.animFrame = null; }
}

/* ═══════════════════════════════════════════════════════════════
   APLICAR COLOR
   ═══════════════════════════════════════════════════════════════ */
// colorKey: clave del estado (ej: 'colorPantaloneta', 'colorParteAbajoPant')
function aplicarColorAccesorio(id, colorKey, color) {
  const acc = ACCESORIOS[id];
  if (!acc) return;
  color = color.startsWith('#') ? color : '#' + color;

  // Actualizar estado global
  estado[colorKey] = color;

  // Actualizar material del mesh correspondiente
  const meshName = Object.entries(acc.colorKeys).find(([m,k]) => k === colorKey)?.[0];
  if (meshName && acc.materiales[meshName]) {
    acc.materiales[meshName].color.set(color);
    acc.materiales[meshName].needsUpdate = true;
  }
  // La cara trasera (ver dividirMeshFrenteAtras) sigue el mismo color base
  if (meshName && acc.materialesAtras && acc.materialesAtras[meshName]) {
    acc.materialesAtras[meshName].color.set(color);
    acc.materialesAtras[meshName].needsUpdate = true;
  }

  // Actualizar dot del selector de zona
  const dot = document.getElementById('acc-dot-' + colorKey);
  if (dot) dot.style.background = color;

  // El picker/hex/paleta son compartidos por todas las zonas del
  // accesorio — solo se actualizan si el color cambiado es el de la
  // zona actualmente activa (si no, se aplicó a la otra zona o vino
  // de restaurar un borrador, y no debe pisar lo que se ve ahora).
  if (acc.zonaActiva === colorKey) {
    const picker = document.getElementById('acc-picker-' + id);
    const hexInp = document.getElementById('acc-hex-' + id);
    if (picker) picker.value = color;
    if (hexInp) hexInp.value = color;
    document.querySelectorAll('#acc-swatches-' + id + ' .swatch').forEach(sw => {
      sw.classList.toggle('sel', sw.dataset.color === color.toLowerCase());
    });
  }

  // Redibujar SVG 2D del accesorio (o canvas Fabric para pantaloneta)
  if (id === 'pantaloneta') {
    if (typeof actualizarSiluetaPant === 'function') actualizarSiluetaPant();
    // Regenerar textura 3D si hay diseño activo
    if (typeof actualizarTexturaPantaloneta3D === 'function') actualizarTexturaPantaloneta3D();
  } else {
    renderizarSVGaccesorio(id);
  }
}

/* Aplica un color a la zona actualmente seleccionada del accesorio
   (usado por el picker/hex compartido, ver tab-colores.blade.php). */
function aplicarColorAccesorioActivo(id, color) {
  const acc = ACCESORIOS[id];
  if (!acc) return;
  aplicarColorAccesorio(id, acc.zonaActiva, color);
}

/* Cambia qué zona del accesorio controla el picker/hex/paleta
   compartidos, y refleja el color actual de esa zona en ellos. */
function seleccionarZonaAccesorio(id, colorKey, btnEl) {
  const acc = ACCESORIOS[id];
  if (!acc) return;
  acc.zonaActiva = colorKey;

  document.querySelectorAll('#acc-zona-selector-' + id + ' .destino-btn').forEach(b => b.classList.remove('active'));
  btnEl?.classList.add('active');

  const color = estado[colorKey] || '#ffffff';
  const picker = document.getElementById('acc-picker-' + id);
  const hexInp = document.getElementById('acc-hex-' + id);
  if (picker) picker.value = color;
  if (hexInp) hexInp.value = color;
  document.querySelectorAll('#acc-swatches-' + id + ' .swatch').forEach(sw => {
    sw.classList.toggle('sel', sw.dataset.color === color.toLowerCase());
  });
}

/* ─── Swatches ─── */
function initSwatchesAccesorio(id) {
  // Una sola paleta compartida por accesorio — se aplica a la zona
  // seleccionada en el selector de zona (ver seleccionarZonaAccesorio).
  const acc = ACCESORIOS[id];
  if (!acc) return;
  if (!acc.zonaActiva) acc.zonaActiva = Object.values(acc.colorKeys)[0];

  const cont = document.getElementById('acc-swatches-' + id);
  if (!cont) return;
  cont.innerHTML = '';
  PALETA_ACC.forEach(color => {
    const s = document.createElement('div');
    s.className = 'swatch';
    s.dataset.color = color.toLowerCase();
    s.style.background = color;
    if (color === '#ffffff') s.style.border = '2.5px solid #CBD5E1';
    s.onclick = () => aplicarColorAccesorioActivo(id, color);
    cont.appendChild(s);
  });
}

function sincronizarAccesoriosConPrenda() {
  // Pantaloneta/Medias viven en modeloCompleto.glb (camiseta) y no
  // existen en chompa.glb — cargarModelo3D ya oculta sus meshes al
  // cambiar de prenda. El Pantalón es al revés: es un accesorio
  // independiente (su propio GLB) que solo tiene sentido con la chompa.
  // Por eso cada accesorio se desactiva solo cuando la prenda activa
  // deja de ser la que le corresponde — así no se pisan entre sí.
  const esCamiseta = tipoPrendaActual === 'camiseta';

  Object.entries(ACCESORIOS).forEach(([id, acc]) => {
    // independiente=true → accesorio de chompa (aplica cuando NO es camiseta)
    // independiente=false → accesorio de camiseta (aplica cuando SÍ es camiseta)
    const aplica = acc.independiente ? !esCamiseta : esCamiseta;
    if (acc.activo && !aplica) toggleAccesorio(id);
  });

  document.getElementById('accesorios-camiseta-wrap').style.display = esCamiseta ? '' : 'none';
  document.getElementById('accesorios-chompa-wrap').style.display = esCamiseta ? 'none' : '';
}

function getColoresAccesorios() {
  return {
    color_pantaloneta      : ACCESORIOS.pantaloneta.activo ? estado.colorPantaloneta       : '#ffffff',
    color_parte_abajo_pant : ACCESORIOS.pantaloneta.activo ? estado.colorParteAbajoPant    : '#ffffff',
    color_medias           : ACCESORIOS.medias.activo      ? estado.colorMedias            : '#ffffff',
    color_partearriba_med  : ACCESORIOS.medias.activo      ? estado.colorPartearribaMedias : '#ffffff',
    color_pantalon_chompa  : ACCESORIOS.pantalon.activo    ? estado.chompaColorPantalon    : '#ffffff',
    pantaloneta_activa     : ACCESORIOS.pantaloneta.activo ? '1' : '0',
    medias_activas         : ACCESORIOS.medias.activo      ? '1' : '0',
    pantalon_chompa_activo : ACCESORIOS.pantalon.activo    ? '1' : '0',
  };
}