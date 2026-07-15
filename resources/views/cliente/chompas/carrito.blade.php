@extends('layouts.catalogo')

@section('titulo', 'Mi carrito de chompas')

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Mi carrito de chompas</div>
  <a href="{{ route('cliente.chompas.index') }}" class="btn-secondary">← Seguir comprando</a>
</div>
                      
@if(empty($carrito))
  <div class="card card-pad reveal" style="text-align:center;padding:48px;color:var(--text-3);">
    <svg viewBox="0 0 24 24" style="width:40px;height:40px;stroke:var(--border-2);margin:0 auto 12px;display:block;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
    Tu carrito está vacío. Ve al catálogo de chompas y elige la tuya.
  </div>
@else

@if($hayAmbos)
  <div style="background:var(--blue-soft);border:1px solid var(--blue);color:var(--blue);padding:11px 16px;border-radius:9px;font-size:0.82rem;margin-bottom:16px;">
    Tienes uniformes y chompas en tu carrito: se confirmarán juntos como <strong>un solo pedido</strong> con un solo pago.
  </div>
@endif

<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:24px;align-items:start;">

  {{-- ITEMS --}}
  <div class="card reveal" style="overflow:hidden;">
    @foreach($carrito as $key => $item)
      <div style="display:flex;gap:16px;padding:16px 20px;border-bottom:1px solid var(--border);align-items:center;flex-wrap:wrap;">
        <img src="{{ asset('storage/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}"
             style="width:70px;height:70px;object-fit:cover;border-radius:10px;border:1px solid var(--border);">

        <div style="flex:1;min-width:150px;">
          <div style="font-weight:700;color:var(--text-1);font-size:0.93rem;">{{ $item['nombre'] }}</div>
          <div style="font-size:0.78rem;color:var(--text-3);">Tela: {{ $item['tipo_tela'] }}</div>
          <div style="font-size:0.82rem;margin-top:4px;">
            <span style="background:var(--blue-soft);color:var(--blue);padding:2px 9px;border-radius:5px;font-weight:700;font-size:0.75rem;">
              Talla {{ $item['talla'] }}
            </span>
            <span style="color:var(--text-2);font-weight:600;margin-left:8px;">${{ number_format($item['precio'], 2) }} c/u</span>
          </div>
        </div>

        {{-- Cambiar cantidad --}}
        <form action="{{ route('cliente.chompas.actualizar', $key) }}" method="POST" style="display:flex;align-items:center;gap:6px;">
          @csrf
          <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" max="100"
                 style="width:64px;padding:7px 8px;border:1.5px solid var(--border);border-radius:8px;text-align:center;background:var(--bg-2);color:var(--text-1);outline:none;">
          <button type="submit" style="background:var(--bg-3);border:1px solid var(--border);color:var(--text-2);padding:7px 10px;border-radius:8px;font-size:0.75rem;cursor:pointer;font-weight:600;">
            Actualizar
          </button>
        </form>

        <div style="font-weight:800;color:var(--text-1);min-width:80px;text-align:right;">
          ${{ number_format($item['precio'] * $item['cantidad'], 2) }}
        </div>

        <form action="{{ route('cliente.chompas.quitar', $key) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;width:32px;height:32px;border-radius:8px;font-weight:700;cursor:pointer;">✕</button>
        </form>
      </div>
    @endforeach

    @if($hayAmbos)
      @foreach($carritoUniformes as $key => $item)
        <div style="display:flex;gap:16px;padding:16px 20px;border-bottom:1px solid var(--border);align-items:center;flex-wrap:wrap;">
          <img src="{{ asset('storage/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}"
               style="width:70px;height:70px;object-fit:cover;border-radius:10px;border:1px solid var(--border);">

          <div style="flex:1;min-width:150px;">
            <div style="font-weight:700;color:var(--text-1);font-size:0.93rem;">{{ $item['nombre'] }}</div>
            <div style="font-size:0.78rem;color:var(--text-3);">Tela: {{ $item['tipo_tela'] }}</div>
            <div style="font-size:0.82rem;margin-top:4px;">
              <span style="background:var(--blue-soft);color:var(--blue);padding:2px 9px;border-radius:5px;font-weight:700;font-size:0.75rem;">
                Talla {{ $item['talla'] }}
              </span>
              <span style="color:var(--text-2);font-weight:600;margin-left:8px;">${{ number_format($item['precio'], 2) }} c/u</span>
            </div>
          </div>

          <form action="{{ route('cliente.carrito.actualizar', $key) }}" method="POST" style="display:flex;align-items:center;gap:6px;">
            @csrf
            <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" max="100"
                   style="width:64px;padding:7px 8px;border:1.5px solid var(--border);border-radius:8px;text-align:center;background:var(--bg-2);color:var(--text-1);outline:none;">
            <button type="submit" style="background:var(--bg-3);border:1px solid var(--border);color:var(--text-2);padding:7px 10px;border-radius:8px;font-size:0.75rem;cursor:pointer;font-weight:600;">
              Actualizar
            </button>
          </form>

          <div style="font-weight:800;color:var(--text-1);min-width:80px;text-align:right;">
            ${{ number_format($item['precio'] * $item['cantidad'], 2) }}
          </div>

          <form action="{{ route('cliente.carrito.quitar', $key) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;width:32px;height:32px;border-radius:8px;font-weight:700;cursor:pointer;">✕</button>
          </form>
        </div>
      @endforeach
    @endif
  </div>

  {{-- RESUMEN + CONFIRMAR --}}
  <div class="card card-pad reveal">
    <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:16px;">Resumen del pedido</div>

    @php
      $totalMostrado = $hayAmbos ? $totalCombinado : $total;
      $adelantoMostrado = $hayAmbos ? $adelantoCombinado : $adelanto;
      $saldoMostrado = $hayAmbos ? $saldoCombinado : $saldo;
    @endphp

    <div style="font-size:0.9rem;color:var(--text-2);line-height:2.2;">
      <div style="display:flex;justify-content:space-between;">
        <span>Total:</span>
        <strong style="color:var(--text-1);font-size:1.1rem;">${{ number_format($totalMostrado, 2) }}</strong>
      </div>
      <div style="display:flex;justify-content:space-between;">
        <span>Adelanto requerido (50%):</span>
        <strong style="color:var(--blue);">${{ number_format($adelantoMostrado, 2) }}</strong>
      </div>
      <div style="display:flex;justify-content:space-between;">
        <span>Saldo al recibir:</span>
        <strong style="color:var(--text-1);">${{ number_format($saldoMostrado, 2) }}</strong>
      </div>
    </div>

    <hr style="border:none;border-top:1px solid var(--border);margin:16px 0;">

    <div style="font-size:0.78rem;color:var(--text-3);margin-bottom:16px;line-height:1.6;">
      Al confirmar tu pedido se te pedirá subir el comprobante del adelanto del 50% para que podamos iniciar la producción.
    </div>

    <form action="{{ route('cliente.chompas.confirmar') }}" method="POST">
      @csrf
      <button type="submit" class="btn-primary" style="width:100%;padding:14px;font-size:0.98rem;">
        Confirmar pedido
      </button>
    </form>

    <form action="{{ route('cliente.chompas.vaciar') }}" method="POST" style="margin-top:10px;"
          onsubmit="return confirm('¿Seguro que quieres vaciar el carrito de chompas?');">
      @csrf
      <button type="submit" style="width:100%;padding:11px;border:1px solid var(--border);background:transparent;color:var(--text-3);border-radius:10px;cursor:pointer;font-size:0.85rem;">
        Vaciar {{ $hayAmbos ? 'chompas' : 'carrito' }}
      </button>
    </form>
    @if($hayAmbos)
      <form action="{{ route('cliente.carrito.vaciar') }}" method="POST" style="margin-top:10px;"
            onsubmit="return confirm('¿Seguro que quieres vaciar el carrito de uniformes?');">
        @csrf
        <button type="submit" style="width:100%;padding:11px;border:1px solid var(--border);background:transparent;color:var(--text-3);border-radius:10px;cursor:pointer;font-size:0.85rem;">
          Vaciar uniformes
        </button>
      </form>
    @endif
  </div>

</div>

@endif

@endsection
