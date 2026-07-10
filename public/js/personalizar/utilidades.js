function cambiarTool(tab, el) {
  document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.rail-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + tab).classList.add('active');
  if(el) el.classList.add('active');
}

function mostrarToolbar() {
  document.getElementById('obj-toolbar').classList.add('visible');
}
function moverArriba() {
  const cv = (typeof getCanvasActivo==='function') ? getCanvasActivo() : fabricCanvas;
  const o = cv.getActiveObject(); if(!o) return;
  cv.bringToFront(o); cv.renderAll(); guardarHistorial();
  if(cv===fabricCanvas) actualizarTextura3D();
}
function moverAtras() {
  const cv = (typeof getCanvasActivo==='function') ? getCanvasActivo() : fabricCanvas;
  const o = cv.getActiveObject(); if(!o) return;
  cv.sendToBack(o);
  const silueta = cv.getObjects().find(x=>x.id==='silueta'||x.id==='silueta-pant');
  if(silueta) cv.sendToBack(silueta);
  cv.renderAll(); guardarHistorial();
  if(cv===fabricCanvas) actualizarTextura3D();
}
function esObjetoProtegido(o) {
  return !o || o.id==='silueta' || o.id==='silueta-pant' || o.id==='zona-pant' || o.id==='zona-diseno';
}

function duplicarObj() {
  const cv = (typeof getCanvasActivo==='function') ? getCanvasActivo() : fabricCanvas;
  const objetos = cv.getActiveObjects().filter(o => !esObjetoProtegido(o));
  if(!objetos.length) return;

  let pendientes = objetos.length;
  const clones = [];
  objetos.forEach(o => {
    o.clone(clon => {
      clon.set({ left:o.left+20, top:o.top+20 });
      cv.add(clon);
      clones.push(clon);
      if(--pendientes === 0) {
        cv.discardActiveObject();
        if(clones.length > 1) {
          const seleccion = new fabric.ActiveSelection(clones, { canvas: cv });
          cv.setActiveObject(seleccion);
        } else if(clones.length === 1) {
          cv.setActiveObject(clones[0]);
        }
        cv.renderAll(); guardarHistorial();
        if(cv===fabricCanvas) actualizarTextura3D();
      }
    });
  });
}
function eliminarObj() {
  const cv = (typeof getCanvasActivo==='function') ? getCanvasActivo() : fabricCanvas;
  const objetos = cv.getActiveObjects().filter(o => !esObjetoProtegido(o));
  if(!objetos.length) return;
  objetos.forEach(o => {
    if(o===objLogo){ objLogo=null; }
    cv.remove(o);
  });
  cv.discardActiveObject();
  cv.renderAll(); guardarHistorial();
  if(cv===fabricCanvas) actualizarTextura3D();
}

let clipboardObjs = null;
let clipboardPegadas = 0;

function copiarSeleccion() {
  const cv = (typeof getCanvasActivo==='function') ? getCanvasActivo() : fabricCanvas;
  const objetos = cv.getActiveObjects().filter(o => !esObjetoProtegido(o));
  if(!objetos.length) return;
  clipboardObjs = objetos.map(o => o.toObject(FABRIC_PROPS));
  clipboardPegadas = 0;
}

function pegarSeleccion() {
  if(!clipboardObjs || !clipboardObjs.length) return;
  const cv = (typeof getCanvasActivo==='function') ? getCanvasActivo() : fabricCanvas;
  clipboardPegadas++;
  const desplazamiento = 20 * clipboardPegadas;

  fabric.util.enlivenObjects(clipboardObjs, objetosNuevos => {
    objetosNuevos.forEach(obj => {
      obj.set({
        id: (obj.tipo||'obj') + '-' + Date.now() + '-' + Math.floor(Math.random()*1000),
        left: obj.left + desplazamiento,
        top: obj.top + desplazamiento,
      });
      cv.add(obj);
    });
    cv.discardActiveObject();
    if(objetosNuevos.length > 1) {
      cv.setActiveObject(new fabric.ActiveSelection(objetosNuevos, { canvas: cv }));
    } else if(objetosNuevos.length === 1) {
      cv.setActiveObject(objetosNuevos[0]);
    }
    cv.renderAll(); guardarHistorial();
    if(cv===fabricCanvas) actualizarTextura3D();
  });
}

function initAtajosTeclado() {
  document.addEventListener('keydown', e => {
    const enCampo = ['INPUT','TEXTAREA'].includes(e.target.tagName) || e.target.isContentEditable;
    if(enCampo) return;

    if(e.key === 'Delete' || e.key === 'Backspace') {
      e.preventDefault();
      eliminarObj();
    } else if((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'c') {
      e.preventDefault();
      copiarSeleccion();
    } else if((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'v') {
      e.preventDefault();
      pegarSeleccion();
    }
  });
}

let toastTimer;
function toast(msg, tipo='') {
  const el = document.getElementById('toast');
  el.textContent = msg;
  el.className = 'show '+tipo;
  clearTimeout(toastTimer);
  toastTimer = setTimeout(()=>el.className='', 3200);
}