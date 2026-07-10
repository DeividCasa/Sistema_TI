async function generarIA() {
  const prompt = document.getElementById('ia-prompt').value.trim();
  if (!prompt) { toast('Escribe una descripción primero', 'error'); return; }

  document.getElementById('ia-loading').style.display = 'block';
  document.getElementById('ia-resultado').style.display = 'none';

  try {
    const body = new URLSearchParams({ prompt, tipo_prenda: tipoPrendaActual });
    const r = await fetch(URL_GENERAR_IA, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': CSRF,
        'Accept': 'application/json',
      },
      body,
    });
    const data = await r.json();

    document.getElementById('ia-loading').style.display = 'none';

    if (!data.success) {
      toast(data.message || 'No se pudo generar el diseño. Intenta de nuevo.', 'error');
      return;
    }

    aplicarResultadoIA(data);
  } catch {
    document.getElementById('ia-loading').style.display = 'none';
    toast('Ocurrió un error generando el diseño. Intenta de nuevo.', 'error');
  }
}

function aplicarResultadoIA(data) {
  if (data.colores) {
    Object.entries(data.colores).forEach(([key3d, hex]) => {
      if (hex) actualizarColor3D(key3d, hex);
    });
    refrescarSilueta();
    guardarHistorial();
  }

  const pasos = [];
  if (data.rayas && data.rayas.activo && data.rayas.color) {
    pasos.push(() => agregarRayasIA(data.rayas.color, data.rayas.zonas, data.rayas.cantidad, data.rayas.direccion));
  }
  if (data.figuras && data.figuras.length) {
    pasos.push(() => agregarFigurasIA(data.figuras));
  }

  pasos.reduce((cadena, paso) => cadena.then(paso), Promise.resolve()).then(() => {
    const sugeridos = data.elementos_sugeridos || [];
    const resumen = document.getElementById('ia-resultado');
    if (sugeridos.length) {
      resumen.textContent = 'Sugerencia: agrega manualmente desde la pestaña Logo → '
        + sugeridos.join(' · ');
      resumen.style.display = 'block';
    }
    toast('✓ Diseño aplicado', 'success');
  });
}

/* Dibuja rayas del color, cantidad y dirección indicados en cada una de las
   zonas dadas (típicamente frente/atrás), cambiando de vista temporalmente
   para poder escribir en el canvas 2D de cada una y luego sincronizando el
   3D de todas las vistas al final. */
async function agregarRayasIA(color, zonas, cantidad, direccion) {
  const vistaOriginal = vistaActual;
  const listaZonas = (zonas && zonas.length) ? zonas : ['frente', 'atras'];
  const n = Math.min(Math.max(parseInt(cantidad, 10) || 4, 1), 12);
  const vertical = direccion === 'vertical';

  for (const zona of listaZonas) {
    if (!VISTAS[zona] || !vistaPermiteDiseno(zona)) continue;

    await new Promise(resolve => cambiarVista(zona, null, resolve));

    // Si ya se generó un diseño IA antes en esta zona, se quitan sus rayas
    // previas para no apilarlas encima de las nuevas.
    fabricCanvas.getObjects()
      .filter(o => typeof o.id === 'string' && o.id.startsWith('figura-rayas-'))
      .forEach(o => fabricCanvas.remove(o));

    const rectZona = ZONAS_DISENO_2D[zona];
    if (!rectZona) continue;

    const grosor = (vertical ? rectZona.w : rectZona.h) / (n * 2);
    const rayas = [];
    for (let i = 0; i < n; i++) {
      rayas.push(new fabric.Rect(vertical ? {
        left: -rectZona.w / 2 + i * (grosor * 2),
        top: -rectZona.h / 2,
        width: grosor,
        height: rectZona.h,
        fill: color,
      } : {
        left: -rectZona.w / 2,
        top: -rectZona.h / 2 + i * (grosor * 2),
        width: rectZona.w,
        height: grosor,
        fill: color,
      }));
    }

    const p = puntoInicialDiseno(zona);
    const grupo = new fabric.Group(rayas, {
      left: p.x, top: p.y, originX: 'center', originY: 'center',
      id: 'figura-rayas-' + Date.now(), tipo: 'figura',
    });

    fabricCanvas.add(grupo);
    limitarObjetoZona(grupo);
    fabricCanvas.renderAll();
    guardarCanvasActual();
  }

  await new Promise(resolve => cambiarVista(vistaOriginal, null, resolve));
  actualizarTodasTexturas3D();
}

/* Construye una figura simple (mismo vocabulario que la pestaña Figuras:
   corazón, estrella, círculo, cuadro, triángulo) con el color dado. */
function construirFiguraIA(tipo, color, punto) {
  const base = {
    left: punto.x, top: punto.y, originX: 'center', originY: 'center',
    fill: color, id: 'figura-ia-' + Date.now() + '-' + tipo, tipo: 'figura',
  };
  if (tipo === 'rect') return new fabric.Rect({ ...base, width: 120, height: 80, rx: 6, ry: 6 });
  if (tipo === 'circle') return new fabric.Circle({ ...base, radius: 52 });
  if (tipo === 'triangle') return new fabric.Triangle({ ...base, width: 120, height: 110 });
  if (tipo === 'star') return new fabric.Polygon(puntosEstrella(0, 0, 58, 25, 5), base);
  if (tipo === 'heart') {
    return new fabric.Path('M 0 -28 C -46 -72 -108 -12 0 66 C 108 -12 46 -72 0 -28 Z', {
      ...base, scaleX: .85, scaleY: .85,
    });
  }
  return null;
}

/* Agrega las figuras simples sugeridas por la IA (ej. un corazón rojo en el
   frente), cambiando de vista temporalmente igual que las rayas. */
async function agregarFigurasIA(figuras) {
  const vistaOriginal = vistaActual;

  for (const f of figuras) {
    const zona = (f.zona && VISTAS[f.zona]) ? f.zona : 'frente';
    if (!vistaPermiteDiseno(zona)) continue;

    await new Promise(resolve => cambiarVista(zona, null, resolve));

    const obj = construirFiguraIA(f.tipo, f.color, puntoInicialDiseno(zona));
    if (!obj) continue;

    fabricCanvas.add(obj);
    limitarObjetoZona(obj);
    fabricCanvas.renderAll();
    guardarCanvasActual();
  }

  await new Promise(resolve => cambiarVista(vistaOriginal, null, resolve));
  actualizarTodasTexturas3D();
}
