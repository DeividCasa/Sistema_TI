function agregarTexto() {
  if(!vistaPermiteDiseno()){ toast('El cuello solo permite cambiar color','error'); return; }
  const val = document.getElementById('input-texto').value.trim();
  if(!val){ toast('Escribe el texto primero','error'); return; }
  const size = parseInt(document.getElementById('texto-size').value);
  const font = document.getElementById('texto-font').value;
  const p = puntoInicialDiseno();
  const t = new fabric.Text(val.toUpperCase(), {
    left:p.x, top:p.y, originX:'center', originY:'center',
    fontFamily:font, fontSize:size, fontWeight:'bold',
    fill:estado.colorTexto, textBaseline:'alphabetic', id:'texto-'+Date.now(), tipo:'texto',
  });
  const cv1 = (typeof getCanvasActivo==='function') ? getCanvasActivo() : fabricCanvas;
  cv1.add(t);
  cv1.setActiveObject(t);
  if (cv1===fabricCanvas) { limitarObjetoZona(t); actualizarTextura3D(); }
  else if (typeof limitarObjZonaPant==='function') {
    limitarObjZonaPant(t,{x:14,y:22,w:120,h:76});
    cv1.renderAll();
    if (typeof actualizarTexturaPantaloneta3D==='function') actualizarTexturaPantaloneta3D();
  }
  cv1.renderAll();
  // No hace falta guardarHistorial() aquí — cv1.add(t) ya disparó
  // "object:added", que guarda el historial automáticamente. Llamarlo de
  // nuevo creaba una segunda entrada idéntica y el usuario tenía que
  // presionar "deshacer" dos veces para ver el primer cambio real.
}

function agregarNumero() {
  if(!vistaPermiteDiseno()){ toast('El cuello solo permite cambiar color','error'); return; }
  const val = document.getElementById('input-numero').value.trim();
  if(!val){ toast('Escribe el nÃºmero primero','error'); return; }
  const font = document.getElementById('texto-font').value;
  const p = puntoInicialDiseno();
  const t = new fabric.Text(val, {
    left:p.x, top:p.y, originX:'center', originY:'center',
    fontFamily:font, fontSize:80, fontWeight:'800',
    fill:estado.colorTexto, textBaseline:'alphabetic', id:'numero-'+Date.now(), tipo:'texto',
  });
  const cv2 = (typeof getCanvasActivo==='function') ? getCanvasActivo() : fabricCanvas;
  cv2.add(t);
  cv2.setActiveObject(t);
  if (cv2===fabricCanvas) { limitarObjetoZona(t); actualizarTextura3D(); }
  else if (typeof limitarObjZonaPant==='function') {
    limitarObjZonaPant(t,{x:14,y:22,w:120,h:76});
    cv2.renderAll();
    if (typeof actualizarTexturaPantaloneta3D==='function') actualizarTexturaPantaloneta3D();
  }
  cv2.renderAll();
  // Ver nota en agregarTexto(): object:added ya guarda el historial.
}

function actualizarTextoLive() {
  const obj = fabricCanvas.getActiveObject();
  if(!obj || obj.type!=='text') return;
  obj.set('text', document.getElementById('input-texto').value.toUpperCase());
  fabricCanvas.renderAll();
  guardarHistorial();
  actualizarTextura3D();
}
function actualizarNumeroLive() {
  const obj = fabricCanvas.getActiveObject();
  if(!obj || obj.type!=='text') return;
  obj.set('text', document.getElementById('input-numero').value);
  fabricCanvas.renderAll();
  guardarHistorial();
  actualizarTextura3D();
}
function cambiarTamanoTexto(val) {
  const obj = fabricCanvas.getActiveObject();
  if(!obj || obj.type!=='text') return;
  obj.set('fontSize', parseInt(val));
  fabricCanvas.renderAll();
  guardarHistorial();
  actualizarTextura3D();
}
function cambiarFuenteTexto(font) {
  const obj = fabricCanvas.getActiveObject();
  if(!obj || obj.type!=='text') return;
  obj.set('fontFamily', font);
  fabricCanvas.renderAll();
  guardarHistorial();
  actualizarTextura3D();
}

/* Color personalizado para texto/número (además de los swatches fijos) */
function aplicarColorTextoCustom(color) {
  aplicarColorTexto(color);
}
function aplicarColorTextoHex(val) {
  const clean = normalizarHex(val);
  if(/^#[0-9a-fA-F]{6}$/.test(clean)) aplicarColorTexto(clean);
}
/* Refleja un color (hex) en el picker, el hex-input y el swatch resaltado
   del panel de texto/número. */
function actualizarUIColorTexto(color) {
  const clean = normalizarHex(color);
  const picker = document.getElementById('texto-color-custom');
  const hex = document.getElementById('texto-hex-input');
  if (picker) picker.value = clean;
  if (hex) hex.value = clean;
  document.querySelectorAll('#swatches-texto .swatch').forEach(sw => {
    sw.classList.toggle('sel', sw.dataset.color === clean);
  });
}

/* Al seleccionar un texto/número ya existente, reflejar su color actual
   (antes quedaba desincronizado y parecía que el color no se podía cambiar). */
function sincronizarColorTextoUI() {
  const obj = fabricCanvas.getActiveObject();
  if (!obj || obj.type !== 'text') return;
  actualizarUIColorTexto(obj.fill);
}

function aplicarColorTexto(color) {
  const clean = normalizarHex(color);
  estado.colorTexto = clean;
  actualizarUIColorTexto(clean);
  const obj = fabricCanvas.getActiveObject();
  if(obj && obj.type==='text'){
    obj.set('fill', clean);
    fabricCanvas.renderAll();
    guardarHistorial();
    actualizarTextura3D();
  }
}