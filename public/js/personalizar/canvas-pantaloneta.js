/* ═══════════════════════════════════════════════════════════════
   CANVAS 2D PANTALONETA — Fabric.js independiente
   Permite agregar logos, texto y figuras a la pantaloneta
   ═══════════════════════════════════════════════════════════════ */

const PANT_W = 148;
const PANT_H = 148;
let fabricPant = null;
let pantSiluetaVersion = 0;

/* SVG de silueta pantaloneta para el canvas */
function getSVGPantaloneta() {
  const c1 = estado.colorPantaloneta    || '#1565c0';
  const c2 = estado.colorParteAbajoPant || '#0d47a1';
  return `<svg width="${PANT_W}" height="${PANT_H}" viewBox="0 0 148 148" xmlns="http://www.w3.org/2000/svg">
    <!-- Cintura -->
    <rect x="14" y="8" width="120" height="14" rx="5" fill="${c1}"/>
    <!-- Cuerpo izquierda -->
    <path d="M14 22 L8 95 Q8 108 24 108 L60 108 L74 62 L74 22 Z" fill="${c1}"/>
    <!-- Cuerpo derecha -->
    <path d="M134 22 L140 95 Q140 108 124 108 L88 108 L74 62 L74 22 Z" fill="${c1}"/>
    <!-- Banda inferior izquierda -->
    <rect x="9" y="100" width="52" height="12" rx="4" fill="${c2}"/>
    <!-- Banda inferior derecha -->
    <rect x="87" y="100" width="52" height="12" rx="4" fill="${c2}"/>
    <!-- Línea central -->
    <line x1="74" y1="56" x2="74" y2="108" stroke="rgba(0,0,0,.1)" stroke-width="1"/>
  </svg>`;
}

/* Inicializar canvas Fabric.js de pantaloneta */
function initFabricPantaloneta() {
  const wrap = document.getElementById('acc-fabric-wrap-pantaloneta');
  if (!wrap) return;
  if (fabricPant) return; // ya inicializado

  // Forzar dimensiones en el elemento canvas HTML antes de que Fabric lo tome
  const canvasEl = document.getElementById('fabric-canvas-pantaloneta');
  if (!canvasEl) return;
  canvasEl.width  = PANT_W;
  canvasEl.height = PANT_H;

  wrap.style.width  = PANT_W + 'px';
  wrap.style.height = PANT_H + 'px';

  fabricPant = new fabric.Canvas('fabric-canvas-pantaloneta', {
    width: PANT_W,
    height: PANT_H,
    backgroundColor: '#f8fafc',
    selection: true,
    preserveDrawingBuffer: true,
  });

  refrescarSiluetaPant();

  // Eventos: limitar objetos a la zona imprimible (cuerpo de la pantaloneta)
  const ZONA_PANT = { x: 10, y: 22, w: 128, h: 78 };
  fabricPant.on('object:moving',  e => limitarObjZonaPant(e.target, ZONA_PANT));
  fabricPant.on('object:scaling', e => limitarObjZonaPant(e.target, ZONA_PANT));
  fabricPant.on('object:modified', () => { guardarHistorial(); actualizarTexturaPantaloneta3D(); });
  fabricPant.on('object:added',   e => { if (!esObjetoSistemaPant(e.target)) { guardarHistorial(); actualizarTexturaPantaloneta3D(); } });
  fabricPant.on('object:removed', e => { if (!esObjetoSistemaPant(e.target)) { guardarHistorial(); actualizarTexturaPantaloneta3D(); } });
  // after:render no — solo actualizar en cambios reales para no sobrecargar GPU
  fabricPant.on('selection:created', () => {
    document.getElementById('obj-toolbar')?.classList.add('visible');
  });
  fabricPant.on('selection:cleared', () => {
    // Solo ocultar toolbar si el canvas principal tampoco tiene selección
    if (!fabricCanvas?.getActiveObject()) {
      document.getElementById('obj-toolbar')?.classList.remove('visible');
    }
  });
}

function esObjetoSistemaPant(obj) {
  return obj && (obj.id === 'silueta-pant' || obj.id === 'zona-pant');
}

function refrescarSiluetaPant() {
  if (!fabricPant) return;
  const version = ++pantSiluetaVersion;

  // Limpiar silueta y zona anteriores
  fabricPant.getObjects().filter(o => esObjetoSistemaPant(o)).forEach(o => fabricPant.remove(o));

  const svgStr = getSVGPantaloneta();
  const url = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svgStr);

  fabric.Image.fromURL(url, img => {
    if (version !== pantSiluetaVersion) return;
    img.set({
      id: 'silueta-pant',
      left: 0, top: 0,
      selectable: false, evented: false,
    });
    fabricPant.insertAt(img, 0);

    // Zona de diseño
    const zona = new fabric.Rect({
      id: 'zona-pant',
      left: 14, top: 22,
      width: 120, height: 76,
      fill: 'rgba(37,99,235,0.03)',
      stroke: 'rgba(37,99,235,0.3)',
      strokeDashArray: [4, 4],
      strokeWidth: 1,
      selectable: false, evented: false,
    });
    fabricPant.add(zona);
    fabricPant.sendToBack(zona);
    fabricPant.sendToBack(img);
    fabricPant.renderAll();
  }, { crossOrigin: 'anonymous' });
}

function limitarObjZonaPant(obj, zona) {
  if (!obj || esObjetoSistemaPant(obj)) return;
  obj.setCoords();
  let r = obj.getBoundingRect(true, true);
  let dx = 0, dy = 0;
  if (r.left < zona.x) dx = zona.x - r.left;
  if (r.top  < zona.y) dy = zona.y - r.top;
  if (r.left + r.width  > zona.x + zona.w) dx = zona.x + zona.w - (r.left + r.width);
  if (r.top  + r.height > zona.y + zona.h) dy = zona.y + zona.h - (r.top  + r.height);
  if (dx || dy) { obj.left += dx; obj.top += dy; obj.setCoords(); }
}

/* Actualizar silueta cuando cambia el color de la pantaloneta */
function actualizarSiluetaPant() {
  refrescarSiluetaPant();
}

/* Agregar objeto al canvas activo (pantaloneta o camiseta) */
function agregarAlCanvasActivo(fabricObj) {
  // Si la pantaloneta está activa y visible, el usuario puede elegir
  // Por defecto agrega al canvas principal (camiseta)
  if (fabricCanvas) {
    fabricCanvas.add(fabricObj);
    fabricCanvas.setActiveObject(fabricObj);
    fabricCanvas.renderAll();
  }
}

/* Zona editable real del canvas 2D de la pantaloneta (coincide con
   ZONA_PANT usada para limitar el arrastre en initFabricPantaloneta). */
const ZONA_PANT_ORIGEN = { x: 10, y: 22, w: 128, h: 78 };

/* ═══════════════════════════════════════════════════════════════
   MAPA DE CORRESPONDENCIA ZONA 2D → UV FRONTAL (warp por rejilla)
   El desenvuelto UV de la pantaloneta es muy irregular: un simple
   estirado rectangular (zona → un rectángulo UV) deja el diseño con
   tamaño/posición muy distintos a lo que se ve en el editor 2D.
   En su lugar, se dispara un rayo por cada punto de una rejilla sobre
   la silueta frontal (grupo de material 0, ver dividirMeshFrenteAtras)
   para averiguar a qué UV corresponde cada punto, y luego el diseño
   se dibuja triángulo por triángulo con la transformación afín exacta
   de cada celda — como un "warp" de malla, no un solo estirado.
   ═══════════════════════════════════════════════════════════════ */
const MAPA_UV_COLS = 6;
const MAPA_UV_ROWS = 5;
let mapaFrenteUVCache = { mesh: null, grid: null };

function construirMapaFrenteUV(mesh) {
  if (mapaFrenteUVCache.mesh === mesh && mapaFrenteUVCache.grid) return mapaFrenteUVCache.grid;
  if (!Array.isArray(mesh.material)) return null;

  // Cámara de referencia fija (no la del usuario) para que el mapa sea
  // siempre el mismo sin importar cómo esté orientada la vista actual.
  const camTemp = new THREE.PerspectiveCamera(42, 1, 0.01, 100);
  camTemp.position.set(0, -0.37, 1.2);
  camTemp.lookAt(0, -0.37, 0);
  camTemp.updateMatrixWorld(true);

  mesh.updateMatrixWorld(true);
  const rc = new THREE.Raycaster();
  const golpearFrente = (ndcX, ndcY) => {
    rc.setFromCamera(new THREE.Vector2(ndcX, ndcY), camTemp);
    const hits = rc.intersectObject(mesh, false);
    const hit = hits.find(h => h.face.materialIndex === 0);
    return hit ? { u: hit.uv.x, v: hit.uv.y } : null;
  };

  // 1) Bbox NDC de la silueta frontal visible
  let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
  const SCAN = 40;
  for (let j = 0; j <= SCAN; j++) {
    for (let i = 0; i <= SCAN; i++) {
      const nx = -1 + 2 * i / SCAN, ny = -1 + 2 * j / SCAN;
      if (golpearFrente(nx, ny)) {
        if (nx < minX) minX = nx; if (nx > maxX) maxX = nx;
        if (ny < minY) minY = ny; if (ny > maxY) maxY = ny;
      }
    }
  }
  if (!isFinite(minX)) return null;

  // Margen de seguridad para no pegar el diseño justo en el borde
  const padX = (maxX - minX) * 0.10, padY = (maxY - minY) * 0.10;
  minX += padX; maxX -= padX; minY += padY; maxY -= padY;

  // 2) Rejilla de puntos UV (fila 0 = arriba de la zona = cintura)
  const grid = [];
  for (let j = 0; j <= MAPA_UV_ROWS; j++) {
    const ny = maxY - (maxY - minY) * (j / MAPA_UV_ROWS);
    const fila = [];
    for (let i = 0; i <= MAPA_UV_COLS; i++) {
      const nx = minX + (maxX - minX) * (i / MAPA_UV_COLS);
      fila.push(golpearFrente(nx, ny));
    }
    grid.push(fila);
  }
  // Rellenar huecos (puntos que no impactaron la malla) con el vecino más cercano
  for (let j = 0; j <= MAPA_UV_ROWS; j++) {
    for (let i = 0; i <= MAPA_UV_COLS; i++) {
      if (grid[j][i]) continue;
      let mejor = null, mejorD = Infinity;
      for (let jj = 0; jj <= MAPA_UV_ROWS; jj++) {
        for (let ii = 0; ii <= MAPA_UV_COLS; ii++) {
          if (!grid[jj][ii]) continue;
          const d = (jj - j) * (jj - j) + (ii - i) * (ii - i);
          if (d < mejorD) { mejorD = d; mejor = grid[jj][ii]; }
        }
      }
      grid[j][i] = mejor;
    }
  }

  mapaFrenteUVCache = { mesh, grid };
  return grid;
}

/* Transforma afínmente un triángulo fuente (s0,s1,s2) hacia un triángulo
   destino (d0,d1,d2) y dibuja la imagen fuente recortada a ese triángulo. */
function dibujarTrianguloTexturizado(ctx, img, s0, s1, s2, d0, d1, d2) {
  const denom = s0.x * (s1.y - s2.y) + s1.x * (s2.y - s0.y) + s2.x * (s0.y - s1.y);
  if (Math.abs(denom) < 1e-6) return;

  ctx.save();
  ctx.beginPath();
  ctx.moveTo(d0.x, d0.y); ctx.lineTo(d1.x, d1.y); ctx.lineTo(d2.x, d2.y);
  ctx.closePath();
  ctx.clip();

  const a = (d0.x * (s1.y - s2.y) + d1.x * (s2.y - s0.y) + d2.x * (s0.y - s1.y)) / denom;
  const c = (d0.x * (s2.x - s1.x) + d1.x * (s0.x - s2.x) + d2.x * (s1.x - s0.x)) / denom;
  const e = (d0.x * (s1.x * s2.y - s2.x * s1.y) + d1.x * (s2.x * s0.y - s0.x * s2.y) + d2.x * (s0.x * s1.y - s1.x * s0.y)) / denom;
  const b = (d0.y * (s1.y - s2.y) + d1.y * (s2.y - s0.y) + d2.y * (s0.y - s1.y)) / denom;
  const d = (d0.y * (s2.x - s1.x) + d1.y * (s0.x - s2.x) + d2.y * (s1.x - s0.x)) / denom;
  const f = (d0.y * (s1.x * s2.y - s2.x * s1.y) + d1.y * (s2.x * s0.y - s0.x * s2.y) + d2.y * (s0.x * s1.y - s1.x * s0.y)) / denom;

  ctx.transform(a, b, c, d, e, f);
  ctx.drawImage(img, 0, 0);
  ctx.restore();
}

/* Dibuja el canvas de diseño (source, en coordenadas de ZONA_PANT_ORIGEN)
   sobre el canvas UV (ctx) siguiendo la rejilla de correspondencia. */
function dibujarDisenioConMapaUV(ctx, source, grid, uvW, uvH) {
  for (let j = 0; j < MAPA_UV_ROWS; j++) {
    for (let i = 0; i < MAPA_UV_COLS; i++) {
      const sx0 = ZONA_PANT_ORIGEN.x + (i / MAPA_UV_COLS) * ZONA_PANT_ORIGEN.w;
      const sx1 = ZONA_PANT_ORIGEN.x + ((i + 1) / MAPA_UV_COLS) * ZONA_PANT_ORIGEN.w;
      const sy0 = ZONA_PANT_ORIGEN.y + (j / MAPA_UV_ROWS) * ZONA_PANT_ORIGEN.h;
      const sy1 = ZONA_PANT_ORIGEN.y + ((j + 1) / MAPA_UV_ROWS) * ZONA_PANT_ORIGEN.h;

      const p00 = grid[j][i], p10 = grid[j][i + 1], p01 = grid[j + 1][i], p11 = grid[j + 1][i + 1];
      const d00 = { x: p00.u * uvW, y: p00.v * uvH };
      const d10 = { x: p10.u * uvW, y: p10.v * uvH };
      const d01 = { x: p01.u * uvW, y: p01.v * uvH };
      const d11 = { x: p11.u * uvW, y: p11.v * uvH };

      dibujarTrianguloTexturizado(ctx, source, { x: sx0, y: sy0 }, { x: sx1, y: sy0 }, { x: sx0, y: sy1 }, d00, d10, d01);
      dibujarTrianguloTexturizado(ctx, source, { x: sx1, y: sy0 }, { x: sx1, y: sy1 }, { x: sx0, y: sy1 }, d10, d11, d01);
    }
  }
}

/* Actualizar textura 3D de pantaloneta
   Renderiza los objetos de diseño sobre fondo de color sólido.
   Se excluye la silueta SVG para que no tape el diseño en el UV. */
function actualizarTexturaPantaloneta3D() {
  if (!fabricPant || !modelo3D) return;

  const matPant = ACCESORIOS?.pantaloneta?.materiales?.['Soccer_Outfit_Kit_01_1002004'];
  if (!matPant) return;

  // Verificar si hay objetos de diseño reales (excluir silueta y zona)
  const objetos = fabricPant.getObjects().filter(o => !esObjetoSistemaPant(o));

  if (objetos.length === 0) {
    // Sin diseño — color sólido
    if (matPant._texturaCanvas) {
      matPant._texturaCanvas.dispose();
      matPant._texturaCanvas = null;
    }
    matPant.map = null;
    matPant.color.set(estado.colorPantaloneta || '#1565c0');
    matPant.needsUpdate = true;
    if (renderer3d && scene3d && camera3d) renderer3d.render(scene3d, camera3d);
    return;
  }

  // Canvas del tamaño UV (480x520)
  const UV_W = 480, UV_H = 520;

  const intermedio = document.createElement('canvas');
  intermedio.width  = UV_W;
  intermedio.height = UV_H;
  const ctx = intermedio.getContext('2d');

  // Fondo color pantaloneta
  ctx.fillStyle = estado.colorPantaloneta || '#1565c0';
  ctx.fillRect(0, 0, UV_W, UV_H);

  // Canvas temporal con SOLO los objetos de diseño (sin silueta ni zona)
  // Todos juntos en un StaticCanvas para respetar el z-order correcto
  const tmpEl = document.createElement('canvas');
  tmpEl.width  = PANT_W;
  tmpEl.height = PANT_H;

  const tmpFabric = new fabric.StaticCanvas(tmpEl, {
    width: PANT_W,
    height: PANT_H,
    backgroundColor: 'transparent',
  });

  // Clonar y agregar en orden para respetar capas
  const clones = [];
  const clonarYAgregar = (index) => {
    if (index >= objetos.length) {
      // Todos clonados — renderizar y aplicar textura
      tmpFabric.renderAll();

      // Proyectar la zona editable sobre la región UV frontal real,
      // usando el mapa de correspondencia (warp por rejilla) en vez de
      // un solo estirado rectangular, para que tamaño y posición
      // coincidan con lo que el cliente ve en el editor 2D.
      let mesh = null;
      modelo3D.traverse(o => { if (o.isMesh && o.name === 'Soccer_Outfit_Kit_01_1002004') mesh = o; });
      const grid = mesh ? construirMapaFrenteUV(mesh) : null;
      if (grid) {
        dibujarDisenioConMapaUV(ctx, tmpFabric.getElement(), grid, UV_W, UV_H);
      }
      tmpFabric.dispose();

      if (!matPant._texturaCanvas) {
        matPant._texturaCanvas = new THREE.CanvasTexture(intermedio);
        matPant._texturaCanvas.flipY = false;
        matPant._texturaCanvas.wrapS = THREE.ClampToEdgeWrapping;
        matPant._texturaCanvas.wrapT = THREE.ClampToEdgeWrapping;
      }
      matPant._texturaCanvas.image = intermedio;
      matPant._texturaCanvas.needsUpdate = true;
      matPant.map = matPant._texturaCanvas;
      matPant.color.set(0xffffff);
      matPant.needsUpdate = true;
      if (renderer3d && scene3d && camera3d) renderer3d.render(scene3d, camera3d);
      return;
    }
    objetos[index].clone(clonado => {
      tmpFabric.add(clonado);
      clonarYAgregar(index + 1);
    });
  };

  clonarYAgregar(0);
}

/* Exportar canvas pantaloneta como JSON para guardar */
function getPantalonetaJSON() {
  if (!fabricPant) return null;
  return fabricPant.toJSON(['id','tipo','name','fontFamily','fontWeight','fontStyle','textDecoration','textBaseline']);
}

/* Exportar imagen 2D de la pantaloneta */
function getPantalonetaImagen() {
  if (!fabricPant) return null;
  return fabricPant.toDataURL({ format: 'png', quality: 0.92 });
}

/* ═══════════════════════════════════════════════════════════════
   SELECTOR DE DESTINO — Camiseta o Pantaloneta
   ═══════════════════════════════════════════════════════════════ */

// 'camiseta' = canvas principal | 'pantaloneta' = fabricPant
let canvasDestino = 'camiseta';

function setCanvasDestino(destino) {
  canvasDestino = destino;
  document.querySelectorAll('.destino-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('destino-btn-' + destino)?.classList.add('active');
}

/* Obtener el canvas activo según destino */
function getCanvasActivo() {
  if (canvasDestino === 'pantaloneta' && fabricPant) return fabricPant;
  return fabricCanvas;
}

/* Obtener punto inicial de diseño según destino */
function getPuntoDiseno() {
  if (canvasDestino === 'pantaloneta') {
    return { x: PANT_W / 2, y: PANT_H / 2 - 10 };
  }
  return puntoInicialDiseno();
}

/* Mostrar/ocultar selector de destino */
function actualizarSelectorDestino() {
  const selector = document.getElementById('selector-destino');
  if (!selector) return;
  const pantActiva = ACCESORIOS?.pantaloneta?.activo;
  selector.style.display = pantActiva ? 'flex' : 'none';
  if (!pantActiva) canvasDestino = 'camiseta';
}