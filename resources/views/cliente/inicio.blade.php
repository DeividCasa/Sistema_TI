@extends('layouts.catalogo')

@section('titulo', 'Leo José | Inicio')
@section('body-class', 'pagina-inicio')

@section('contenido')

{{-- ── BANNER PRINCIPAL (una sola imagen: sin carrusel, sin animación
     escalonada — evita el parpadeo/superposición que causaba slick+animate.css) ── --}}
<section class="section-slide">
  <div class="item-slick1" style="background-image: linear-gradient(90deg, rgba(10,12,10,0.72) 0%, rgba(10,12,10,0.42) 45%, rgba(10,12,10,0.05) 75%), url('{{ $info->imagen_path ? asset('storage/'.$info->imagen_path) : asset('images/fondo.png') }}');">
    <div class="container h-full">
      <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
        <span class="ltext-101 cl0 respon2" style="opacity:0.85;">
          Ropa deportiva &amp; uniformes
        </span>

        <h2 class="ltext-201 cl0 p-t-19 p-b-43 respon1">
          {{ $info->banner_titulo ?: ($info->nombre_local ?? 'Leo José') }}
        </h2>

        <a href="{{ route('cliente.catalogo.index') }}" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
          Ver catálogo
        </a>
      </div>
    </div>
  </div>
</section>


{{-- ── CATEGORÍAS ────────────────────────────────────────────────── --}}
<div class="sec-banner bg0 p-t-80 p-b-50">
  <div class="container">
    <div class="row">
      @foreach([
        ['label' => 'Toda la ropa',        'sub' => 'Catálogo completo', 'imagen' => $info->categoria_ropa_imagen,     'categoria' => null],
        ['label' => 'Uniformes escolares', 'sub' => 'Colegios de Salcedo', 'imagen' => $info->categoria_uniforme_imagen, 'categoria' => 'uniforme'],
        ['label' => 'Chompas',             'sub' => 'Abrigo con estilo', 'imagen' => $info->categoria_chompa_imagen,   'categoria' => 'chompa'],
      ] as $cat)
        <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
          <div class="block1 wrap-pic-w">
            @if($cat['imagen'])
              <img src="{{ asset('storage/'.$cat['imagen']) }}" alt="{{ $cat['label'] }}">
            @else
              <img src="{{ asset('images/fondo.png') }}" alt="{{ $cat['label'] }}">
            @endif

            <a href="{{ route('cliente.catalogo.index', $cat['categoria'] ? ['categoria' => $cat['categoria']] : []) }}" class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
              <div class="block1-txt-child1 flex-col-l">
                <span class="block1-name ltext-102 trans-04 p-b-8">{{ $cat['label'] }}</span>
                <span class="block1-info stext-102 trans-04">{{ $cat['sub'] }}</span>
              </div>

              <div class="block1-txt-child2 p-b-4 trans-05">
                <div class="block1-link stext-101 cl0 trans-09">Ver más</div>
              </div>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

@push('estilos')
<style>
  /* Las letras de las 3 categorías (Toda la ropa / Uniformes / Chompas) no deben
     quedar flotando directo sobre la foto de la prenda: se deja un espacio en
     blanco arriba de la foto para el título, en vez de texto crudo encima de
     la imagen (sin cajas ni fondos, el texto se ve igual que antes). */
  .sec-banner .block1 {
    background: #fff;
  }
  .sec-banner .block1 > img {
    padding-top: 92px;
  }
</style>
@endpush


{{-- ── PRODUCTOS ─────────────────────────────────────────────────── --}}
@if($destacados->isNotEmpty())
<section class="bg0 p-t-23 p-b-95">
  <div class="container">
    <div class="p-b-10">
      <h3 class="ltext-103 cl5">Nuestros productos</h3>
    </div>

    <div class="flex-w flex-sb-m p-b-52">
      <div class="flex-w flex-l-m filter-tope-group m-tb-10">
        <button type="button" class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">Todos</button>
        <button type="button" class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".uniforme">Uniformes</button>
        <button type="button" class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".chompa">Chompas</button>
        <button type="button" class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".camiseta">Camisetas</button>
      </div>
    </div>

    <div class="row isotope-grid">
      @foreach($destacados as $producto)
        <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{ $producto['tipo'] }}">
          <div class="block2">
            <div class="block2-pic hov-img0">
              @if($producto['imagen'])
                <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}">
              @else
                <img src="{{ asset('images/fondo.png') }}" alt="{{ $producto['nombre'] }}">
              @endif

              <a href="{{ $producto['url'] }}" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
                Ver producto
              </a>
            </div>

            <div class="block2-txt flex-w flex-t p-t-14">
              <div class="block2-txt-child1 flex-col-l">
                <a href="{{ $producto['url'] }}" class="stext-104 cl4 hov-cl1 trans-04 p-b-6">
                  {{ $producto['nombre'] }}
                </a>
                <span class="stext-105 cl3">${{ number_format($producto['precio'], 2) }}</span>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="flex-c-m flex-w w-full p-t-45">
      <a href="{{ route('cliente.catalogo.index') }}" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
        Ver todo el catálogo
      </a>
    </div>
  </div>
</section>
@endif


{{-- ── TESTIMONIOS ───────────────────────────────────────────────── --}}
<section class="bg6 p-t-80 p-b-70">
  <div class="container">
    <div class="p-b-40 txt-center">
      <h3 class="ltext-103 cl5">Lo que dicen nuestros clientes</h3>
      <a href="{{ route('cliente.testimonios.create') }}" class="stext-106 cl6 hov-cl1 trans-04">⭐ Danos tu opinión</a>
    </div>

    @if($testimonios->isEmpty())
      <div class="txt-center">
        <p class="stext-107 cl6 p-b-20">Aún no tenemos opiniones publicadas — ¡sé el primero en compartir tu experiencia!</p>
        <a href="{{ route('cliente.testimonios.create') }}" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04" style="margin:0 auto;">
          Dejar mi opinión
        </a>
      </div>
    @else
      <div class="row">
        @foreach($testimonios as $testimonio)
          <div class="col-md-6 col-lg-4 p-b-30">
            <div class="bg0 bor19 p-all-30" style="border-radius:8px;height:100%;">
              <div class="flex-w flex-m p-b-15">
                @if($testimonio->imagen)
                  <img src="{{ asset('storage/'.$testimonio->imagen) }}" alt="{{ $testimonio->nombre_cliente }}" style="width:46px;height:46px;border-radius:50%;object-fit:cover;margin-right:12px;">
                @else
                  <div class="flex-c-m" style="width:46px;height:46px;border-radius:50%;background:#F1592A;color:#fff;font-family:'Poppins-SemiBold';margin-right:12px;">
                    {{ strtoupper(mb_substr($testimonio->nombre_cliente, 0, 1)) }}
                  </div>
                @endif
                <div>
                  <div class="stext-104 cl2">{{ $testimonio->nombre_cliente }}</div>
                  @if($testimonio->calificacion)
                    <div style="color:#F59E0B;font-size:0.8rem;">
                      {{ str_repeat('★', $testimonio->calificacion) }}{{ str_repeat('☆', 5 - $testimonio->calificacion) }}
                    </div>
                  @endif
                </div>
              </div>
              <p class="stext-102 cl6">"{{ $testimonio->texto }}"</p>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>


{{-- ── VISÍTANOS ─────────────────────────────────────────────────── --}}
<section class="bg2 p-t-80 p-b-80" style="background:#222;">
  <div class="container">
    <div class="row">
      <div class="col-lg-5 p-b-30">
        <h3 class="ltext-103" style="color:#fff;">{{ $info->visitanos_titulo ?: 'Visítanos' }}</h3>
        <p class="stext-102 p-t-15" style="color:rgba(255,255,255,0.6);max-width:340px;">
          {{ $info->visitanos_texto ?: 'Conoce el local, resuelve tus dudas o pasa a recoger tu pedido — estos son nuestros datos de contacto.' }}
        </p>
      </div>
      <div class="col-lg-7 p-b-30">
        <div class="row">
          @if($info->direccion)
            <div class="col-sm-6 p-b-20">
              <div class="stext-107" style="color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.06em;font-size:0.7rem;">Dirección</div>
              <div class="stext-104" style="color:#fff;margin-top:4px;">{{ $info->direccion }}</div>
            </div>
          @endif
          @if($info->horario)
            <div class="col-sm-6 p-b-20">
              <div class="stext-107" style="color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.06em;font-size:0.7rem;">Horario</div>
              <div class="stext-104" style="color:#fff;margin-top:4px;">{{ $info->horario }}</div>
            </div>
          @endif
          @if($info->telefono)
            <div class="col-sm-6 p-b-20">
              <div class="stext-107" style="color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.06em;font-size:0.7rem;">Teléfono</div>
              <div class="stext-104" style="color:#fff;margin-top:4px;">{{ $info->telefono }}</div>
            </div>
          @endif
          @if($info->email_contacto)
            <div class="col-sm-6 p-b-20">
              <div class="stext-107" style="color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.06em;font-size:0.7rem;">Correo</div>
              <div class="stext-104" style="color:#fff;margin-top:4px;">{{ $info->email_contacto }}</div>
            </div>
          @endif
        </div>

        @if(config('services.google_maps.key'))
          <div style="margin-top:20px;">
            <div id="mapa-local-cliente" style="width:100%;height:280px;border-radius:8px;overflow:hidden;"></div>
            <div style="text-align:right;margin-top:10px;">
              <button type="button" id="btn-zoom-in-cliente" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04" style="display:inline-flex;margin-right:6px;">+ Acercar</button>
              <button type="button" id="btn-zoom-out-cliente" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04" style="display:inline-flex;">− Alejar</button>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>

@if(config('services.google_maps.key'))
  @push('scripts')
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMapaClienteLeoJose" async defer></script>
  <script>
    function initMapaClienteLeoJose() {
      const centro = { lat: {{ $info->mapa_lat ?? -0.9346 }}, lng: {{ $info->mapa_lng ?? -78.6157 }} };
      const mapa = new google.maps.Map(document.getElementById('mapa-local-cliente'), { center: centro, zoom: 15, mapTypeId: google.maps.MapTypeId.ROADMAP });
      new google.maps.Marker({ position: centro, map: mapa, title: @json($info->nombre_local ?: 'Leo José') });
      document.getElementById('btn-zoom-in-cliente').addEventListener('click', function () { mapa.setZoom(mapa.getZoom() + 1); });
      document.getElementById('btn-zoom-out-cliente').addEventListener('click', function () { mapa.setZoom(mapa.getZoom() - 1); });
    }
  </script>
  @endpush
@endif

@endsection
