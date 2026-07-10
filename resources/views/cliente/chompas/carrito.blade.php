@extends('Plantilla/Plantilla')

@section('titulo', 'Mi carrito de chompas')
@section('page-title', 'Mi carrito de chompas')

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    ✓ {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Mi carrito de chompas</div>
  <a href="{{ route('cliente.chompas.index') }}" class="btn-secondary">← Seguir comprando</a>
</div>

@if(empty($carrito))
  <div class="card card-pad reveal" style="text-align:center;padding:48px;color:var(--text-3);">
    <div style="font-size:2.5rem;margin-bottom:10px;">🛒</div>
    Tu carrito está vacío. Ve al catálogo de chompas y elige la tuya.
  </div>
@else

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
  </div>

  {{-- RESUMEN + CONFIRMAR --}}
  <div class="card card-pad reveal">
    <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:16px;">Resumen del pedido</div>

    <div style="font-size:0.9rem;color:var(--text-2);line-height:2.2;">
      <div style="display:flex;justify-content:space-between;">
        <span>Total:</span>
        <strong style="color:var(--text-1);font-size:1.1rem;">${{ number_format($total, 2) }}</strong>
      </div>
      <div style="display:flex;justify-content:space-between;">
        <span>Adelanto requerido (50%):</span>
        <strong style="color:var(--blue);">${{ number_format($adelanto, 2) }}</strong>
      </div>
      <div style="display:flex;justify-content:space-between;">
        <span>Saldo al recibir:</span>
        <strong style="color:var(--text-1);">${{ number_format($saldo, 2) }}</strong>
      </div>
    </div>

    <hr style="border:none;border-top:1px solid var(--border);margin:16px 0;">

    <div style="font-size:0.78rem;color:var(--text-3);margin-bottom:16px;line-height:1.6;">
      Al confirmar tu pedido se te pedirá subir el comprobante del adelanto del 50% para que podamos iniciar la producción.
    </div>

    <form action="{{ route('cliente.chompas.confirmar') }}" method="POST">
      @csrf
      <button type="submit" class="btn-primary" style="width:100%;padding:14px;font-size:0.98rem;">
        ✓ Confirmar pedido
      </button>
    </form>

    <form action="{{ route('cliente.chompas.vaciar') }}" method="POST" style="margin-top:10px;"
          onsubmit="return confirm('¿Seguro que quieres vaciar el carrito?');">
      @csrf
      <button type="submit" style="width:100%;padding:11px;border:1px solid var(--border);background:transparent;color:var(--text-3);border-radius:10px;cursor:pointer;font-size:0.85rem;">
        Vaciar carrito
      </button>
    </form>
  </div>

</div>

@endif

@endsection
