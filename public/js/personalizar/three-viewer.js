let renderer3d, scene3d, camera3d, controls3d, modelo3D, texturaCanvas;
let distanciaCamara3D = 2.4;
let materiales3D = {};
let texturas3D = {};
let loader3D;
let texturaVersion = {};
let autoRotateTimer;

function init3D() {
  const canvas3d = document.getElementById('canvas-3d');
  const visor    = document.getElementById('visor-3d');

  renderer3d = new THREE.WebGLRenderer({ canvas:canvas3d, antialias:true, preserveDrawingBuffer:true });
  renderer3d.setPixelRatio(window.devicePixelRatio);
  renderer3d.shadowMap.enabled = true;
  renderer3d.shadowMap.type    = THREE.PCFSoftShadowMap;

  function resize3d() {
    const w = Math.max(visor.clientWidth,  1);
    const h = Math.max(visor.clientHeight - 36, 1);
    renderer3d.setSize(w, h);
    if(camera3d){ camera3d.aspect = w/h; camera3d.updateProjectionMatrix(); }
  }
  resize3d();
  new ResizeObserver(resize3d).observe(visor);

  scene3d = new THREE.Scene();
  scene3d.background = new THREE.Color(0x0f172a);

  camera3d = new THREE.PerspectiveCamera(42, 1, 0.1, 100);
  camera3d.position.set(0, 0, 2.4);

  controls3d = new THREE.OrbitControls(camera3d, canvas3d);
  controls3d.enableDamping = true;
  controls3d.dampingFactor = 0.08;
  controls3d.minDistance   = 0.5;
  controls3d.maxDistance   = 8;

  // Autorotación lenta al entrar: le da una pista visual al cliente de que
  // el modelo se puede girar con el mouse. Se apaga en cuanto el usuario
  // empieza a arrastrar (evento "start") y se reactiva sola tras un rato
  // de inactividad (evento "end" + timeout), en vez de quedar apagada para
  // siempre tras la primera interacción.
  controls3d.autoRotate = true;
  controls3d.autoRotateSpeed = 1.4;
  controls3d.addEventListener('start', () => {
    controls3d.autoRotate = false;
    clearTimeout(autoRotateTimer);
  });
  controls3d.addEventListener('end', () => {
    clearTimeout(autoRotateTimer);
    autoRotateTimer = setTimeout(() => { controls3d.autoRotate = true; }, 4000);
  });

  // Luces
  scene3d.add(new THREE.AmbientLight(0xffffff, 0.6));
  const dirLight = new THREE.DirectionalLight(0xffffff, 1.2);
  dirLight.position.set(3, 6, 4);
  dirLight.castShadow = true;
  scene3d.add(dirLight);
  const dirLight2 = new THREE.DirectionalLight(0xffffff, 0.4);
  dirLight2.position.set(-3, 2, -3);
  scene3d.add(dirLight2);

  loader3D = new THREE.GLTFLoader();
  cargarModelo3D();

  animar3D();
}

/* Gira la cámara alrededor del modelo un paso fijo (usado por las flechas
   del visor). Se apoya en coordenadas esféricas alrededor de
   controls3d.target para no pelear con OrbitControls. */
function rotarVisor3D(direccion) {
  if (!controls3d || !camera3d) return;
  controls3d.autoRotate = false;
  clearTimeout(autoRotateTimer);

  const offset = new THREE.Vector3().copy(camera3d.position).sub(controls3d.target);
  const spherical = new THREE.Spherical().setFromVector3(offset);
  const paso = Math.PI / 8;
  if (direccion === 'left')  spherical.theta -= paso;
  if (direccion === 'right') spherical.theta += paso;
  if (direccion === 'up')    spherical.phi = Math.max(0.15, spherical.phi - paso);
  if (direccion === 'down')  spherical.phi = Math.min(Math.PI - 0.15, spherical.phi + paso);
  offset.setFromSpherical(spherical);

  camera3d.position.copy(controls3d.target).add(offset);
  camera3d.lookAt(controls3d.target);
  controls3d.update();

  autoRotateTimer = setTimeout(() => { controls3d.autoRotate = true; }, 4000);
}

function resetearCamara() {
  camera3d.position.set(0, 0, 2.4);
  camera3d.lookAt(0, 0, 0);
  if(controls3d) {
    controls3d.target.set(0, 0, 0);
    controls3d.update();
  }
}

function centrarModelo3D() {
  if(!modelo3D) return;

  modelo3D.position.set(0, 0, 0);
  // Forzar recálculo de matrixWorld: sin esto, expandByObject usa la
  // transformación obsoleta (previa al reset de posición) y la caja
  // delimitadora queda mal calculada, dejando la cámara apuntando lejos
  // del modelo real (se ve el visor 3D vacío).
  modelo3D.updateMatrixWorld(true);

  // Calcular bbox SOLO de meshes visibles (ignora pantaloneta/medias ocultas)
  const box = new THREE.Box3();
  modelo3D.traverse(obj => {
    if (obj.isMesh && obj.visible) box.expandByObject(obj);
  });

  if(box.isEmpty()) return;

  const center = new THREE.Vector3();
  const size   = new THREE.Vector3();
  box.getCenter(center);
  box.getSize(size);

  modelo3D.position.set(-center.x, -center.y, -center.z);
  modelo3D.updateMatrixWorld(true);

  const maxDim    = Math.max(size.x, size.y, size.z);
  const fov       = camera3d.fov * (Math.PI / 180);
  const distancia = (maxDim / 2) / Math.tan(fov / 2) * 1.85;
  distanciaCamara3D = distancia;

  camera3d.position.set(0, 0, distancia);
  camera3d.lookAt(0, 0, 0);
  if(controls3d) {
    controls3d.target.set(0, 0, 0);
    controls3d.minDistance = distancia * 0.25;
    controls3d.maxDistance = distancia * 5;
    controls3d.update();
  }
}

// Toma capturas del visor 3D desde el frente y desde atrás (girando la
// cámara 180° alrededor del target), usada al guardar el diseño para que
// el cliente pueda ver ambas caras en "Mis diseños". Deja la cámara tal
// como estaba al terminar, para no desorientar al usuario en el visor.
function capturarVistas3D() {
  if(!renderer3d || !scene3d || !camera3d) return { frente: null, atras: null };

  const posOriginal    = camera3d.position.clone();
  const targetOriginal = controls3d ? controls3d.target.clone() : new THREE.Vector3(0,0,0);
  const distancia      = distanciaCamara3D || camera3d.position.distanceTo(targetOriginal) || 2.4;

  camera3d.position.set(targetOriginal.x, targetOriginal.y, targetOriginal.z + distancia);
  camera3d.lookAt(targetOriginal);
  renderer3d.render(scene3d, camera3d);
  const frente = renderer3d.domElement.toDataURL('image/png');

  camera3d.position.set(targetOriginal.x, targetOriginal.y, targetOriginal.z - distancia);
  camera3d.lookAt(targetOriginal);
  renderer3d.render(scene3d, camera3d);
  const atras = renderer3d.domElement.toDataURL('image/png');

  camera3d.position.copy(posOriginal);
  camera3d.lookAt(targetOriginal);
  renderer3d.render(scene3d, camera3d);

  return { frente, atras };
}

function crearMateriales3D() {
  materiales3D = {};
  // Un material por cada clave de color (no por vista)
  // Esto permite que varios meshes compartan el mismo material
  Object.keys(MESH_COLORES).forEach(colorKey => {
    materiales3D[colorKey] = new THREE.MeshStandardMaterial({
      color: estado[colorKey] || '#ffffff',
      roughness: 0.7,
      metalness: 0.05,
    });
  });
  // Compatibilidad con chompa (por vista)
  if (tipoPrendaActual === 'chompa') {
    Object.entries(VISTAS).forEach(([vista, info]) => {
      if (!materiales3D[vista]) {
        materiales3D[vista] = new THREE.MeshStandardMaterial({
          color: estado[info.key] || '#ffffff',
          roughness: 0.7,
          metalness: 0.05,
        });
      }
    });
  }
}

function limpiarTexturas3D() {
  Object.values(texturas3D).forEach(tex => tex?.dispose?.());
  texturas3D = {};
  texturaVersion = {};
}

function limpiarModelo3D() {
  if(!modelo3D) return;
  scene3d.remove(modelo3D);
  modelo3D.traverse(obj => {
    if(!obj.isMesh) return;
    if(obj.geometry) obj.geometry.dispose?.();
    if(obj.material && !Object.values(materiales3D).includes(obj.material)) {
      if(Array.isArray(obj.material)) obj.material.forEach(m => m.dispose?.());
      else obj.material.dispose?.();
    }
  });
  mapaUVMeshCache.clear();
  modelo3D = null;
}

function cargarModelo3D() {
  if(!scene3d || !loader3D) return;

  limpiarModelo3D();
  limpiarTexturas3D();
  crearMateriales3D();
  resetearCamara();

  const prenda = PRENDAS[tipoPrendaActual];
  loader3D.load(prenda.ruta, gltf => {
    if(prenda !== PRENDAS[tipoPrendaActual]) return;

    modelo3D = gltf.scene;

    modelo3D.traverse(obj => {
      if (!obj.isMesh) return;

      const nombre = obj.name || '';

      // Ocultar meshes base/auxiliares (tanto por nombre propio como por padre)
      const esOculto = (typeof MESHES_OCULTOS !== 'undefined') &&
        (MESHES_OCULTOS.includes(nombre) ||
         (obj.parent && MESHES_OCULTOS.includes(obj.parent.name)));
      if (esOculto) {
        obj.visible = false;
        return;
      }

      // Modelo completo (camiseta): solo mostrar meshes de la camiseta, NO pantaloneta/medias
      if (tipoPrendaActual === 'camiseta' && typeof MESH_COLORES !== 'undefined') {
        // Claves de color que pertenecen SOLO a la camiseta
        const COLORES_CAMISETA = [
          'colorFrente','colorAtras','colorMangas',
          'colorParteAbajoMangas','colorCuello','colorParteAbajoCamiseta'
        ];
        let colorKey = null;
        for (const key of COLORES_CAMISETA) {
          if (MESH_COLORES[key] && MESH_COLORES[key].includes(nombre)) { colorKey = key; break; }
        }
        if (colorKey && materiales3D[colorKey]) {
          obj.visible = true;
          obj.castShadow = true;
          obj.receiveShadow = true;
          obj.material = materiales3D[colorKey];
        } else {
          obj.visible = false;
        }
        return;
      }

      // Chompa: buscar por meshMap de la prenda
      const meshName = obj.geometry?.name || '';
      const vista = prenda.meshMap?.[nombre] || prenda.meshMap?.[meshName];
      if (!vista) { obj.visible = false; return; }

      obj.visible = true;
      obj.castShadow = true;
      obj.receiveShadow = true;
      obj.material = materiales3D[vista] || new THREE.MeshStandardMaterial({
        color: 0xffffff, roughness: 0.7, metalness: 0.05,
      });
    });

    // Corregir rotación del modelo si viene rotado del GLB
    modelo3D.rotation.set(0, 0, 0);

    scene3d.add(modelo3D);
    // Centrar automáticamente con bounding box real
    centrarModelo3D();
    actualizarTodasTexturas3D();
    // Reaplicar accesorios activos sobre el nuevo modelo
    if (typeof ACCESORIOS !== 'undefined') {
      Object.entries(ACCESORIOS).forEach(([id, acc]) => {
        if (acc.activo) {
          if (typeof aplicarMaterialesAccesorio === 'function') aplicarMaterialesAccesorio(id);
        }
      });
      if (Object.values(ACCESORIOS).some(a => a.activo)) {
        setTimeout(() => { if (typeof reencuadrarUniforme === 'function') reencuadrarUniforme(); }, 100);
      }
    }
  }, undefined, err => console.error("Error cargando modelo 3D:", err));
}

function actualizarColor3D(tipo, color) {
  estado[tipo] = color;

  // Actualizar material por colorKey (modelo completo)
  if (materiales3D[tipo]) {
    materiales3D[tipo].color.set(color);
    materiales3D[tipo].needsUpdate = true;
  }

  // Actualizar también por vista (chompa)
  const vista = vistaDesdeKeyColor(tipo);
  if (vista && materiales3D[vista]) {
    materiales3D[vista].color.set(color);
    materiales3D[vista].needsUpdate = true;
  }

  if (vista) actualizarTexturaVista3D(vista);
  if (renderer3d && scene3d && camera3d) renderer3d.render(scene3d, camera3d);
}

function vistaDesdeKeyColor(key) {
  return Object.keys(VISTAS).find(v => VISTAS[v].key3d === key || VISTAS[v].key === key);
}

function materialPorVista(vista) {
  // Primero buscar por vista directamente (chompa)
  if (materiales3D[vista]) return materiales3D[vista];
  // Luego buscar por colorKey de la vista (camiseta con modeloCompleto)
  const colorKey = VISTAS[vista]?.key;
  if (colorKey && materiales3D[colorKey]) return materiales3D[colorKey];
  return null;
}

function crearCanvasTextura(vista, callback) {
  const el = document.createElement('canvas');
  el.width = CANVAS_W;
  el.height = CANVAS_H;
  const tmp = new fabric.StaticCanvas(el, {
    width: CANVAS_W,
    height: CANVAS_H,
    backgroundColor: estado[VISTAS[vista].key],
  });

  const limpiarYTerminar = () => {
    tmp.getObjects().slice().forEach(o => {
      if(esObjetoSistema(o)) tmp.remove(o);
      if(!vistaPermiteDiseno(vista) && !esObjetoSistema(o)) tmp.remove(o);
    });
    normalizarTextosCanvas(tmp);
    ajustarObjetosParaTextura3D(tmp, vista);
    tmp.backgroundColor = estado[VISTAS[vista].key];
    tmp.renderAll();
    callback(el);
    setTimeout(() => tmp.dispose(), 0);
  };

  if(vista === vistaActual && fabricCanvas) {
    tmp.loadFromJSON(fabricCanvas.toJSON(FABRIC_PROPS), limpiarYTerminar);
    return;
  }

  if(canvasData[vista]) {
    tmp.loadFromJSON(canvasData[vista], limpiarYTerminar);
    return;
  }

  limpiarYTerminar();
}

function ajustarObjetosParaTextura3D(canvasTmp, vista) {
  const cfg = TEXTURA_3D_AJUSTE[vista];
  if(!cfg) return;

  const cx = CANVAS_W / 2;
  const cy = CANVAS_H / 2;

  canvasTmp.getObjects().forEach(obj => {
    if(esObjetoSistema(obj)) return;

    const left = typeof obj.left === 'number' ? obj.left : cx;
    const top  = typeof obj.top === 'number' ? obj.top : cy;
    const dirX = cfg.mirrorX ? -1 : 1;
    const dirY = cfg.invertY ? -1 : 1;

    obj.set({
      left: cx + ((left - cx) * dirX * cfg.scaleX) + cfg.offsetX,
      top:  cy + ((top - cy) * dirY * cfg.scaleY) + cfg.offsetY,
    });
    obj.setCoords();
  });
}

/* ═══════════════════════════════════════════════════════════════
   WARP UV GENÉRICO POR MALLA (usado por la chompa)
   Los paneles de chompa.glb tienen un desenvuelto UV muy irregular:
   aplicar el canvas de diseño directamente (como sí funciona en la
   camiseta) deja la textura invisible o reducida a un solo color.
   Para corregirlo, se dispara una rejilla de rayos contra la malla
   real (usando una cámara "de frente" calculada desde su propio
   bounding box) para saber a qué UV corresponde cada punto, y el
   canvas de diseño se pinta triángulo por triángulo con esa
   correspondencia — igual que el mapeo ya usado para la pantaloneta
   en canvas-pantaloneta.js (dibujarTrianguloTexturizado).
   ═══════════════════════════════════════════════════════════════ */
const mapaUVMeshCache = new Map();

function construirCamaraFrontalMesh(mesh, direccionForzada) {
  mesh.updateMatrixWorld(true);
  const box = new THREE.Box3().setFromObject(mesh);
  if (box.isEmpty()) return null;
  const center = new THREE.Vector3();
  const size = new THREE.Vector3();
  box.getCenter(center);
  box.getSize(size);

  let dir;
  if (direccionForzada) {
    // Paneles como el torso (frente/atrás) o la capucha son, en la
    // práctica, planos que el cliente ve de frente/de espaldas — igual que
    // la cámara real del visor. Promediar las normales de la malla para
    // "adivinar" hacia dónde mira el panel resulta poco fiable cuando la
    // malla tiene curvatura o triangulación desigual (ej. "frente" del
    // chompa.glb promediaba a (-0.75,-0.24,0.62): la cámara de calibración
    // terminaba mirando casi de lado, y el diseño se proyectaba rotado y
    // deformado). Para esos paneles se fuerza el eje real que usa la
    // cámara del visor en vez de la normal promediada.
    dir = new THREE.Vector3(...direccionForzada).normalize();
  } else {
    // La dirección "hacia afuera" del panel se obtiene promediando las
    // normales reales de la malla (en espacio mundo). Antes se usaba el eje
    // más delgado del bbox asumiendo siempre signo positivo, lo cual es
    // falso para paneles que miran hacia el lado negativo de ese eje (ej.
    // "Atrás", que mira hacia -Z): la cámara de calibración terminaba
    // viendo la cara interior del panel y el diseño se mapeaba en una zona
    // UV que nunca es visible desde la cámara real del visor 3D.
    dir = new THREE.Vector3();
    const normalAttr = mesh.geometry.attributes.normal;
    if (normalAttr) {
      const normalMatrix = new THREE.Matrix3().getNormalMatrix(mesh.matrixWorld);
      const n = new THREE.Vector3();
      for (let i = 0; i < normalAttr.count; i++) {
        n.fromBufferAttribute(normalAttr, i).applyMatrix3(normalMatrix);
        dir.add(n);
      }
      dir.normalize();
    }
    if (dir.lengthSq() < 1e-6) {
      // Sin normales válidas: volver al criterio anterior (eje más delgado)
      dir.set(0, 0, 1);
      if (size.x <= size.y && size.x <= size.z) dir.set(1, 0, 0);
      else if (size.y <= size.x && size.y <= size.z) dir.set(0, 1, 0);
    }
  }

  // Distancia/FOV holgados a propósito: es preferible que la cámara de
  // calibración vea DE MÁS (incluyendo algo de fondo) a que se quede
  // corta y deje sin mapear zonas del panel que sí son visibles desde
  // la cámara real del visor.
  const diag = size.length();
  const cam = new THREE.PerspectiveCamera(75, 1, 0.01, 100);
  cam.position.copy(center).addScaledVector(dir, Math.max(diag * 0.9, 0.05));
  cam.lookAt(center);
  cam.updateMatrixWorld(true);
  return cam;
}

function construirMapaUVMesh(mesh, cols = 24, rows = 24, direccionForzada) {
  if (mapaUVMeshCache.has(mesh)) return mapaUVMeshCache.get(mesh);

  const camTemp = construirCamaraFrontalMesh(mesh, direccionForzada);
  if (!camTemp) { mapaUVMeshCache.set(mesh, null); return null; }

  const rc = new THREE.Raycaster();
  const golpear = (nx, ny) => {
    rc.setFromCamera(new THREE.Vector2(nx, ny), camTemp);
    const hits = rc.intersectObject(mesh, false);
    return hits.length ? { u: hits[0].uv.x, v: hits[0].uv.y } : null;
  };

  // Pequeño desfase para que ningún punto de muestreo caiga EXACTAMENTE
  // en el eje óptico (NDC 0,0): la cámara de calibración siempre mira al
  // centro del bbox, y el punto donde el cliente coloca un diseño nuevo
  // también es el centro del lienzo — si coinciden justo con una costura
  // o pliegue de la malla (ej. el cierre de la chompa), el diseño caería
  // siempre en ese punto ciego. El desfase evita que ambos centros calcen.
  const JITTER_X = 0.027, JITTER_Y = 0.019;

  let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
  // 18 en vez de 30: son cientos de raycasts menos por panel (esto se
  // repite en cada apertura de la chompa, ya que el cache se invalida al
  // recargar el modelo) sin pérdida perceptible en la calidad del mapeo.
  const SCAN = 18;
  for (let j = 0; j <= SCAN; j++) {
    for (let i = 0; i <= SCAN; i++) {
      const nx = -1 + 2 * i / SCAN + JITTER_X, ny = -1 + 2 * j / SCAN + JITTER_Y;
      if (golpear(nx, ny)) {
        if (nx < minX) minX = nx; if (nx > maxX) maxX = nx;
        if (ny < minY) minY = ny; if (ny > maxY) maxY = ny;
      }
    }
  }
  if (!isFinite(minX)) { mapaUVMeshCache.set(mesh, null); return null; }

  const padX = (maxX - minX) * 0.05, padY = (maxY - minY) * 0.05;
  minX += padX; maxX -= padX; minY += padY; maxY -= padY;

  // Desfase de media celda: evita que un VÉRTICE de la rejilla caiga
  // justo en el centro exacto (mismo motivo que JITTER_X/Y arriba).
  const medioX = ((maxX - minX) / cols) * 0.5;
  const medioY = ((maxY - minY) / rows) * 0.5;

  const grid = [];
  for (let j = 0; j <= rows; j++) {
    const ny = maxY - (maxY - minY) * (j / rows) - medioY;
    const fila = [];
    for (let i = 0; i <= cols; i++) {
      const nx = minX + (maxX - minX) * (i / cols) + medioX;
      fila.push(golpear(nx, ny));
    }
    grid.push(fila);
  }
  // NOTA: los puntos sin impacto (ej. la abertura del cierre de la chompa,
  // que separa el panel en dos mitades) se dejan en null a propósito — NO
  // se rellenan con el vecino más cercano, porque eso "puentearía" dos
  // paneles UV distintos con un valor inventado y estiraría el diseño de
  // uno al otro. dibujarDisenioConWarp() simplemente omite cualquier
  // triángulo que toque un punto null, dejando esa franja en el color de
  // fondo (igual que una costura real).

  // Proporción real (ancho/alto) del panel visible, medida en el mismo
  // espacio NDC que la rejilla de arriba. La cámara de calibración usa
  // aspect=1 y el mismo FOV en ambos ejes, así que esta razón aproxima
  // bien la proporción física del panel — se usa luego en
  // aplicarTexturaConWarp() para evitar estirar el diseño de forma no
  // uniforme cuando la "zona de diseño" 2D no comparte esa proporción.
  const aspect = (maxX - minX) / (maxY - minY);

  const resultado = { cols, rows, grid, aspect };
  mapaUVMeshCache.set(mesh, resultado);
  return resultado;
}

/* Distancia UV entre dos puntos de la rejilla — sirve para detectar
   "saltos" (dos paneles separados por un hueco, ej. la abertura del
   cierre de la chompa) y no estirar el diseño de un panel al otro. */
function distanciaUV(a, b) {
  const du = a.u - b.u, dv = a.v - b.v;
  return Math.sqrt(du * du + dv * dv);
}
const UMBRAL_DISCONTINUIDAD_UV = 0.4;
function esTrianguloContinuo(pA, pB, pC) {
  return distanciaUV(pA, pB) < UMBRAL_DISCONTINUIDAD_UV &&
         distanciaUV(pB, pC) < UMBRAL_DISCONTINUIDAD_UV &&
         distanciaUV(pA, pC) < UMBRAL_DISCONTINUIDAD_UV;
}

/* Pinta canvasEl (diseño ya armado en su vista plana) sobre un canvas
   cuadrado en espacio UV usando la rejilla de mapaUVMesh, y lo aplica
   como textura del material. Si no hay mapa disponible, cae de vuelta
   al mapeo directo simple.
   `zona` (opcional) acota el muestreo del canvas de origen a la zona de
   diseño de ese panel (ver PRENDAS.chompa.zonasDiseno): sin esto se
   muestreaba el canvas completo de 480x520 (fondo + diseño) y se
   estiraba sobre todo el panel visible, dejando el diseño real —que solo
   ocupa la zona— minúsculo y descentrado respecto a lo que se ve en el
   editor 2D. */
function aplicarTexturaConWarp(mat, canvasEl, mesh, texKey, colorFondo, zona, direccionForzada) {
  if (texturas3D[texKey]) texturas3D[texKey].dispose();

  const mapa = construirMapaUVMesh(mesh, 24, 24, direccionForzada);
  let fuente = canvasEl;

  const origenX = zona ? zona.x : 0;
  const origenY = zona ? zona.y : 0;
  const origenW = zona ? zona.w : canvasEl.width;
  const origenH = zona ? zona.h : canvasEl.height;

  if (mapa) {
    // Paneles como "atras" solo ocupan una porción pequeña del espacio UV
    // 0-1 completo (a diferencia de "frente", que lo usa casi todo). Con
    // un canvas intermedio chico, esa porción recibe muy pocos píxeles
    // reales y el diseño queda minúsculo/ilegible. Usar un tamaño grande
    // asegura resolución suficiente incluso para paneles con UV chico.
    const UV_SIZE = 2048;
    const intermedio = document.createElement('canvas');
    intermedio.width = UV_SIZE;
    intermedio.height = UV_SIZE;
    const ctx = intermedio.getContext('2d');
    // Fondo del color de la prenda — así las celdas que se saltan por
    // discontinuidad (huecos como el del cierre) no quedan en negro.
    ctx.fillStyle = colorFondo || '#ffffff';
    ctx.fillRect(0, 0, UV_SIZE, UV_SIZE);

    const { cols, rows, grid, aspect } = mapa;

    // La "zona de diseño" 2D casi nunca comparte la proporción real del
    // panel (aspect, medido arriba con la rejilla de rayos): estirar la
    // zona completa sobre el panel completo (como se hacía antes) deforma
    // el diseño de forma no uniforme — más ancho o más alto de lo que se
    // ve en el editor. Se calcula aquí un rectángulo "activo" dentro del
    // panel, con la misma proporción que la zona, centrado en él: fuera de
    // ese rectángulo simplemente no se dibuja nada (queda el color de
    // fondo), y dentro la zona se proyecta a escala uniforme.
    const zonaAspect = origenW / origenH;
    let activeW = 1, activeH = 1;
    if (aspect > 0 && isFinite(aspect)) {
      if (zonaAspect > aspect) activeH = aspect / zonaAspect;
      else activeW = zonaAspect / aspect;
    }
    const activeX0 = (1 - activeW) / 2;
    const activeY0 = (1 - activeH) / 2;
    const mapFrac = (f, base, size) => size > 0 ? (f - base) / size : f;

    for (let j = 0; j < rows; j++) {
      for (let i = 0; i < cols; i++) {
        const fx0 = mapFrac(i / cols, activeX0, activeW), fx1 = mapFrac((i + 1) / cols, activeX0, activeW);
        const fy0 = mapFrac(j / rows, activeY0, activeH), fy1 = mapFrac((j + 1) / rows, activeY0, activeH);
        if (fx1 <= 0 || fx0 >= 1 || fy1 <= 0 || fy0 >= 1) continue;
        const cx0 = Math.max(0, fx0), cx1 = Math.min(1, fx1);
        const cy0 = Math.max(0, fy0), cy1 = Math.min(1, fy1);
        const sx0 = origenX + cx0 * origenW, sx1 = origenX + cx1 * origenW;
        const sy0 = origenY + cy0 * origenH, sy1 = origenY + cy1 * origenH;
        const p00 = grid[j][i], p10 = grid[j][i + 1], p01 = grid[j + 1][i], p11 = grid[j + 1][i + 1];
        if (p00 && p10 && p01 && esTrianguloContinuo(p00, p10, p01)) {
          const d00 = { x: p00.u * UV_SIZE, y: p00.v * UV_SIZE };
          const d10 = { x: p10.u * UV_SIZE, y: p10.v * UV_SIZE };
          const d01 = { x: p01.u * UV_SIZE, y: p01.v * UV_SIZE };
          dibujarTrianguloTexturizado(ctx, canvasEl, { x: sx0, y: sy0 }, { x: sx1, y: sy0 }, { x: sx0, y: sy1 }, d00, d10, d01);
        }
        if (p10 && p11 && p01 && esTrianguloContinuo(p10, p11, p01)) {
          const d10 = { x: p10.u * UV_SIZE, y: p10.v * UV_SIZE };
          const d11 = { x: p11.u * UV_SIZE, y: p11.v * UV_SIZE };
          const d01 = { x: p01.u * UV_SIZE, y: p01.v * UV_SIZE };
          dibujarTrianguloTexturizado(ctx, canvasEl, { x: sx1, y: sy0 }, { x: sx1, y: sy1 }, { x: sx0, y: sy1 }, d10, d11, d01);
        }
      }
    }
    fuente = intermedio;
  }

  const tex = new THREE.CanvasTexture(fuente);
  tex.flipY = false;
  tex.wrapS = THREE.ClampToEdgeWrapping;
  tex.wrapT = THREE.ClampToEdgeWrapping;
  tex.needsUpdate = true;

  texturas3D[texKey] = tex;
  mat.map = tex;
  mat.color.set(0xffffff);
  mat.needsUpdate = true;
}

function aplicarCanvasAMaterial(vista, canvasEl) {
  const mat = materialPorVista(vista);
  if(!mat) return;
  const texKey = `${tipoPrendaActual}:${vista}`;
  const colorKey = VISTAS[vista]?.key;

  // Vistas de solo color: no canvas, solo color sólido
  if(VISTAS[vista]?.soloColor) {
    if(texturas3D[texKey]) {
      texturas3D[texKey].dispose();
      delete texturas3D[texKey];
    }
    mat.map = null;
    mat.color.set(estado[colorKey]);
    mat.needsUpdate = true;
    return;
  }

  // Chompa: el desenvuelto UV de sus mallas es irregular, se necesita
  // el warp por rejilla en vez del mapeo directo de abajo.
  if (tipoPrendaActual === 'chompa' && modelo3D) {
    const meshMap = PRENDAS.chompa.meshMap || {};
    let meshVista = null;
    modelo3D.traverse(obj => { if (obj.isMesh && meshMap[obj.name] === vista) meshVista = obj; });
    if (meshVista) {
      const zona = PRENDAS.chompa.zonasDiseno?.[vista];
      const dirCalibracion = PRENDAS.chompa.direccionesCalibracion?.[vista];
      aplicarTexturaConWarp(mat, canvasEl, meshVista, texKey, estado[colorKey], zona, dirCalibracion);
      return;
    }
  }

  // Vistas con diseño: aplicar canvas como textura
  if(texturas3D[texKey]) texturas3D[texKey].dispose();
  const tex = new THREE.CanvasTexture(canvasEl);
  tex.flipY = false;
  tex.wrapS = THREE.ClampToEdgeWrapping;
  tex.wrapT = THREE.ClampToEdgeWrapping;
  tex.needsUpdate = true;

  texturas3D[texKey] = tex;
  mat.map = tex;
  mat.color.set(0xffffff);
  mat.needsUpdate = true;

  // Para mangas: crear material espejo para la manga izquierda
  if (vista === 'mangas' && tipoPrendaActual === 'camiseta') {
    const texKeyMirror = `${tipoPrendaActual}:mangas-mirror`;
    if(texturas3D[texKeyMirror]) texturas3D[texKeyMirror].dispose();

    // Crear canvas espejo (flip horizontal)
    const mirrorEl = document.createElement('canvas');
    mirrorEl.width  = canvasEl.width;
    mirrorEl.height = canvasEl.height;
    const ctx = mirrorEl.getContext('2d');
    ctx.save();
    ctx.scale(-1, 1);
    ctx.drawImage(canvasEl, -canvasEl.width, 0);
    ctx.restore();

    const texMirror = new THREE.CanvasTexture(mirrorEl);
    texMirror.flipY = false;
    texMirror.wrapS = THREE.ClampToEdgeWrapping;
    texMirror.wrapT = THREE.ClampToEdgeWrapping;
    texMirror.needsUpdate = true;
    texturas3D[texKeyMirror] = texMirror;

    // Asignar textura espejo a la manga izquierda (mesh 005)
    if (modelo3D) {
      modelo3D.traverse(obj => {
        if (!obj.isMesh) return;
        if (obj.name === 'Soccer_Outfit_Kit_01_1001005') {
          // Crear material independiente para manga izquierda
          if (!obj._matMangaIzq) {
            obj._matMangaIzq = new THREE.MeshStandardMaterial({
              roughness: 0.7, metalness: 0.05,
            });
          }
          obj._matMangaIzq.color.set(0xffffff);
          obj._matMangaIzq.map = texMirror;
          obj._matMangaIzq.needsUpdate = true;
          obj.material = obj._matMangaIzq;
        }
      });
    }
  }
}

function actualizarTexturaVista3D(vista) {
  if(!modelo3D) return;
  const texKey = `${tipoPrendaActual}:${vista}`;
  texturaVersion[texKey] = (texturaVersion[texKey] || 0) + 1;
  const version = texturaVersion[texKey];
  const tipoAlSolicitar = tipoPrendaActual;
  crearCanvasTextura(vista, canvasEl => {
    if(tipoAlSolicitar !== tipoPrendaActual || version !== texturaVersion[texKey]) return;
    aplicarCanvasAMaterial(vista, canvasEl);
    if(renderer3d && scene3d && camera3d) renderer3d.render(scene3d, camera3d);
  });
}

function actualizarTodasTexturas3D() {
  // El warp UV de la chompa (construirMapaUVMesh) es costoso: varios miles
  // de raycasts por panel. Hacerlo de un tirón para las 6 vistas bloquea
  // el hilo principal antes de que se pinte el primer frame, así que el
  // modelo tarda en aparecer y el auto-rotate no arranca hasta que todo
  // termina. Se procesa una vista por frame para que el render (y el giro)
  // arranquen de inmediato con el material sólido y las texturas se vayan
  // aplicando encima en los frames siguientes.
  const vistas = Object.keys(VISTAS);
  const tipoAlIniciar = tipoPrendaActual;
  const modeloAlIniciar = modelo3D;
  let i = 0;
  function procesarSiguiente() {
    if (tipoPrendaActual !== tipoAlIniciar || modelo3D !== modeloAlIniciar) return;
    if (i >= vistas.length) return;
    actualizarTexturaVista3D(vistas[i]);
    i++;
    if (i < vistas.length) requestAnimationFrame(procesarSiguiente);
  }
  procesarSiguiente();
}

function actualizarTextura3D() {
  actualizarTexturaVista3D(vistaActual);
  if(texturaCanvas) texturaCanvas.needsUpdate = true;
  if(renderer3d && scene3d && camera3d) renderer3d.render(scene3d, camera3d);
}

function animar3D() {
  requestAnimationFrame(animar3D);
  if(controls3d) controls3d.update();
  if(texturaCanvas) texturaCanvas.needsUpdate = true;
  try {
    if(renderer3d && scene3d && camera3d) renderer3d.render(scene3d, camera3d);
  } catch(e) { /* ignorar errores de render transitorio */ }
}