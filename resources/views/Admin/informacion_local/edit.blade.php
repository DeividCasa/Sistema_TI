@extends('Admin.panel_admin')
@section('titulo', 'Información del local')
@section('page-title', 'Información del local')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

<style>
  .info-local-form { display: flex; flex-direction: column; gap: 20px; max-width: 1100px; }
  .info-local-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .info-local-3col { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
  /* Evita que una imagen/preview con nombre de archivo largo empuje la columna
     de la grilla más allá de su ancho (comportamiento por defecto de CSS Grid). */
  .info-local-2col > *, .info-local-3col > * { min-width: 0; }
  @media (max-width: 820px) {
    .info-local-2col, .info-local-3col { grid-template-columns: 1fr; }
  }
  .info-local-label {
    display: block; font-size: 0.78rem; font-weight: 600; color: var(--text-2);
    text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 7px;
  }
  .info-local-input {
    width: 100%; padding: 11px 14px; border: 1.5px solid var(--border); border-radius: 10px;
    font-family: var(--font-b); font-size: 0.93rem; color: var(--text-1); background: var(--bg-2); outline: none;
  }
</style>


<div class="sec-header reveal">
  <div class="sec-title">Información del local</div>
</div>

<form action="{{ route('admin.informacion-local.update') }}" method="POST" enctype="multipart/form-data" class="info-local-form">
  @csrf
  @method('PUT')

  {{-- ── BANNER PRINCIPAL ────────────────────────────────────────── --}}
  <div class="card card-pad reveal">
    <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:16px;">Banner principal (página de inicio)</div>

    @include('Admin.componentes.campo-imagen', [
      'name'      => 'imagen',
      'label'     => 'Imagen del banner',
      'currentUrl'=> $info->imagen_path ? asset('storage/'.$info->imagen_path) : null,
      'large'     => true,
      'aspect'    => '21/9',
      'hint'      => 'Recomendado: imagen ancha (ej. 1600×700px) — JPG, PNG o WEBP, máximo 4MB',
    ])

    <div class="info-local-2col">
      <div>
        <label class="info-local-label">Título del banner</label>
        <input type="text" name="banner_titulo" value="{{ old('banner_titulo', $info->banner_titulo) }}"
          placeholder="Si lo dejas vacío, se usa el nombre del local" class="info-local-input">
      </div>
      <div>
        <label class="info-local-label">Subtítulo del banner</label>
        <input type="text" name="banner_subtitulo" value="{{ old('banner_subtitulo', $info->banner_subtitulo) }}"
          placeholder="Si lo dejas vacío, se usa la descripción del local" class="info-local-input">
      </div>
    </div>
  </div>

  {{-- ── CATEGORÍAS DESTACADAS ───────────────────────────────────── --}}
  <div class="card card-pad reveal">
    <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:6px;">Categorías destacadas</div>
    <p style="font-size:0.82rem;color:var(--text-3);margin-bottom:16px;">Imagen para cada tarjeta de categoría en la página de inicio.</p>

    <div class="info-local-3col">
      @include('Admin.componentes.campo-imagen', [
        'name'      => 'categoria_ropa_imagen',
        'label'     => 'Toda la ropa',
        'currentUrl'=> $info->categoria_ropa_imagen ? asset('storage/'.$info->categoria_ropa_imagen) : null,
        'large'     => true,
        'aspect'    => '1/1',
      ])
      @include('Admin.componentes.campo-imagen', [
        'name'      => 'categoria_uniforme_imagen',
        'label'     => 'Uniformes escolares',
        'currentUrl'=> $info->categoria_uniforme_imagen ? asset('storage/'.$info->categoria_uniforme_imagen) : null,
        'large'     => true,
        'aspect'    => '1/1',
      ])
      @include('Admin.componentes.campo-imagen', [
        'name'      => 'categoria_chompa_imagen',
        'label'     => 'Chompas',
        'currentUrl'=> $info->categoria_chompa_imagen ? asset('storage/'.$info->categoria_chompa_imagen) : null,
        'large'     => true,
        'aspect'    => '1/1',
      ])
    </div>
  </div>

  {{-- ── SECCIÓN "VISÍTANOS" ─────────────────────────────────────── --}}
  <div class="card card-pad reveal">
    <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:6px;">Sección "Visítanos"</div>
    <p style="font-size:0.82rem;color:var(--text-3);margin-bottom:16px;">Título y texto que aparecen junto a los datos de contacto, al final de la página de inicio.</p>

    <div style="margin-bottom:14px;">
      <label class="info-local-label">Título</label>
      <input type="text" name="visitanos_titulo" value="{{ old('visitanos_titulo', $info->visitanos_titulo) }}"
        placeholder="Visítanos" class="info-local-input">
    </div>
    <div>
      <label class="info-local-label">Texto</label>
      <textarea name="visitanos_texto" rows="3" placeholder="Conoce el local, resuelve tus dudas o pasa a recoger tu pedido — estos son nuestros datos de contacto."
        class="info-local-input" style="resize:vertical;">{{ old('visitanos_texto', $info->visitanos_texto) }}</textarea>
    </div>
  </div>

  {{-- ── INFORMACIÓN DEL LOCAL ───────────────────────────────────── --}}
  <div class="card card-pad reveal">
    <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:16px;">Datos del local</div>

    <div class="info-local-2col" style="margin-bottom:14px;">
      <div>
        <label class="info-local-label">Nombre del local</label>
        <input type="text" name="nombre_local" value="{{ old('nombre_local', $info->nombre_local) }}" class="info-local-input">
      </div>
      <div>
        <label class="info-local-label">Horario de atención</label>
        <input type="text" name="horario" value="{{ old('horario', $info->horario) }}"
          placeholder="Ej: Lunes a viernes, 8:00 - 18:00" class="info-local-input">
      </div>
    </div>

    <div style="margin-bottom:14px;">
      <label class="info-local-label">Descripción</label>
      <textarea name="descripcion" rows="3" class="info-local-input" style="resize:vertical;">{{ old('descripcion', $info->descripcion) }}</textarea>
    </div>

    <div class="info-local-2col">
      <div>
        <label class="info-local-label">Dirección</label>
        <input type="text" name="direccion" value="{{ old('direccion', $info->direccion) }}" class="info-local-input">
      </div>
      <div>
        <label class="info-local-label">Teléfono</label>
        <input type="text" name="telefono" value="{{ old('telefono', $info->telefono) }}" class="info-local-input">
      </div>
    </div>
    <div style="margin-top:14px;">
      <label class="info-local-label">Correo de contacto</label>
      <input type="email" name="email_contacto" value="{{ old('email_contacto', $info->email_contacto) }}" class="info-local-input">
    </div>
  </div>

  {{-- ── WHATSAPP FLOTANTE ────────────────────────────────────────── --}}
  <div class="card card-pad reveal">
    <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:6px;">WhatsApp flotante</div>
    <p style="font-size:0.82rem;color:var(--text-3);margin-bottom:16px;">Datos que se muestran en el botón flotante de WhatsApp, en todas las páginas del cliente.</p>

    <div class="info-local-2col" style="margin-bottom:14px;">
      <div>
        <label class="info-local-label">Número de WhatsApp</label>
        <input type="text" name="whatsapp_numero" value="{{ old('whatsapp_numero', $info->whatsapp_numero) }}"
          placeholder="Ej: 593992502749" class="info-local-input">
      </div>
      <div>
        <label class="info-local-label">Horario a mostrar</label>
        <input type="text" name="whatsapp_horario" value="{{ old('whatsapp_horario', $info->whatsapp_horario) }}"
          placeholder="Ej: Todos los días: 7:00 AM - 6:00 PM" class="info-local-input">
      </div>
    </div>

    <div style="margin-bottom:14px;">
      <label class="info-local-label">Dirección a mostrar</label>
      <input type="text" name="whatsapp_direccion" value="{{ old('whatsapp_direccion', $info->whatsapp_direccion) }}" class="info-local-input">
    </div>

    <div>
      <label class="info-local-label">Mensaje predeterminado</label>
      <input type="text" name="whatsapp_mensaje" value="{{ old('whatsapp_mensaje', $info->whatsapp_mensaje) }}"
        placeholder="Hola, quisiera más información sobre sus productos." class="info-local-input">
    </div>
  </div>

  {{-- ── UBICACIÓN EN EL MAPA ────────────────────────────────────── --}}
  <div class="card card-pad reveal">
    <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:6px;">Ubicación en el mapa</div>

    @if(config('services.google_maps.key'))
      <p style="font-size:0.82rem;color:var(--text-3);margin-bottom:16px;">
        Haz clic en el mapa o arrastra el marcador para elegir dónde aparece tu local en la página de inicio de los clientes.
      </p>

      <div id="mapa-local" style="width:100%;height:320px;border-radius:10px;border:1px solid var(--border);"></div>

      <div style="text-align:right;margin-top:10px;">
        <button type="button" id="btn-zoom-in" class="btn-secondary" style="padding:6px 14px;font-size:0.8rem;margin-right:6px;">
          + Acercar
        </button>
        <button type="button" id="btn-zoom-out" class="btn-secondary" style="padding:6px 14px;font-size:0.8rem;">
          − Alejar
        </button>
      </div>
    @else
      <p style="font-size:0.82rem;color:var(--text-3);">
        Falta configurar la clave de Google Maps (<code>GOOGLE_MAPS_API_KEY</code> en el archivo <code>.env</code>) para mostrar el mapa aquí.
      </p>
    @endif

    <input type="hidden" id="mapa_lat" name="mapa_lat" value="{{ old('mapa_lat', $info->mapa_lat ?? -0.9346) }}">
    <input type="hidden" id="mapa_lng" name="mapa_lng" value="{{ old('mapa_lng', $info->mapa_lng ?? -78.6157) }}">
  </div>

  <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
    Guardar cambios
  </button>
</form>

@if(config('services.google_maps.key'))
  @push('scripts')
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMapaAdminLeoJose" async defer></script>
  <script>
    function initMapaAdminLeoJose() {
      const latInput = document.getElementById('mapa_lat');
      const lngInput = document.getElementById('mapa_lng');
      const centro = { lat: parseFloat(latInput.value), lng: parseFloat(lngInput.value) };

      const mapa = new google.maps.Map(document.getElementById('mapa-local'), {
        center: centro,
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
      });

      const marcador = new google.maps.Marker({
        position: centro,
        map: mapa,
        draggable: true,
        title: 'Arrastra para ajustar la ubicación',
      });

      function actualizarCoords(posicion) {
        latInput.value = posicion.lat().toFixed(7);
        lngInput.value = posicion.lng().toFixed(7);
      }

      google.maps.event.addListener(marcador, 'dragend', function () {
        actualizarCoords(marcador.getPosition());
      });

      mapa.addListener('click', function (e) {
        marcador.setPosition(e.latLng);
        actualizarCoords(e.latLng);
      });

      document.getElementById('btn-zoom-in').addEventListener('click', function () {
        mapa.setZoom(mapa.getZoom() + 1);
      });
      document.getElementById('btn-zoom-out').addEventListener('click', function () {
        mapa.setZoom(mapa.getZoom() - 1);
      });
    }
  </script>
  @endpush
@endif

@endsection
