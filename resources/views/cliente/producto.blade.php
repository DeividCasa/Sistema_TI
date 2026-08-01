@extends('layouts.catalogo')

@section('titulo', $plantilla->nombre)

@section('contenido')

<!-- breadcrumb -->
<div class="container">
  <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
    <a href="{{ route('inicio') }}" class="stext-109 cl8 hov-cl1 trans-04">
      Inicio <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
    </a>
    <a href="{{ session('catalogo_url', route('cliente.catalogo.index')) }}" class="stext-109 cl8 hov-cl1 trans-04">
      Catálogo <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
    </a>
    <span class="stext-109 cl4">{{ $plantilla->nombre }}</span>
  </div>
</div>

<!-- Product Detail -->
<section class="sec-product-detail bg0 p-t-45 p-b-60">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-lg-7 p-b-30">
        <div class="p-l-25 p-r-30 p-lr-0-lg">
          <div class="wrap-pic-w pos-relative gallery-lb">
            @if($plantilla->imagen_preview)
              <img src="{{ asset('storage/'.$plantilla->imagen_preview) }}" alt="{{ $plantilla->nombre }}" style="border-radius:8px;">
              <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="{{ asset('storage/'.$plantilla->imagen_preview) }}">
                <i class="fa fa-expand"></i>
              </a>
            @else
              <img src="{{ asset('images/fondo.png') }}" alt="{{ $plantilla->nombre }}" style="border-radius:8px;">
            @endif
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-5 p-b-30">
        <div class="p-r-50 p-t-5 p-lr-0-lg">
          <h4 class="mtext-105 cl2 p-b-14">{{ $plantilla->nombre }}</h4>

          <span class="stext-107" style="color:var(--blue);background:var(--blue-soft);padding:4px 12px;border-radius:20px;text-transform:capitalize;margin-right:6px;">
            {{ $plantilla->tipo_prenda }}
          </span>
          <span class="stext-107" style="color:var(--blue);background:var(--blue-soft);padding:4px 12px;border-radius:20px;">
            {{ ['hombre' => 'Para Hombre', 'mujer' => 'Para Mujer'][$plantilla->genero] ?? 'Unisex' }}
          </span>

          <div class="mtext-106 cl2 p-t-20">
            ${{ number_format($plantilla->precio, 2) }}
            <span class="stext-107 cl6">/ unidad</span>
          </div>

          @if($plantilla->descripcion)
            <p class="stext-102 cl6 p-t-15">{{ $plantilla->descripcion }}</p>
          @endif

          <form action="{{ route('cliente.plantillas.agregar') }}" method="POST">
            @csrf
            <input type="hidden" name="plantilla_id" value="{{ $plantilla->id }}">

            <div class="p-t-30">
              @if(!empty($plantilla->colores))
                <div class="p-b-20">
                  <span class="mtext-102 cl2 p-b-10" style="display:block;">Color</span>
                  <div class="flex-w" style="gap:10px;">
                    @foreach($plantilla->colores as $i => $color)
                      <label style="cursor:pointer;">
                        <input type="radio" name="color" value="{{ $color }}" {{ $i == 0 ? 'checked' : '' }} style="display:none;" onchange="marcarColor(this)">
                        <span class="color-opcion" style="width:32px;height:32px;border-radius:50%;background:{{ $color }};
                          border:3px solid {{ $i == 0 ? 'var(--blue)' : '#ebebeb' }};display:inline-block;transition:border-color 0.2s;"></span>
                      </label>
                    @endforeach
                  </div>
                </div>
              @endif

              @if(!empty($plantilla->tallas))
                <div class="p-b-10">
                  <span class="mtext-102 cl2 p-b-10" style="display:block;">Talla</span>
                  <div class="flex-w" style="gap:10px;">
                    @foreach($plantilla->tallas as $i => $talla)
                      <label style="cursor:pointer;">
                        <input type="radio" name="talla" value="{{ $talla }}" {{ $i == 0 ? 'checked' : '' }} style="display:none;" onchange="marcarTalla(this)">
                        <span class="talla-opcion" style="padding:10px 18px;border:1.5px solid {{ $i == 0 ? 'var(--blue)' : '#ebebeb' }};
                          border-radius:8px;background:{{ $i == 0 ? 'var(--blue-soft)' : '#fff' }};
                          color:{{ $i == 0 ? 'var(--blue)' : '#222' }};font-weight:700;font-size:0.85rem;
                          display:inline-block;transition:all 0.2s;">{{ $talla }}</span>
                      </label>
                    @endforeach
                  </div>
                </div>
              @else
                <input type="hidden" name="talla" value="M">
              @endif

              <div class="flex-w flex-r-m p-t-20">
                <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                  <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m" onclick="cambiarCantidad(-1)">
                    <i class="fs-16 zmdi zmdi-minus"></i>
                  </div>
                  <input class="mtext-104 cl3 txt-center num-product" type="number" name="cantidad" id="cantidad" value="1" min="1" readonly>
                  <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m" onclick="cambiarCantidad(1)">
                    <i class="fs-16 zmdi zmdi-plus"></i>
                  </div>
                </div>

                <button type="submit" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                  Agregar al carrito
                </button>
              </div>

              <div style="background:#f8f8f8;border-radius:8px;padding:14px 18px;margin-top:20px;">
                <div class="flex-w flex-sb-m stext-102 cl6">
                  <span>Subtotal</span>
                  <strong id="total-precio" style="color:#222;">${{ number_format($plantilla->precio, 2) }}</strong>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
  const precioUnitario = {{ $plantilla->precio }};

  function cambiarCantidad(delta) {
    const input = document.getElementById('cantidad');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    input.value = val;
    actualizarTotal();
  }

  function actualizarTotal() {
    const cantidad = parseInt(document.getElementById('cantidad').value);
    const total = precioUnitario * cantidad;
    document.getElementById('total-precio').textContent = '$' + total.toFixed(2);
  }

  function marcarColor(radio) {
    document.querySelectorAll('.color-opcion').forEach(s => s.style.borderColor = '#ebebeb');
    radio.nextElementSibling.style.borderColor = 'var(--blue)';
  }

  function marcarTalla(radio) {
    document.querySelectorAll('.talla-opcion').forEach(s => {
      s.style.borderColor = '#ebebeb';
      s.style.background = '#fff';
      s.style.color = '#222';
    });
    const span = radio.nextElementSibling;
    span.style.borderColor = 'var(--blue)';
    span.style.background = 'var(--blue-soft)';
    span.style.color = 'var(--blue)';
  }
</script>
@endpush
