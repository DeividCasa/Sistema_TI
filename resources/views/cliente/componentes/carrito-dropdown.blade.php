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
@endphp

<div class="carrito-dropdown" id="carrito-dropdown">
    <div class="carrito-dropdown-titulo">Mi carrito</div>

    @if($carritoVacio)
        <div class="carrito-vacio">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
            Tu carrito está vacío.
        </div>
    @else
        <div class="carrito-seccion">

            @if(!empty($carritoPlantillas))
                <div class="carrito-seccion-titulo">Ropa</div>
                @foreach($carritoPlantillas as $key => $item)
                    <div class="carrito-item">
                        @if($item['imagen'])
                            <img src="{{ asset('storage/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}">
                        @endif
                        <div class="carrito-item-info">
                            <div class="carrito-item-nombre">{{ $item['nombre'] }}</div>
                            <div class="carrito-item-detalle">
                                @if($item['talla']) Talla {{ $item['talla'] }} &times; @endif{{ $item['cantidad'] }}
                            </div>
                        </div>
                        <div class="carrito-item-precio">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>
                        <form action="{{ route('cliente.plantillas.quitar', $key) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="carrito-item-quitar" aria-label="Quitar">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            @endif

            @if(!empty($carritoUniformes))
                <div class="carrito-seccion-titulo" style="margin-top:10px;">Uniformes escolares</div>
                @foreach($carritoUniformes as $key => $item)
                    <div class="carrito-item">
                        <img src="{{ asset('storage/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}">
                        <div class="carrito-item-info">
                            <div class="carrito-item-nombre">{{ $item['nombre'] }}</div>
                            <div class="carrito-item-detalle">Talla {{ $item['talla'] }} &times; {{ $item['cantidad'] }}</div>
                        </div>
                        <div class="carrito-item-precio">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>
                        <form action="{{ route('cliente.carrito.quitar', $key) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="carrito-item-quitar" aria-label="Quitar">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            @endif

            @if(!empty($carritoChompas))
                <div class="carrito-seccion-titulo" style="margin-top:10px;">Chompas</div>
                @foreach($carritoChompas as $key => $item)
                    <div class="carrito-item">
                        <img src="{{ asset('storage/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}">
                        <div class="carrito-item-info">
                            <div class="carrito-item-nombre">{{ $item['nombre'] }}</div>
                            <div class="carrito-item-detalle">Talla {{ $item['talla'] }} &times; {{ $item['cantidad'] }}</div>
                        </div>
                        <div class="carrito-item-precio">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>
                        <form action="{{ route('cliente.chompas.quitar', $key) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="carrito-item-quitar" aria-label="Quitar">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            @endif

            <div class="carrito-seccion-footer">
                <div class="carrito-seccion-total">Total: <strong>${{ number_format($totalGeneral, 2) }}</strong></div>
                <a href="{{ route('cliente.carrito.index') }}" class="carrito-btn-pagar">Ver carrito completo</a>
            </div>
        </div>
    @endif
</div>
