@extends('Plantilla/Plantilla')

@section('titulo', 'Mi carrito')
@section('page-title', 'Mi carrito de uniformes')

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    ✓ {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Mi carrito de uniformes</div>
  <a href="{{ route('cliente.uniformes.index') }}" class="btn-secondary">← Seguir comprando</a>
</div>

@if(empty($carrito))
  <div class="card card-pad reveal" style="text-align:center;padding:48px;color:var(--text-3);">
    <div style="font-size:2.5rem;margin-bottom:10px;">🛒</div>
    Tu carrito está vacío. Ve al catálogo de uniformes escolares y elige el tuyo.
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
          <button type="submit" title="Quitar"
            style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;width:32px;height:32px;border-radius:8px;font-weight:700;cursor:pointer;">✕</button>
        </form>
      </div>
    @endforeach

    <div style="padding:12px 20px;text-align:right;">
      <form action="{{ route('cliente.carrito.vaciar') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" style="background:none;border:none;color:var(--text-3);font-size:0.8rem;cursor:pointer;text-decoration:underline;">
          Vaciar carrito
        </button>
      </form>
    </div>
  </div>

  {{-- RESUMEN --}}
  <div class="card card-pad reveal">
    <div style="font-size:1rem;font-weight:800;color:var(--text-1);margin-bottom:16px;">Resumen del pedido</div>

    <div style="font-size:0.9rem;color:var(--text-2);line-height:2.2;">
      <div style="display:flex;justify-content:space-between;">
        <span>Total del pedido:</span>
        <strong style="color:var(--text-1);font-size:1.1rem;">${{ number_format($total, 2) }}</strong>
      </div>
      <div style="display:flex;justify-content:space-between;border-top:1px dashed var(--border);padding-top:8px;">
        <span>Adelanto mínimo (50%):</span>
        <strong style="color:var(--blue);font-size:1.05rem;">${{ number_format($adelanto, 2) }}</strong>
      </div>
      <div style="display:flex;justify-content:space-between;">
        <span>Saldo al entregar:</span>
        <strong style="color:var(--text-1);">${{ number_format($saldo, 2) }}</strong>
      </div>
    </div>

    <div style="background:#FEF9C3;border:1px solid #FDE68A;color:#A16207;padding:11px 14px;border-radius:9px;font-size:0.78rem;margin:16px 0;line-height:1.5;">
      ⚠ <strong>Política del local:</strong> para que tu pedido entre en producción debes cancelar
      al menos el <strong>50% del valor total</strong> (${{ number_format($adelanto, 2) }}) y subir la foto del voucher.
      También puedes cancelar el pago completo si lo prefieres.
    </div>

    <form action="{{ route('cliente.carrito.confirmar') }}" method="POST">
      @csrf
      <button type="submit" class="btn-primary" style="width:100%;padding:14px;font-size:0.97rem;">
        Confirmar pedido y pagar →
      </button>
    </form>
  </div>
</div>

@endif

@endsection
