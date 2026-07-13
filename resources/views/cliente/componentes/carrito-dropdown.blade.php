@php
    $carritoUniformes = session('carrito_uniformes', []);
    $carritoChompas = session('carrito_chompas', []);

    $totalUniformes = 0;
    foreach ($carritoUniformes as $item) { $totalUniformes += $item['precio'] * $item['cantidad']; }

    $totalChompas = 0;
    foreach ($carritoChompas as $item) { $totalChompas += $item['precio'] * $item['cantidad']; }

    $carritoVacio = empty($carritoUniformes) && empty($carritoChompas);
@endphp

<div class="carrito-dropdown" id="carrito-dropdown">
    <div class="carrito-dropdown-titulo">Mi carrito</div>

    @if($carritoVacio)
        <div class="carrito-vacio">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
            Tu carrito está vacío.
        </div>
    @endif

    @if(!empty($carritoUniformes))
        <div class="carrito-seccion">
            <div class="carrito-seccion-titulo">Uniformes escolares</div>
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
            <div class="carrito-seccion-footer">
                <div class="carrito-seccion-total">Total: <strong>${{ number_format($totalUniformes, 2) }}</strong></div>
                <a href="{{ route('cliente.carrito.index') }}" class="carrito-btn-pagar">Ver carrito completo</a>
            </div>
        </div>
    @endif

    @if(!empty($carritoChompas))
        <div class="carrito-seccion">
            <div class="carrito-seccion-titulo">Chompas</div>
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
            <div class="carrito-seccion-footer">
                <div class="carrito-seccion-total">Total: <strong>${{ number_format($totalChompas, 2) }}</strong></div>
                <a href="{{ route('cliente.chompas.carrito') }}" class="carrito-btn-pagar">Ver carrito completo</a>
            </div>
        </div>
    @endif
</div>
