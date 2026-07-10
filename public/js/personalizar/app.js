function confirmarGuardarDiseno() {
  document.getElementById('modal-guardar').classList.add('visible');
}
function cerrarModalGuardar() {
  document.getElementById('modal-guardar').classList.remove('visible');
}
function confirmarGuardarSi() {
  cerrarModalGuardar();
  guardarDiseno();
}

async function guardarDiseno() {
  canvasData[vistaActual] = fabricCanvas.toJSON(FABRIC_PROPS);

  const imagen2D = fabricCanvas.toDataURL({ format:'png', quality:.92 });
  const { frente: imagen3DFrente, atras: imagen3DAtras } = capturarVistas3D();

  const acc = getColoresAccesorios();

  const body = new URLSearchParams({
    plantilla_id          : PLANTILLA_ID ?? '',
    nombre                : document.getElementById('nombre-diseno').value,
    tipo_prenda           : tipoPrendaActual,
    color_frente          : estado.colorFrente,
    color_atras           : estado.colorAtras,
    color_manga_izquierda : estado.colorMangaIzquierda,
    color_manga_derecha   : estado.colorMangaDerecha,
    color_cuello          : estado.colorCuello,
    color_cierre          : estado.colorCierre,
    color_bolsillo        : estado.colorBolsillo,
    color_capucha         : estado.colorCapucha,
    color_parte_abajo     : estado.colorParteAbajo,
    color_pantaloneta      : acc.color_pantaloneta,
    color_parte_abajo_pant : acc.color_parte_abajo_pant,
    color_medias           : acc.color_medias,
    color_partearriba_med  : acc.color_partearriba_med,
    pantaloneta_activa     : acc.pantaloneta_activa,
    medias_activas         : acc.medias_activas,
    color_short           : '#ffffff',
    color_texto           : estado.colorTexto,
    canvas_json           : JSON.stringify(canvasData),
    imagen_captura        : imagen2D,
    imagen_3d_frente      : imagen3DFrente,
    imagen_3d_atras       : imagen3DAtras,
  });

  try {
    const r = await fetch(URL_GUARDAR_DISENO, {
      method:'POST',
      headers:{ 'Content-Type':'application/x-www-form-urlencoded', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
      body,
    });
    const data = await r.json();
    if(data.success){
      toast('✔ '+data.message,'success');
      borrarBorradorLocal();
    } else {
      toast(data.message||'Error al guardar','error');
    }
  } catch {
    toast('Ocurrió un error al guardar','error');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  // Si hay un borrador guardado (ej. de un refresh accidental), se aplica
  // ANTES de los init normales para que arranquen ya con la prenda/colores
  // correctos en vez de los valores por defecto.
  const borrador = (typeof prepararRestauracionBorrador === 'function') ? prepararRestauracionBorrador() : null;

  activarConfiguracionPrenda(tipoPrendaActual);
  renderVistaTabs();
  initFabric();
  initSwatchesGlobal();
  initSwatchesTexto();
  initSwatchesFigura();
  init3D();
  // initAccesorios necesita que scene3d ya exista (lo crea init3D)
  initAccesorios();
  initSwatchesAccesorio('pantaloneta');
  initSwatchesAccesorio('medias');
  initSwatchesAccesorio('pantalon');
  if (typeof cargarMisLogos === 'function') cargarMisLogos();
  initAtajosTeclado();
  document.getElementById('btn-undo').disabled = true;
  document.getElementById('btn-redo').disabled = true;
  actualizarBadgeZona();

  if (borrador && typeof terminarRestauracionBorrador === 'function') {
    terminarRestauracionBorrador(borrador);
  } else if (typeof sincronizarAccesoriosConPrenda === 'function') {
    sincronizarAccesoriosConPrenda();
  }
  if (typeof iniciarAutoguardadoBorrador === 'function') iniciarAutoguardadoBorrador();
});