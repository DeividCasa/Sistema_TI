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

  // estado.colorFrente/colorCierre/etc. son claves SOLO de la camiseta —
  // la chompa usa sus propias claves (chompaColorFrente, chompaColorCierre,
  // ...). Antes se leían siempre las de camiseta sin importar la prenda
  // activa, así que para una chompa se guardaba "undefined" (o un color
  // viejo de camiseta) en vez del color real elegido.
  const esChompa = tipoPrendaActual === 'chompa';

  const body = new URLSearchParams({
    plantilla_id          : PLANTILLA_ID ?? '',
    nombre                : document.getElementById('nombre-diseno').value,
    genero                : document.getElementById('genero-diseno').value,
    tipo_prenda           : tipoPrendaActual,
    color_frente          : esChompa ? estado.chompaColorFrente     : estado.colorFrente,
    color_atras           : esChompa ? estado.chompaColorAtras      : estado.colorAtras,
    color_manga_izquierda : esChompa ? estado.chompaColorMangaIzq   : estado.colorMangas,
    color_manga_derecha   : esChompa ? estado.chompaColorMangas     : estado.colorMangas,
    color_cuello          : esChompa ? ''                           : estado.colorCuello,
    color_cierre          : esChompa ? estado.chompaColorCierre     : '',
    color_bolsillo        : esChompa ? estado.chompaColorBolsillo   : '',
    color_capucha         : esChompa ? estado.chompaColorCapucha    : '',
    color_parte_abajo     : esChompa ? estado.chompaColorParteAbajo : '',
    color_parte_abajo_mangas   : esChompa ? '' : estado.colorParteAbajoMangas,
    color_parte_abajo_camiseta : esChompa ? '' : estado.colorParteAbajoCamiseta,
    color_pantaloneta      : acc.color_pantaloneta,
    color_parte_abajo_pant : acc.color_parte_abajo_pant,
    color_medias           : acc.color_medias,
    color_partearriba_med  : acc.color_partearriba_med,
    color_pantalon_chompa  : acc.color_pantalon_chompa,
    pantaloneta_activa     : acc.pantaloneta_activa,
    medias_activas         : acc.medias_activas,
    pantalon_chompa_activo : acc.pantalon_chompa_activo,
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