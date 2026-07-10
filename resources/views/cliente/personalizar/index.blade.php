<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $plantilla->nombre ?? 'Crea tu diseño' }} - Editor</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/personalizar/editor.css') }}">
  <link rel="stylesheet" href="{{ asset('css/personalizar/panel.css') }}">
  <link rel="stylesheet" href="{{ asset('css/personalizar/canvas.css') }}">
  <link rel="stylesheet" href="{{ asset('css/personalizar/visor3d.css') }}">
</head>
<body>
@include('cliente.personalizar.componentes.topbar')

<div class="editor-body">
  @include('cliente.personalizar.componentes.panel-herramientas')

  <div class="work-area">
    @include('cliente.personalizar.componentes.canvas')
    @include('cliente.personalizar.componentes.visor3d')
  </div>
</div>

<div id="toast"></div>

<script>
/* Parche para Fabric.js 5.x que usa 'alphabetical' inválido en Canvas API */
(function() {
  const desc = Object.getOwnPropertyDescriptor(CanvasRenderingContext2D.prototype, 'textBaseline');
  if (desc && desc.set) {
    Object.defineProperty(CanvasRenderingContext2D.prototype, 'textBaseline', {
      get: desc.get,
      set: function(val) {
        desc.set.call(this, val === 'alphabetical' ? 'alphabetic' : val);
      },
      configurable: true,
    });
  }
})();
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script>
const CSRF = "{{ csrf_token() }}";
const PLANTILLA_ID = {{ $plantilla->id ?? 'null' }};
const RUTAS_MODELO = {
  camiseta  : "{{ asset('modelos/camiseta1.glb') }}",
  chompa    : "{{ asset('modelos/chompa.glb') }}",
  completo  : "{{ asset('modelos/modeloCompleto.glb') }}",
  pantaloneta: "{{ asset('modelos/shorts.glb') }}",
  medias    : "{{ asset('modelos/medias.glb') }}",
  pantalon  : "{{ asset('modelos/pantalonDeportivo.glb') }}",
};
const URL_GUARDAR_DISENO = "{{ route('disenios.store') }}";
const URL_LOGOS = "{{ route('logos.index') }}";
const URL_GENERAR_IA = "{{ route('disenios.generar-ia') }}";
</script>
<script src="{{ asset('js/personalizar/prendas.js') }}"></script>
<script src="{{ asset('js/personalizar/historial.js') }}"></script>
<script src="{{ asset('js/personalizar/canvas2d.js') }}"></script>
<script src="{{ asset('js/personalizar/three-viewer.js') }}"></script>
<script src="{{ asset('js/personalizar/colores.js') }}"></script>
<script src="{{ asset('js/personalizar/logos.js') }}"></script>
<script src="{{ asset('js/personalizar/texto.js') }}"></script>
<script src="{{ asset('js/personalizar/figuras.js') }}"></script>
<script src="{{ asset('js/personalizar/ia.js') }}"></script>
<script src="{{ asset('js/personalizar/utilidades.js') }}"></script>
<script src="{{ asset('js/personalizar/canvas-pantaloneta.js') }}"></script>
<script src="{{ asset('js/personalizar/accesorios.js') }}"></script>
<script src="{{ asset('js/personalizar/persistencia.js') }}"></script>
<script src="{{ asset('js/personalizar/app.js') }}"></script>
</body>
</html>