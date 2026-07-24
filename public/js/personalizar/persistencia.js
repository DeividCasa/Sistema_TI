/* ═══════════════════════════════════════════════════════════════
   PERSISTENCIA LOCAL DEL BORRADOR
   Antes, recargar la página (F5, cierre accidental de pestaña, etc.)
   borraba todo el trabajo del cliente sin aviso. Esto guarda un
   "borrador" en localStorage periódicamente y lo restaura al abrir
   el editor, para que un refresh accidental no pierda el diseño.
   ═══════════════════════════════════════════════════════════════ */

const CLAVE_BORRADOR = 'personalizar_borrador_' + (typeof CLIENTE_ID !== 'undefined' && CLIENTE_ID ? CLIENTE_ID : 'anon') + '_' + (typeof PLANTILLA_ID !== 'undefined' && PLANTILLA_ID ? PLANTILLA_ID : 'libre');

function guardarBorradorLocal() {
  try {
    if (typeof fabricCanvas === 'undefined' || !fabricCanvas) return;

    guardarCanvasActual(); // vuelca fabricCanvas al canvasData[vistaActual] de la prenda activa

    const datos = {
      version: 1,
      guardadoEn: Date.now(),
      nombreDiseno: document.getElementById('nombre-diseno')?.value || '',
      tipoPrendaActual,
      vistaActual,
      estado: { ...estado },
      canvasData: {
        camiseta: PRENDAS.camiseta.canvasData,
        chompa: PRENDAS.chompa.canvasData,
      },
      pantaloneta: {
        activo: !!ACCESORIOS?.pantaloneta?.activo,
        canvasJSON: (typeof getPantalonetaJSON === 'function') ? getPantalonetaJSON() : null,
      },
      medias: {
        activo: !!ACCESORIOS?.medias?.activo,
      },
      pantalon: {
        activo: !!ACCESORIOS?.pantalon?.activo,
      },
    };
    localStorage.setItem(CLAVE_BORRADOR, JSON.stringify(datos));
  } catch (e) {
    // localStorage puede fallar (modo privado, cuota llena, etc.) — no es crítico
  }
}

function borrarBorradorLocal() {
  try { localStorage.removeItem(CLAVE_BORRADOR); } catch (e) {}
}

function leerBorradorLocal() {
  try {
    const raw = localStorage.getItem(CLAVE_BORRADOR);
    return raw ? JSON.parse(raw) : null;
  } catch (e) {
    return null;
  }
}

/* Aplica los datos del borrador ANTES de que corran los init normales
   (activarConfiguracionPrenda, initFabric, init3D, ...), para que carguen
   ya apuntando a la prenda/vista/colores correctos desde el principio. */
function prepararRestauracionBorrador() {
  const datos = leerBorradorLocal();
  if (!datos) return null;

  if (datos.estado) Object.assign(estado, datos.estado);
  if (datos.canvasData?.camiseta) PRENDAS.camiseta.canvasData = datos.canvasData.camiseta;
  if (datos.canvasData?.chompa) PRENDAS.chompa.canvasData = datos.canvasData.chompa;
  if (datos.tipoPrendaActual && PRENDAS[datos.tipoPrendaActual]) tipoPrendaActual = datos.tipoPrendaActual;
  if (datos.nombreDiseno) {
    const input = document.getElementById('nombre-diseno');
    if (input) input.value = datos.nombreDiseno;
  }

  return datos;
}

/* Termina de aplicar lo que necesita que el canvas/modelo 3D ya existan
   (vista actual, pestaña de prenda activa, accesorios y su propio canvas). */
function terminarRestauracionBorrador(datos) {
  if (!datos) return;

  document.querySelectorAll('.prenda-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('btn-prenda-' + tipoPrendaActual)?.classList.add('active');

  if (datos.vistaActual && VISTAS[datos.vistaActual]) {
    vistaActual = datos.vistaActual;
    document.querySelectorAll('.vista-tab').forEach(b => {
      b.classList.toggle('active', b.dataset.vista === vistaActual);
    });
  }
  cargarCanvasDeVista(vistaActual);
  actualizarBadgeZona();
  actualizarDotsVistas();
  reiniciarHistorial();

  // Pantaloneta/Medias solo existen para la camiseta (ver
  // sincronizarAccesoriosConPrenda): si el borrador se guardó en chompa
  // con estos activos de una sesión anterior, no deben restaurarse.
  if (tipoPrendaActual === 'camiseta') {
    if (datos.medias?.activo) toggleAccesorio('medias');

    if (datos.pantaloneta?.activo) {
      toggleAccesorio('pantaloneta');
      // El canvas de la pantaloneta se crea con un pequeño retraso (ver
      // toggleAccesorio → initFabricPantaloneta); esperamos ese mismo margen
      // antes de volcarle el JSON guardado.
      setTimeout(() => {
        if (fabricPant && datos.pantaloneta.canvasJSON) {
          fabricPant.loadFromJSON(datos.pantaloneta.canvasJSON, () => {
            fabricPant.renderAll();
            if (typeof actualizarTexturaPantaloneta3D === 'function') actualizarTexturaPantaloneta3D();
          });
        }
      }, 120);
    }
  }

  // El Pantalón solo existe para la chompa (ver sincronizarAccesoriosConPrenda).
  if (tipoPrendaActual === 'chompa' && datos.pantalon?.activo) {
    toggleAccesorio('pantalon');
  }

  if (typeof sincronizarAccesoriosConPrenda === 'function') sincronizarAccesoriosConPrenda();

  toast('Se restauró tu diseño anterior', 'success');
}

/* Autoguardado periódico + al cerrar/salir de la pestaña */
function iniciarAutoguardadoBorrador() {
  setInterval(guardarBorradorLocal, 4000);
  window.addEventListener('beforeunload', guardarBorradorLocal);
}
