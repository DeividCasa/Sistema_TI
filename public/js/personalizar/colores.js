function normalizarHex(color) {
  if(!color) return '#000000';
  const clean = String(color).trim();
  return clean.startsWith('#') ? clean.toLowerCase() : ('#' + clean).toLowerCase();
}

function initSwatchesGlobal() {
  const cont = document.getElementById('swatches-global');
  cont.innerHTML = '';
  PALETA_STD.forEach(color => {
    const s = document.createElement('div');
    s.className = 'swatch';
    s.dataset.color = normalizarHex(color);
    s.style.background = color;
    if(color === '#ffffff') s.style.border = '2.5px solid #CBD5E1';
    s.onclick = () => aplicarColorZona(color);
    cont.appendChild(s);
  });
}

function aplicarColorZona(color) {
  color = normalizarHex(color);
  const info = VISTAS[vistaActual];
  estado[info.key] = color;

  // Actualizar dot en tab
  const dotVista = document.getElementById('dot-' + vistaActual);
  if(dotVista) dotVista.style.background = color;

  // Actualizar badge
  document.getElementById('zona-dot').style.background = color;

  // Actualizar picker y hex
  actualizarUIColor(color);

  // Resaltar swatch seleccionado
  document.querySelectorAll('#swatches-global .swatch').forEach(sw => {
    sw.classList.toggle('sel', normalizarHex(sw.dataset.color) === color);
  });

  // Refrescar silueta 2D
  refrescarSilueta();

  // Actualizar 3D
  actualizarColor3D(info.key3d, color);

  guardarHistorial();
}

function aplicarColorCustom(color) {
  const clean = normalizarHex(color);
  document.getElementById('hex-input').value = clean;
  aplicarColorZona(clean);
}

function aplicarHex(val) {
  const clean = normalizarHex(val);
  if(/^#[0-9a-fA-F]{6}$/.test(clean)) {
    document.getElementById('color-custom').value = clean;
    aplicarColorZona(clean);
  }
}

function actualizarUIColor(color) {
  try {
    const clean = normalizarHex(color);
    document.getElementById('color-custom').value = clean;
    document.getElementById('hex-input').value = clean;
  } catch(e){}
}

/* Swatches de texto (en el tab texto) */
function initSwatchesTexto() {
  const cont = document.getElementById('swatches-texto');
  cont.innerHTML = '';
  ['#000000','#ffffff','#1a237e','#b71c1c','#1b5e20','#fdd835','#e65100','#4a148c'].forEach(color => {
    const s = document.createElement('div');
    s.className = 'swatch' + (estado.colorTexto === color ? ' sel' : '');
    s.dataset.color = normalizarHex(color);
    s.style.background = color;
    if(color==='#ffffff') s.style.border = '2.5px solid #CBD5E1';
    s.onclick = () => aplicarColorTexto(color);
    cont.appendChild(s);
  });
}
