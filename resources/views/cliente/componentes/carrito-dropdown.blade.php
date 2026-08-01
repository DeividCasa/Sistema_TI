@php
    $carritoPlantillas = session('carrito_plantillas', []);
    $carritoUniformes = session('carrito_uniformes', []);
    $carritoChompas = session('carrito_chompas', []);

    $totalPlantillas = 0;
    foreach ($carritoPlantillas as $item) { $totalPlantillas += $item['precio'] * $item['cantidad']; }

    $totalUniformes = 0;
    foreach ($carritoUniformes as $item) { $totalUniformes += $item['precio'] * $item['cantidad']; }

    $totalChompas = 0;
    foreach ($carritoChompas as $item) { $totalChompas += $item['precio'] * $item['cantidad']; }

    $carritoVacio = empty($carritoPlantillas) && empty($carritoUniformes) && empty($carritoChompas);
    $totalGeneral = $totalPlantillas + $totalUniformes + $totalChompas;

    $filas = [];
    foreach ($carritoPlantillas as $key => $item) {
        $filas[] = ['key' => $key, 'item' => $item, 'ruta' => route('cliente.plantillas.quitar', $key)];
    }
    foreach ($carritoUniformes as $key => $item) {
        $filas[] = ['key' => $key, 'item' => $item, 'ruta' => route('cliente.carrito.quitar', $key)];
    }
    foreach ($carritoChompas as $key => $item) {
        $filas[] = ['key' => $key, 'item' => $item, 'ruta' => route('cliente.chompas.quitar', $key)];
    }
@endphp

@if($carritoVacio)
    <div class="w-full p-t-20 p-b-40 txt-center">
        <i class="zmdi zmdi-shopping-cart" style="font-size:40px;color:#ccc;"></i>
        <p class="stext-107 cl6 p-t-15">Tu carrito está vacío.</p>
    </div>
@else
    <ul class="header-cart-wrapitem w-full">
        @foreach($filas as $fila)
            <li class="header-cart-item flex-w flex-t m-b-12">
                <div class="header-cart-item-img">
                    @if(!empty($fila['item']['imagen']))
                        <img src="{{ asset('storage/' . $fila['item']['imagen']) }}" alt="{{ $fila['item']['nombre'] }}">
                    @endif
                </div>

                <div class="header-cart-item-txt p-t-8">
                    <span class="header-cart-item-name m-b-18">
                        {{ $fila['item']['nombre'] }}
                    </span>

                    <span class="header-cart-item-info">
                        @if(!empty($fila['item']['talla'])) Talla {{ $fila['item']['talla'] }} &middot; @endif
                        {{ $fila['item']['cantidad'] }} x ${{ number_format($fila['item']['precio'], 2) }}
                    </span>

                    <form action="{{ $fila['ruta'] }}" method="POST" class="p-t-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="stext-107" style="background:none;border:none;color:#F1592A;cursor:pointer;padding:0;">
                            Quitar
                        </button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="w-full">
        <div class="header-cart-total w-full p-tb-40">
            Total: ${{ number_format($totalGeneral, 2) }}
        </div>

        <div class="header-cart-buttons flex-w w-full">
            <a href="{{ route('cliente.carrito.index') }}" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                Ver carrito
            </a>

            <a href="{{ route('cliente.carrito.index') }}" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                Pagar
            </a>
        </div>
    </div>
@endif
