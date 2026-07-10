const CANVAS_W = 480, CANVAS_H = 520;
let fabricCanvas;
let objLogo = null;
let siluetaVersion = 0;

function initFabric() {
  const wrap = document.getElementById('canvas-wrap');
  fabricCanvas = new fabric.Canvas('fabric-canvas', {
    width: CANVAS_W, height: CANVAS_H,
    backgroundColor: '#f8fafc',
    selection: true,
    preserveDrawingBuffer: true,
  });
  wrap.style.width  = CANVAS_W + 'px';
  wrap.style.height = CANVAS_H + 'px';

  refrescarSilueta();

  fabricCanvas.on('selection:created', () => { mostrarToolbar(); sincronizarColorTextoUI(); });
  fabricCanvas.on('selection:updated', () => { mostrarToolbar(); sincronizarColorTextoUI(); });
  fabricCanvas.on('selection:cleared', () => {
    document.getElementById('obj-toolbar').classList.remove('visible');
  });
  fabricCanvas.on("object:moving",   e => { limitarObjetoZona(e.target); actualizarTextura3D(); });
  fabricCanvas.on("object:scaling",  e => { limitarObjetoZona(e.target); actualizarTextura3D(); });
  fabricCanvas.on("object:rotating", actualizarTextura3D);
  // "object:modified" dispara al soltar el mouse tras mover, escalar O
  // rotar — es el único evento que cubre un simple arrastre (mover no
  // tiene su propio evento "moved"). Antes solo se guardaba historial en
  // scaled/rotated, así que arrastrar un objeto nunca creaba un punto de
  // deshacer y el botón "deshacer" parecía no funcionar tras mover algo.
  fabricCanvas.on("object:modified", e => {
    if (esObjetoSistema(e.target)) return;
    limitarObjetoZona(e.target);
    guardarHistorial();
    actualizarTextura3D();
  });
  fabricCanvas.on("text:changed",    actualizarTextura3D);
  fabricCanvas.on('object:added',   e => { if(!esObjetoSistema(e.target)){ limitarObjetoZona(e.target); guardarHistorial(); actualizarTextura3D(); } });
  fabricCanvas.on('object:removed', e => { if(!esObjetoSistema(e.target)){ guardarHistorial(); actualizarTextura3D(); } });
  fabricCanvas.on("after:render", () => { if(texturaCanvas) texturaCanvas.needsUpdate = true; });

  guardarHistorial();
}



// Escala de la silueta 2D (0.78 = más pequeña, proporcional al 3D)
const SILUETA_SCALE  = 0.70;
const SILUETA_OFFSET_X = (CANVAS_W - CANVAS_W * SILUETA_SCALE) / 2;  // centrar horizontal
const SILUETA_OFFSET_Y = (CANVAS_H - CANVAS_H * SILUETA_SCALE) / 2;  // centrar vertical

function refrescarSilueta() {
  const version = ++siluetaVersion;
  const existente = fabricCanvas.getObjects().find(o => o.id === 'silueta');
  if (existente) fabricCanvas.remove(existente);
  const zonaExistente = fabricCanvas.getObjects().find(o => o.id === 'zona-diseno');
  if (zonaExistente) fabricCanvas.remove(zonaExistente);

  const colorActual = estado[VISTAS[vistaActual].key];
  const svgStr = PRENDAS[tipoPrendaActual].svg(vistaActual, colorActual);
  const url = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svgStr);

  fabric.Image.fromURL(url, img => {
    if(version !== siluetaVersion) return;
    img.set({
      id: 'silueta',
      left: CANVAS_W / 2,
      top: SILUETA_OFFSET_Y,
      originX: 'center', originY: 'top',
      selectable: false, evented: false,
      scaleX: SILUETA_SCALE,
      scaleY: SILUETA_SCALE,
    });
    fabricCanvas.insertAt(img, 0);
    if (vistaPermiteDiseno()) refrescarZonaDiseno();
    fabricCanvas.renderAll();
    actualizarTextura3D();
  }, { crossOrigin: 'anonymous' });
}

function refrescarZonaDiseno() {
  const zona = ZONAS_DISENO_2D[vistaActual];
  if(!zona || !fabricCanvas) return;

  // Escalar y desplazar la zona igual que la silueta
  const s  = SILUETA_SCALE;
  const ox = SILUETA_OFFSET_X;
  const oy = SILUETA_OFFSET_Y;

  const rect = new fabric.Rect({
    id: 'zona-diseno',
    left:   zona.x * s + ox,
    top:    zona.y * s + oy,
    width:  zona.w * s,
    height: zona.h * s,
    fill: 'rgba(37,99,235,0.035)',
    stroke: 'rgba(37,99,235,0.35)',
    strokeDashArray: [6, 5],
    strokeWidth: 1.5,
    selectable: false,
    evented: false,
    excludeFromExport: true,
  });
  fabricCanvas.add(rect);
  fabricCanvas.sendToBack(rect);
  const silueta = fabricCanvas.getObjects().find(o => o.id === 'silueta');
  if(silueta) fabricCanvas.sendToBack(silueta);
}

function limitarObjetoZona(obj) {
  const zona = ZONAS_DISENO_2D[vistaActual];
  if(!zona || !obj || esObjetoSistema(obj)) return;

  // Aplicar la misma escala y offset que la silueta
  const s  = SILUETA_SCALE;
  const ox = SILUETA_OFFSET_X;
  const oy = SILUETA_OFFSET_Y;

  const minX = zona.x * s + ox;
  const minY = zona.y * s + oy;
  const maxX = minX + zona.w * s;
  const maxY = minY + zona.h * s;
  const zW   = zona.w * s;
  const zH   = zona.h * s;

  obj.setCoords();
  let rect = obj.getBoundingRect(true, true);
  if(rect.width > zW || rect.height > zH){
    const factor = Math.min(zW / rect.width, zH / rect.height, 1);
    obj.scaleX *= factor;
    obj.scaleY *= factor;
    obj.setCoords();
    rect = obj.getBoundingRect(true, true);
  }

  let dx = 0, dy = 0;
  if(rect.left < minX) dx = minX - rect.left;
  if(rect.top  < minY) dy = minY - rect.top;
  if(rect.left + rect.width  > maxX) dx = maxX - (rect.left + rect.width);
  if(rect.top  + rect.height > maxY) dy = maxY - (rect.top  + rect.height);

  if(dx || dy){
    obj.left += dx;
    obj.top  += dy;
    obj.setCoords();
  }
}

function limpiarObjetosNoPermitidosEnCuello(canvasRef = fabricCanvas) {
  if(!canvasRef) return;
  canvasRef.getObjects().slice().forEach(obj => {
    if(!esObjetoSistema(obj)) canvasRef.remove(obj);
  });
}