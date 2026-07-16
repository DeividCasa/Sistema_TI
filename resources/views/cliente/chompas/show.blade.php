@extends('layouts.catalogo')

@section('titulo', $chompa->nombre)

@section('contenido')

@if($errors->any())
  <div style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    @foreach($errors->all() as $error)
      <div>{{ $error }}</div>
    @endforeach
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">{{ $chompa->nombre }}</div>
  <a href="{{ session('catalogo_url', route('cliente.catalogo.index')) }}" class="btn-secondary">← Volver al catálogo</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;align-items:start;">

  {{-- FOTO --}}
  <div class="card reveal" style="overflow:hidden;">
    <img src="{{ asset('storage/' . $chompa->imagen) }}" alt="{{ $chompa->nombre }}"
         style="width:100%;height:auto;display:block;object-fit:cover;">
  </div>

  {{-- INFO + FORMULARIO --}}
  <div class="card card-pad reveal">
    <div style="font-size:1.2rem;font-weight:800;color:var(--text-1);margin-bottom:6px;">{{ $chompa->nombre }}</div>
    <span style="font-size:0.78rem;font-weight:600;color:var(--blue);background:var(--blue-soft);display:inline-block;padding:4px 12px;border-radius:20px;margin-bottom:10px;">
      {{ ['hombre' => 'Para Hombre', 'mujer' => 'Para Mujer'][$chompa->genero] ?? 'Unisex' }}
    </span>
    <div style="font-size:0.85rem;color:var(--text-2);margin-bottom:4px;">
      <strong>Tipo de tela:</strong> {{ $chompa->tipo_tela }}
    </div>

    @if($chompa->descripcion)
      <div style="font-size:0.87rem;color:var(--text-2);line-height:1.6;margin-bottom:16px;">
        {{ $chompa->descripcion }}
      </div>
    @endif

    <hr style="border:none;border-top:1px solid var(--border);margin:14px 0;">

    <form action="{{ route('cliente.chompas.agregar') }}" method="POST">
      @csrf
      <input type="hidden" name="chompa_id" value="{{ $chompa->id }}">

      {{-- Tallas con su precio --}}
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:10px;">
        Elige tu talla (el precio depende de la talla)
      </label>

      <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:20px;">
        @foreach($chompa->tallas as $talla)
          <label style="cursor:pointer;">
            <input type="radio" name="talla_id" value="{{ $talla->id }}" style="display:none;"
                   onchange="seleccionarTalla(this, {{ $talla->precio }})" {{ old('talla_id') == $talla->id ? 'checked' : '' }}>
            <div class="opcion-talla" data-talla-id="{{ $talla->id }}"
                 style="border:2px solid var(--border);border-radius:10px;padding:10px 16px;text-align:center;min-width:82px;transition:all 0.15s;">
              <div style="font-weight:800;font-size:1.05rem;color:var(--text-1);">{{ $talla->talla }}</div>
              <div style="font-size:0.78rem;font-weight:700;color:var(--blue);">${{ number_format($talla->precio, 2) }}</div>
            </div>
          </label>
        @endforeach
      </div>

      {{-- Cantidad --}}
      <div style="margin-bottom:20px;">
        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:10px;">
          Cantidad
        </label>
        <div style="display:flex;align-items:center;gap:12px;">
          <button type="button" onclick="cambiarCantidad(-1)"
            style="width:36px;height:36px;border-radius:8px;border:1.5px solid var(--border);
            background:var(--bg-2);color:var(--text-1);font-size:1.1rem;cursor:pointer;">−</button>
          <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="100" readonly
            style="width:60px;text-align:center;padding:8px;border:1.5px solid var(--border);
            border-radius:8px;font-family:var(--font-b);font-size:0.95rem;color:var(--text-1);background:var(--bg-2);">
          <button type="button" onclick="cambiarCantidad(1)"
            style="width:36px;height:36px;border-radius:8px;border:1.5px solid var(--border);
            background:var(--bg-2);color:var(--text-1);font-size:1.1rem;cursor:pointer;">+</button>
        </div>
      </div>

      {{-- Subtotal en vivo --}}
      <div id="panel-subtotal" style="display:none;background:var(--blue-soft);border:1px solid var(--blue-border);border-radius:10px;padding:14px 18px;margin-bottom:20px;">
        <div style="display:flex;justify-content:space-between;font-size:0.9rem;color:var(--text-2);">
          <span>Subtotal:</span>
          <strong id="txt-subtotal" style="color:var(--blue);font-size:1.05rem;">$0.00</strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:0.8rem;color:var(--text-3);margin-top:4px;">
          <span>Adelanto del 50% (para iniciar el pedido):</span>
          <span id="txt-adelanto" style="font-weight:700;">$0.00</span>
        </div>
      </div>

      <button type="submit" class="btn-primary" style="width:100%;padding:14px;font-size:0.98rem;">
        Agregar al carrito
      </button>
    </form>
  </div>
</div>

<script>
let precioSeleccionado = 0;

function seleccionarTalla(radio, precio) {
  precioSeleccionado = precio;
  document.querySelectorAll('.opcion-talla').forEach(el => {
    el.style.borderColor = 'var(--border)';
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

@endsection
