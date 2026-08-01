{{--
  Partial compartido para el detalle de producto con tallas de precio variable
  (chompas y uniformes escolares). Espera:
  $producto      → modelo con nombre/imagen/genero/tipo_tela/descripcion/tallas
  $formAction    → ruta del form "agregar al carrito"
  $hiddenName    → nombre del input hidden (chompa_id | uniforme_id)
  $breadcrumbCat → texto de la categoría en el breadcrumb
  $breadcrumbUrl → url de esa categoría en el catálogo
--}}
<br />
<br />
<br />
<!-- breadcrumb -->
<div class="container">
  <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
    <a href="{{ route('inicio') }}" class="stext-109 cl8 hov-cl1 trans-04">
      Inicio <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
    </a>
    <a href="{{ $breadcrumbUrl }}" class="stext-109 cl8 hov-cl1 trans-04">
      {{ $breadcrumbCat }} <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
    </a>
    <span class="stext-109 cl4">{{ $producto->nombre }}</span>
  </div>
</div>


<!-- Product Detail -->
<section class="sec-product-detail bg0 p-t-45 p-b-60">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-lg-7 p-b-30">
        <div class="p-l-25 p-r-30 p-lr-0-lg">
          <div class="wrap-pic-w pos-relative gallery-lb">
            @if($producto->imagen)
              <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" style="border-radius:8px;">
              <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="{{ asset('storage/' . $producto->imagen) }}">
                <i class="fa fa-expand"></i>
              </a>
            @else
              <img src="{{ asset('images/fondo.png') }}" alt="{{ $producto->nombre }}" style="border-radius:8px;">
            @endif
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-5 p-b-30">
        <div class="p-r-50 p-t-5 p-lr-0-lg">
          <h4 class="mtext-105 cl2 p-b-14">{{ $producto->nombre }}</h4>

          <span class="stext-107" style="color:var(--blue);background:var(--blue-soft);padding:4px 12px;border-radius:20px;text-transform:capitalize;">
            {{ ['hombre' => 'Para Hombre', 'mujer' => 'Para Mujer'][$producto->genero] ?? 'Unisex' }}
          </span>
          <br />
          @if($producto->tipo_tela)
            <p class="stext-102 cl6 p-t-15"><strong class="cl2">Tela:</strong> {{ $producto->tipo_tela }}</p>
          @endif
        
          @if($producto->descripcion)
            <p class="stext-102 cl6 p-t-10">{{ $producto->descripcion }}</p>
          @endif

          <form action="{{ $formAction }}" method="POST">
            @csrf
            <input type="hidden" name="{{ $hiddenName }}" value="{{ $producto->id }}">

            <div class="p-t-30">
              <div class="flex-w flex-sb-m p-b-10">
                <span class="mtext-102 cl2">Elige tu talla</span>
                @include('cliente.componentes.guia-tallas')
              </div>

              <div class="flex-w" style="gap:10px;margin-bottom:10px;">
                @foreach($producto->tallas as $talla)
                  <label style="cursor:pointer;">
                    <input type="radio" name="talla_id" value="{{ $talla->id }}" style="display:none;"
                           onchange="seleccionarTalla(this, {{ $talla->precio }})" {{ old('talla_id') == $talla->id ? 'checked' : '' }}>
                    <div class="opcion-talla" data-talla-id="{{ $talla->id }}"
                         style="border:1.5px solid #ebebeb;border-radius:8px;padding:10px 16px;text-align:center;min-width:76px;transition:all 0.15s;">
                      <div class="stext-104 cl2" style="font-weight:700;">{{ $talla->talla }}</div>
                      <div class="stext-107" style="color:var(--blue);font-weight:700;">${{ number_format($talla->precio, 2) }}</div>
                    </div>
                  </label>
                @endforeach
              </div>

              <div class="flex-w flex-r-m p-t-15">
                <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                  <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m" onclick="cambiarCantidad(-1)">
                    <i class="fs-16 zmdi zmdi-minus"></i>
                  </div>
                  <input class="mtext-104 cl3 txt-center num-product" type="number" name="cantidad" id="cantidad" value="1" min="1" max="100" readonly>
                  <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m" onclick="cambiarCantidad(1)">
                    <i class="fs-16 zmdi zmdi-plus"></i>
                  </div>
                </div>

                <button type="submit" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                  Agregar al carrito
                </button>
              </div>

              <div id="panel-subtotal" style="display:none;background:var(--blue-soft);border-radius:8px;padding:14px 18px;margin-top:20px;">
                <div class="flex-w flex-sb-m stext-102 cl6">
                  <span>Subtotal:</span>
                  <strong id="txt-subtotal" style="color:var(--blue);">$0.00</strong>
                </div>
                <div class="flex-w flex-sb-m stext-107 cl6 p-t-4">
                  <span>Adelanto del 50% (para iniciar el pedido):</span>
                  <span id="txt-adelanto" style="font-weight:700;">$0.00</span>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
let precioSeleccionado = 0;

function seleccionarTalla(radio, precio) {
  precioSeleccionado = precio;
  document.querySelectorAll('.opcion-talla').forEach(el => {
    el.style.borderColor = '#ebebeb';
    el.style.background = 'transparent';
  });
  const caja = radio.nextElementSibling;
  caja.style.borderColor = 'var(--blue)';
  caja.style.background = 'var(--blue-soft)';
  actualizarSubtotal();
}

function actualizarSubtotal() {
  if (precioSeleccionado <= 0) return;
  const cantidad = parseInt(document.getElementById('cantidad').value) || 1;
  const subtotal = precioSeleccionado * cantidad;
  document.getElementById('panel-subtotal').style.display = 'block';
  document.getElementById('txt-subtotal').textContent = '$' + subtotal.toFixed(2);
  document.getElementById('txt-adelanto').textContent = '$' + (subtotal / 2).toFixed(2);
}

function cambiarCantidad(delta) {
  const input = document.getElementById('cantidad');
  let val = parseInt(input.value) + delta;
  if (val < 1) val = 1;
  if (val > 100) val = 100;
  input.value = val;
  actualizarSubtotal();
}
</script>
