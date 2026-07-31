/* ═══════════════════════════════════════════════════════════════
   VISOR 3D SOLO LECTURA — "Mis diseños"
   Reutiliza prendas.js / three-viewer.js / accesorios.js (los mismos
   que usa el editor) sin modificarlos: solo hay que poblar sus
   variables globales (estado, tipoPrendaActual, canvasData, ACCESORIOS)
   con los datos guardados de un diseño antes de llamar a sus funciones.
   ═══════════════════════════════════════════════════════════════ */

// three-viewer.js consulta esta variable en crearCanvasTextura() para
// decidir si usa el canvas EN VIVO del editor o el canvasData guardado;
// aquí no hay editor interactivo, así que se deja siempre en null.
let fabricCanvas = null;

let visor3DInicializado = false;
let libreriasVisor3DCargadas = false;
let libreriasVisor3DPromesa = null;
let visor3DPollTimer = null;
let visor3DTimeoutTimer = null;

const VISOR3D_CDN = [
  'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js',
  'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js',
  'https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js',
  'https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js',
];

function cargarScriptVisor3D(src) {
  return new Promise((resolve, reject) => {
    const s = document.createElement('script');
    s.src = src;
    s.onload = () => resolve();
    s.onerror = () => reject(new Error('No se pudo cargar ' + src));
    document.head.appendChild(s);
  });
}

// Las librerías (three.js + addons + fabric.js) solo se descargan la
// primera vez que el cliente abre un 3D, para no cargarlas de más en
// "Mis diseños" si nunca hace clic en una imagen.
async function asegurarLibreriasVisor3D() {
  if (libreriasVisor3DCargadas) return;
  if (!libreriasVisor3DPromesa) {
    libreriasVisor3DPromesa = (async () => {
      for (const src of VISOR3D_CDN) {
        await cargarScriptVisor3D(src);
      }
      libreriasVisor3DCargadas = true;
    })();
  }
  return libreriasVisor3DPromesa;
}

function leerDatosDisenio(disenioId) {
  const el = document.getElementById('datos3d-' + disenioId);
  if (!el) return null;
  try {
    return JSON.parse(el.textContent);
  } catch (e) {
    return null;
  }
}

// Diseños guardados antes de corregir el bug de app.js (que leía las
// claves de estado equivocadas para la chompa) quedaron con el texto
// literal "undefined" en varios colores. Se ignoran esos valores y se
// deja el color por defecto de prendas.js en vez de pintar el modelo
// con un string inválido.
function colorValido(v) {
  return typeof v === 'string' && v !== '' && v !== 'undefined' && v !== 'null';
}

/* Traduce el payload guardado (colores planos + canvas_json de cada
   vista) a las variables globales que prendas.js/three-viewer.js ya
   saben leer (estado, tipoPrendaActual, canvasData de la prenda). */
function aplicarDatosAlEstado(payload) {
  tipoPrendaActual = payload.tipoPrenda === 'chompa' ? 'chompa' : 'camiseta';

  const colores = payload.colores || {};
  const acc = payload.coloresAccesorios || {};

  if (tipoPrendaActual === 'camiseta') {
    if (colorValido(colores.frente)) estado.colorFrente = colores.frente;
    if (colorValido(colores.atras)) estado.colorAtras = colores.atras;
    const manga = [colores.manga_derecha, colores.manga_izquierda].find(colorValido);
    if (manga) estado.colorMangas = manga;
    if (colorValido(colores.cuello)) estado.colorCuello = colores.cuello;
    if (colorValido(colores.parte_abajo_mangas)) estado.colorParteAbajoMangas = colores.parte_abajo_mangas;
    if (colorValido(colores.parte_abajo_camiseta)) estado.colorParteAbajoCamiseta = colores.parte_abajo_camiseta;
  } else {
    if (colorValido(colores.frente)) estado.chompaColorFrente = colores.frente;
    if (colorValido(colores.atras)) estado.chompaColorAtras = colores.atras;
    if (colorValido(colores.manga_derecha)) estado.chompaColorMangas = colores.manga_derecha;
    if (colorValido(colores.manga_izquierda)) estado.chompaColorMangaIzq = colores.manga_izquierda;
    if (colorValido(colores.cierre)) estado.chompaColorCierre = colores.cierre;
    if (colorValido(colores.bolsillo)) estado.chompaColorBolsillo = colores.bolsillo;
    if (colorValido(colores.capucha)) estado.chompaColorCapucha = colores.capucha;
    if (colorValido(colores.parte_abajo)) estado.chompaColorParteAbajo = colores.parte_abajo;
  }

  if (colorValido(acc.pantaloneta)) estado.colorPantaloneta = acc.pantaloneta;
  if (colorValido(acc.parte_abajo_pant)) estado.colorParteAbajoPant = acc.parte_abajo_pant;
  if (colorValido(acc.medias)) estado.colorMedias = acc.medias;
  if (colorValido(acc.partearriba_med)) estado.colorPartearribaMedias = acc.partearriba_med;
  if (colorValido(acc.pantalon_chompa)) estado.chompaColorPantalon = acc.pantalon_chompa;

  let canvasParseado = {};
  if (payload.canvasJson) {
    try {
      canvasParseado = JSON.parse(payload.canvasJson) || {};
    } catch (e) {
      canvasParseado = {};
    }
  }
  PRENDAS[tipoPrendaActual].canvasData = canvasParseado;

  activarConfiguracionPrenda(tipoPrendaActual);
}

/* Enciende/apaga un accesorio de forma idempotente (toggleAccesorio()
   de accesorios.js invierte el estado, así que solo se llama si hace
   falta cambiarlo). */
function fijarAccesorioActivo(id, activo) {
  if (!ACCESORIOS[id]) return;
  if (ACCESORIOS[id].activo !== activo) toggleAccesorio(id);
}

function aplicarAccesorios(payload) {
  const acc = payload.coloresAccesorios || {};

  // Los colores se actualizan ANTES de activar el accesorio: cuando se
  // enciende, aplicarMaterialesAccesorio() usa el color que ya esté en
  // el material en ese momento.
  if (colorValido(acc.pantaloneta)) aplicarColorAccesorio('pantaloneta', 'colorPantaloneta', acc.pantaloneta);
  if (colorValido(acc.parte_abajo_pant)) aplicarColorAccesorio('pantaloneta', 'colorParteAbajoPant', acc.parte_abajo_pant);
  if (colorValido(acc.medias)) aplicarColorAccesorio('medias', 'colorMedias', acc.medias);
  if (colorValido(acc.partearriba_med)) aplicarColorAccesorio('medias', 'colorPartearribaMedias', acc.partearriba_med);
  if (colorValido(acc.pantalon_chompa)) aplicarColorAccesorio('pantalon', 'chompaColorPantalon', acc.pantalon_chompa);

  fijarAccesorioActivo('pantaloneta', !!payload.pantalonetaActiva);
  fijarAccesorioActivo('medias', !!payload.mediasActivas);
  fijarAccesorioActivo('pantalon', !!payload.pantalonChompaActivo);
}

function mostrarCargandoVisor3D() {
  const loading = document.getElementById('visor3d-loading');
  const error = document.getElementById('visor3d-error');
  const canvas = document.getElementById('canvas-3d');
  if (loading) loading.style.display = 'flex';
  if (error) error.style.display = 'none';
  if (canvas) canvas.style.visibility = 'hidden';
}

function mostrarErrorVisor3D() {
  const loading = document.getElementById('visor3d-loading');
  const error = document.getElementById('visor3d-error');
  if (loading) loading.style.display = 'none';
  if (error) error.style.display = 'flex';
}

function esperarModeloCargadoVisor3D() {
  clearInterval(visor3DPollTimer);
  clearTimeout(visor3DTimeoutTimer);

  visor3DPollTimer = setInterval(() => {
    if (typeof modelo3D !== 'undefined' && modelo3D) {
      clearInterval(visor3DPollTimer);
      clearTimeout(visor3DTimeoutTimer);
      const loading = document.getElementById('visor3d-loading');
      const canvas = document.getElementById('canvas-3d');
      if (loading) loading.style.display = 'none';
      if (canvas) canvas.style.visibility = 'visible';
    }
  }, 150);

  visor3DTimeoutTimer = setTimeout(() => {
    clearInterval(visor3DPollTimer);
    if (typeof modelo3D === 'undefined' || !modelo3D) mostrarErrorVisor3D();
  }, 15000);
}

async function abrirVisor3D(disenioId, nombreDiseno, imagenFallback) {
  const overlay = document.getElementById('visor3d-overlay');
  if (!overlay) return;

  const payload = leerDatosDisenio(disenioId);
  if (!payload) return;

  overlay.classList.add('visible');
  const titulo = document.getElementById('visor3d-titulo');
  if (titulo) titulo.textContent = nombreDiseno || 'Mi diseño';
  const errorLink = document.getElementById('visor3d-error-link');
  if (errorLink) errorLink.onclick = () => { cerrarVisor3D(); if (imagenFallback) abrirLightbox(imagenFallback); return false; };

  mostrarCargandoVisor3D();

  try {
    await asegurarLibreriasVisor3D();
  } catch (e) {
    mostrarErrorVisor3D();
    return;
  }

  aplicarDatosAlEstado(payload);

  if (!visor3DInicializado) {
    init3D();
    initAccesorios();
    visor3DInicializado = true;
  } else {
    cargarModelo3D();
  }

  aplicarAccesorios(payload);

  esperarModeloCargadoVisor3D();
}

function cerrarVisor3D() {
  const overlay = document.getElementById('visor3d-overlay');
  if (overlay) overlay.classList.remove('visible');
  clearInterval(visor3DPollTimer);
  clearTimeout(visor3DTimeoutTimer);
}
