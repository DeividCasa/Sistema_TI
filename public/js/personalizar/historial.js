let historial = [], hPos = -1;
// Mientras se restaura un estado (undo/redo), loadFromJSON limpia el
// canvas y vuelve a agregar cada objeto uno por uno, disparando los
// mismos eventos "object:added"/"object:removed" que un cambio real del
// usuario. Sin esta bandera, cada undo/redo terminaba llamando de nuevo
// a guardarHistorial() por su cuenta, empujando el estado "restaurado"
// como si fuera nuevo y borrando silenciosamente la pila de rehacer —
// por eso deshacer/rehacer se sentía roto.
let restaurandoHistorial = false;

function reiniciarHistorial() {
  historial = [];
  hPos = -1;
  guardarHistorial();
}

function guardarHistorial() {
  if (restaurandoHistorial) return;
  historial = historial.slice(0, hPos + 1);
  historial.push(JSON.stringify(fabricCanvas.toJSON(FABRIC_PROPS)));
  hPos = historial.length - 1;
  document.getElementById('btn-undo').disabled = hPos <= 0;
  document.getElementById('btn-redo').disabled = true;
}

function restaurarEstadoHistorial(nuevoPos) {
  hPos = nuevoPos;
  restaurandoHistorial = true;
  fabricCanvas.loadFromJSON(JSON.parse(historial[hPos]), () => {
    restaurandoHistorial = false;
    normalizarTextosCanvas();
    fabricCanvas.renderAll(); refrescarSilueta(); actualizarTextura3D();
  });
  document.getElementById('btn-undo').disabled = hPos <= 0;
  document.getElementById('btn-redo').disabled = hPos >= historial.length - 1;
}

function undo(){
  if(hPos<=0) return;
  restaurarEstadoHistorial(hPos - 1);
}
function redo(){
  if(hPos>=historial.length-1) return;
  restaurarEstadoHistorial(hPos + 1);
}

/* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
   FABRIC.JS â€” CANVAS 2D
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
