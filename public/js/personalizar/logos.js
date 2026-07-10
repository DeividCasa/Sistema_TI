function subirLogo(e) {
  if(!vistaPermiteDiseno()){
    toast('El cuello solo permite cambiar color','error');
    e.target.value = '';
    return;
  }
  const files = Array.from(e.target.files || []);
  if(!files.length) return;

  files.forEach((file, index) => {
    const reader = new FileReader();
    reader.onload = ev => agregarImagenLogoACanvas(ev.target.result, index);
    reader.readAsDataURL(file);

    // Guardar el archivo en la galería privada del cliente para poder
    // reutilizarlo después sin volver a subirlo.
    subirLogoAGaleria(file);
  });

  // Limpiar el input para permitir subir el mismo archivo otra vez
  e.target.value = '';
}

/* Agrega una imagen (desde una URL/dataURL) al canvas activo, igual sea un
   archivo recién subido o un logo ya guardado en la galería del cliente. */
function agregarImagenLogoACanvas(url, offsetIndex = null) {
  fabric.Image.fromURL(url, img => {
    const canvas = (typeof getCanvasActivo === 'function') ? getCanvasActivo() : fabricCanvas;
    // Si no se indica un índice explícito (multi-subida), usar la cantidad
    // de imágenes ya presentes para que cada logo agregado se desplace un
    // poco y no quede apilado exactamente sobre el anterior.
    if (offsetIndex === null) {
      offsetIndex = canvas.getObjects().filter(o => o.tipo === 'imagen').length;
    }
    const esMain = canvas === fabricCanvas;
    const maxSize = esMain ? 120 : 60;
    const escala = maxSize / Math.max(img.width, img.height);
    const p = (typeof getPuntoDiseno === 'function') ? getPuntoDiseno() : puntoInicialDiseno();
    img.set({
      left: p.x + (offsetIndex * 20), top: p.y + (offsetIndex * 20),
      originX:'center', originY:'center',
      scaleX:escala, scaleY:escala,
      id:'imagen-'+Date.now()+'-'+offsetIndex,
      tipo:'imagen'
    });
    // Siempre actualizar objLogo al último agregado (para escalarLogo)
    objLogo = img;
    canvas.add(img);
    canvas.setActiveObject(img);
    if (esMain) { limitarObjetoZona(img); actualizarTextura3D(); }
    else if (typeof limitarObjZonaPant === 'function') {
      limitarObjZonaPant(img, {x:14,y:22,w:120,h:76});
      canvas.renderAll();
      if (typeof actualizarTexturaPantaloneta3D==='function') actualizarTexturaPantaloneta3D();
    }
    canvas.renderAll();
    // object:added ya guarda el historial (ver nota en texto.js).
  }, { crossOrigin:'anonymous' });
}

function escalarLogo(val) {
  // Prioridad: objeto activo en el canvas activo
  const canvas = (typeof getCanvasActivo === 'function') ? getCanvasActivo() : fabricCanvas;
  const activo = canvas.getActiveObject();
  if(activo && activo.tipo === 'imagen') objLogo = activo;
  else if(activo && activo.type === 'image' && !esObjetoSistema(activo)) objLogo = activo;
  if(!objLogo) return;
  const escala = val/120;
  objLogo.scaleX = escala; objLogo.scaleY = escala;
  objLogo.setCoords();
  canvas.renderAll();
  guardarHistorial();
  if (canvas === fabricCanvas) actualizarTextura3D();
  else if (typeof actualizarTexturaPantaloneta3D === 'function') actualizarTexturaPantaloneta3D();
}

/* ═══════════════════════════════════════════════════════════════
   GALERÍA DE LOGOS GUARDADOS — privada por cuenta de cliente
   Evita que el cliente tenga que volver a subir el mismo logo cada
   vez que abre el editor; cada cliente solo ve los suyos (filtrado
   por sesión en el backend).
   ═══════════════════════════════════════════════════════════════ */

async function cargarMisLogos() {
  const grid = document.getElementById('mis-logos-grid');
  if (!grid || typeof URL_LOGOS === 'undefined') return;
  try {
    const r = await fetch(URL_LOGOS, { headers: { 'Accept':'application/json' } });
    const data = await r.json();
    if (data.success) renderizarMisLogos(data.logos);
  } catch {
    // silencioso: la galería es una comodidad, no bloquea el editor
  }
}

function renderizarMisLogos(logos) {
  const grid = document.getElementById('mis-logos-grid');
  if (!grid) return;
  grid.innerHTML = '';

  if (!logos.length) {
    const vacio = document.createElement('div');
    vacio.className = 'mis-logos-vacio';
    vacio.id = 'mis-logos-vacio';
    vacio.textContent = 'Aún no tienes logos guardados.';
    grid.appendChild(vacio);
    return;
  }

  logos.forEach(logo => {
    const item = document.createElement('div');
    item.className = 'mis-logos-item';
    item.title = logo.nombre || 'Logo';
    item.onclick = () => agregarImagenLogoACanvas(logo.url);

    const img = document.createElement('img');
    img.src = logo.url;
    img.alt = logo.nombre || 'Logo';
    item.appendChild(img);

    const del = document.createElement('button');
    del.className = 'mis-logos-del';
    del.innerHTML = '<i class="fas fa-xmark"></i>';
    del.onclick = (ev) => { ev.stopPropagation(); eliminarLogoGuardado(logo.id); };
    item.appendChild(del);

    grid.appendChild(item);
  });
}

async function subirLogoAGaleria(file) {
  if (typeof URL_LOGOS === 'undefined') return;
  const body = new FormData();
  body.append('archivo', file);
  try {
    const r = await fetch(URL_LOGOS, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body,
    });
    const data = await r.json();
    if (data.success) cargarMisLogos();
  } catch {
    // silencioso: si falla el guardado, el logo igual quedó en el diseño actual
  }
}

async function eliminarLogoGuardado(id) {
  if (typeof URL_LOGOS === 'undefined') return;
  try {
    const r = await fetch(`${URL_LOGOS}/${id}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    });
    const data = await r.json();
    if (data.success) cargarMisLogos();
  } catch {
    toast('No se pudo eliminar el logo', 'error');
  }
}
