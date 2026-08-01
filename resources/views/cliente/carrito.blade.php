@extends('layouts.catalogo')

@section('titulo', 'Mi carrito')

@section('contenido')

@php
  $filas = [];
  foreach ($carritoPlantillas as $key => $item) {
    $filas[] = [
      'key' => $key, 'nombre' => $item['nombre'], 'imagen' => $item['imagen'] ?? null,
      'detalle' => $item['talla'] ? 'Talla ' . $item['talla'] : ($item['tipo_prenda'] ?? ''),
      'color' => $item['color'] ?? null, 'precio' => $item['precio'], 'cantidad' => $item['cantidad'],
      'rutaActualizar' => route('cliente.plantillas.actualizar', $key), 'rutaQuitar' => route('cliente.plantillas.quitar', $key),
    ];
  }
  foreach ($carrito as $key => $item) {
    $filas[] = [
      'key' => $key, 'nombre' => $item['nombre'], 'imagen' => $item['imagen'],
      'detalle' => 'Talla ' . $item['talla'] . ' · ' . $item['tipo_tela'],
      'color' => null, 'precio' => $item['precio'], 'cantidad' => $item['cantidad'],
      'rutaActualizar' => route('cliente.carrito.actualizar', $key), 'rutaQuitar' => route('cliente.carrito.quitar', $key),
    ];
  }
  foreach ($carritoChompas as $key => $item) {
    $filas[] = [
      'key' => $key, 'nombre' => $item['nombre'], 'imagen' => $item['imagen'],
      'detalle' => 'Talla ' . $item['talla'] . ' · ' . $item['tipo_tela'],
      'color' => null, 'precio' => $item['precio'], 'cantidad' => $item['cantidad'],
      'rutaActualizar' => route('cliente.chompas.actualizar', $key), 'rutaQuitar' => route('cliente.chompas.quitar', $key),
    ];
  }
  $carritoVacio = empty($carrito) && empty($carritoChompas) && empty($carritoPlantillas);
@endphp

<!-- breadcrumb -->
<div class="container">
  <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
    <a href="{{ route('inicio') }}" class="stext-109 cl8 hov-cl1 trans-04">
      Inicio <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
    </a>
    <span class="stext-109 cl4">Mi carrito</span>
  </div>
</div>

@if($carritoVacio)
  <div class="container p-t-60 p-b-100 txt-center">
    <i class="zmdi zmdi-shopping-cart" style="font-size:60px;color:#ddd;"></i>
    <p class="stext-102 cl6 p-t-20">Tu carrito está vacío. Ve al catálogo y elige tus productos.</p>
    <a href="{{ session('catalogo_url', route('cliente.catalogo.index')) }}" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04" style="margin:24px auto 0;">
      Ir al catálogo
    </a>
  </div>
@else

  @if($hayAmbos)
    <div class="container p-t-20">
      <div class="stext-102" style="background:var(--blue-soft);color:var(--blue);padding:12px 18px;border-radius:8px;">
        Tienes varios tipos de productos en tu carrito: se confirmarán juntos como <strong>un solo pedido</strong> con un solo pago.
      </div>
    </div>
  @endif

  <div class="bg0 p-t-40 p-b-85">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
          <div class="m-l-25 m-r--38 m-lr-0-xl">
            <div class="wrap-table-shopping-cart">
              <table class="table-shopping-cart">
                <tr class="table_head">
                  <th class="column-1">Producto</th>
                  <th class="column-2"></th>
                  <th class="column-3">Precio</th>
                  <th class="column-4">Cantidad</th>
                  <th class="column-5">Total</th>
                </tr>

                @foreach($filas as $fila)
                  <tr class="table_row">
                    <td class="column-1">
                      <div class="how-itemcart1">
                        @if($fila['imagen'])
                          <img src="{{ asset('storage/' . $fila['imagen']) }}" alt="{{ $fila['nombre'] }}">
                        @else
                          <img src="{{ asset('images/fondo.png') }}" alt="{{ $fila['nombre'] }}">
                        @endif
                      </div>
                    </td>
                    <td class="column-2">
                      {{ $fila['nombre'] }}
                      <br><span class="stext-107 cl6">{{ $fila['detalle'] }}</span>
                      @if($fila['color'])
                        <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:{{ $fila['color'] }};border:1px solid #ddd;margin-left:6px;vertical-align:middle;"></span>
                      @endif
                      <br>
                      <form action="{{ $fila['rutaQuitar'] }}" method="POST" class="p-t-4" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="stext-107" style="background:none;border:none;color:#F1592A;cursor:pointer;padding:0;">
                          <i class="fa fa-trash-o"></i> Quitar
                        </button>
                      </form>
                    </td>
                    <td class="column-3">$ {{ number_format($fila['precio'], 2) }}</td>
                    <td class="column-4">
                      <form action="{{ $fila['rutaActualizar'] }}" method="POST" class="flex-w m-l-auto m-r-0" style="align-items:center;gap:8px;">
                        @csrf
                        <input class="mtext-104 cl3 txt-center num-product" type="number" name="cantidad" value="{{ $fila['cantidad'] }}" min="1" max="100" style="width:52px;">
                        <button type="submit" class="stext-107" style="background:#f8f8f8;border:1px solid #ebebeb;border-radius:6px;padding:6px 10px;cursor:pointer;">
                          Actualizar
                        </button>
                      </form>
                    </td>
                    <td class="column-5">$ {{ number_format($fila['precio'] * $fila['cantidad'], 2) }}</td>
                  </tr>
                @endforeach
              </table>
            </div>
          </div>
        </div>

        <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
          <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
            <h4 class="mtext-109 cl2 p-b-30">Resumen del pedido</h4>

            <div class="flex-w flex-t bor12 p-b-13">
              <div class="size-208"><span class="stext-110 cl2">Total del pedido:</span></div>
              <div class="size-209"><span class="mtext-110 cl2">${{ number_format($totalCombinado, 2) }}</span></div>
            </div>

            <div class="flex-w flex-t bor12 p-t-15 p-b-15">
              <div class="size-208"><span class="stext-110 cl2">Adelanto mínimo (50%):</span></div>
              <div class="size-209"><span class="mtext-110" style="color:var(--blue);">${{ number_format($adelantoCombinado, 2) }}</span></div>
            </div>

            <div class="flex-w flex-t p-t-15 p-b-27">
              <div class="size-208"><span class="stext-110 cl2">Saldo al entregar:</span></div>
              <div class="size-209"><span class="mtext-110 cl2">${{ number_format($saldoCombinado, 2) }}</span></div>
            </div>

            <div class="stext-107" style="background:#FEF9C3;color:#A16207;padding:12px 15px;border-radius:8px;margin-bottom:20px;line-height:1.6;">
              <strong>Política del local:</strong> para que tu pedido entre en producción debes cancelar al menos el <strong>50% del valor total</strong>
              (${{ number_format($adelantoCombinado, 2) }}) y subir la foto del voucher. También puedes cancelar el pago completo si lo prefieres.
            </div>

            <form action="{{ route('cliente.carrito.confirmar') }}" method="POST">
              @csrf
              <button type="submit" class="flex-c-m stext-101 cl0 size-116 bg1 bor14 hov-btn1 p-lr-15 trans-04 pointer" style="width:100%;">
                Confirmar pedido y pagar
              </button>
            </form>

            <a href="{{ session('catalogo_url', route('cliente.catalogo.index')) }}" class="stext-107 cl6 hov-cl1 trans-04" style="display:block;text-align:center;margin-top:16px;">
              ← Seguir comprando
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif

@endsection
