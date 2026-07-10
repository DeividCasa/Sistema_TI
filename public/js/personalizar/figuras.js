function getFiguraColor() {
  return document.getElementById('figura-color')?.value || estado.colorTexto || '#000000';
}

/* Paleta de figuras — antes solo había un selector de color nativo;
   se agrega la misma paleta estándar usada en el resto del editor. */
function initSwatchesFigura() {
  const cont = document.getElementById('swatches-figura');
  if (!cont) return;
  cont.innerHTML = '';
  PALETA_STD.forEach(color => {
    const s = document.createElement('div');
    s.className = 'swatch';
    s.dataset.color = normalizarHex(color);
    s.style.background = color;
    if (color === '#ffffff') s.style.border = '2.5px solid #CBD5E1';
    s.onclick = () => cambiarColorFigura(color);
    cont.appendChild(s);
  });
}

function puntosEstrella(cx, cy, outer, inner, points) {
  const pts = [];
  for(let i=0;i<points*2;i++){
    const r = i % 2 === 0 ? outer : inner;
    const a = (Math.PI / points) * i - Math.PI / 2;
    pts.push({ x: cx + Math.cos(a) * r, y: cy + Math.sin(a) * r });
  }
  return pts;
}

function agregarFigura(tipo) {
  if(!vistaPermiteDiseno()){ toast('El cuello solo permite cambiar color','error'); return; }
  const color = getFiguraColor();
  let obj;
  const p = puntoInicialDiseno();
  const base = { left:p.x, top:p.y, originX:'center', originY:'center', fill:color, id:'figura-'+Date.now(), tipo:'figura' };

  if(tipo === 'rect') obj = new fabric.Rect({ ...base, width:120, height:80, rx:6, ry:6 });
  if(tipo === 'circle') obj = new fabric.Circle({ ...base, radius:52 });
  if(tipo === 'triangle') obj = new fabric.Triangle({ ...base, width:120, height:110 });
  if(tipo === 'line') obj = new fabric.Line([p.x - 60, p.y, p.x + 60, p.y], { stroke:color, strokeWidth:10, strokeLineCap:'round', id:'figura-'+Date.now(), tipo:'figura' });
  if(tipo === 'star') obj = new fabric.Polygon(puntosEstrella(0, 0, 58, 25, 5), { ...base });
  if(tipo === 'heart') {
    obj = new fabric.Path('M 0 -28 C -46 -72 -108 -12 0 66 C 108 -12 46 -72 0 -28 Z', {
      ...base, scaleX:.85, scaleY:.85
    });
  }

  if(!obj) return;
  const cvF = (typeof getCanvasActivo==='function') ? getCanvasActivo() : fabricCanvas;
  cvF.add(obj);
  cvF.setActiveObject(obj);
  if (cvF===fabricCanvas) { limitarObjetoZona(obj); actualizarTextura3D(); }
  else if (typeof limitarObjZonaPant==='function') {
    limitarObjZonaPant(obj,{x:14,y:22,w:120,h:76});
    cvF.renderAll();
    if (typeof actualizarTexturaPantaloneta3D==='function') actualizarTexturaPantaloneta3D();
  }
  cvF.renderAll();
  // object:added ya guarda el historial (ver nota en texto.js).
}

function cambiarColorFigura(color) {
  const clean = normalizarHex(color);

  // Reflejar el color elegido en el picker/hex y en el swatch resaltado,
  // así quede seleccionado como color por defecto para la próxima figura.
  const picker = document.getElementById('figura-color');
  const hex = document.getElementById('figura-hex-input');
  if (picker) picker.value = clean;
  if (hex) hex.value = clean;
  document.querySelectorAll('#swatches-figura .swatch').forEach(sw => {
    sw.classList.toggle('sel', sw.dataset.color === clean);
  });

  const obj = fabricCanvas.getActiveObject();
  if(!obj || obj.id === 'silueta' || (obj.tipo !== 'figura')) return;
  if(obj.type === 'line') obj.set('stroke', clean);
  else obj.set('fill', clean);
  fabricCanvas.renderAll();
  guardarHistorial();
  actualizarTextura3D();
}