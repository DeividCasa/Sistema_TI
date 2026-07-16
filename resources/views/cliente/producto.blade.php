@extends('layouts.catalogo')

@section('titulo', $plantilla->nombre)

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    {{ session('success') }}
  </div>
@endif

<style>
.product-image {
        background: var(--bg-3);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 4px;
    }

    .product-image img {
        width: 100%;
        height: auto;
        object-fit: cover;
        display: block;
    }
</style>
<div class="sec-header reveal">
  <div class="sec-title">{{ $plantilla->nombre }}</div>
  <a href="{{ session('catalogo_url', route('cliente.catalogo.index')) }}" class="btn-secondary">← Volver al catálogo</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

  {{-- IMAGEN SIN BORDE --}}
        <div class="product-image">
            @if($plantilla->imagen_preview)
                <img src="{{ asset('storage/'.$plantilla->imagen_preview) }}" alt="{{ $plantilla->nombre }}">
            @else
                <div style="padding: 3rem; text-align: center; color: var(--text-3);">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <p>Sin imagen</p>
                </div>
            @endif
        </div>

  {{-- INFO Y COMPRA --}}
  <div class="card card-pad reveal">
    <div style="font-size:0.78rem;font-weight:600;color:var(--blue);text-transform:capitalize;
      background:var(--blue-soft);display:inline-block;padding:4px 12px;border-radius:20px;margin-bottom:12px;">
      {{ $plantilla->tipo_prenda }}
    </div>
    <div style="font-size:0.78rem;font-weight:600;color:var(--blue);
      background:var(--blue-soft);display:inline-block;padding:4px 12px;border-radius:20px;margin-bottom:12px;margin-left:6px;">
      {{ ['hombre' => 'Para Hombre', 'mujer' => 'Para Mujer'][$plantilla->genero] ?? 'Unisex' }}
    </div>

    <h2 style="font-family:var(--font-d);font-size:1.6rem;font-weight:800;color:var(--text-1);margin-bottom:8px;">
      {{ $plantilla->nombre }}
    </h2>

    <div style="font-family:var(--font-d);font-size:1.8rem;font-weight:800;color:var(--blue);margin-bottom:16px;">
      ${{ number_format($plantilla->precio, 2) }}
      <span style="font-size:0.8rem;font-weight:500;color:var(--text-3);">/ unidad</span>
    </div>

    @if($plantilla->descripcion)
      <p style="font-size:0.9rem;color:var(--text-2);line-height:1.7;margin-bottom:24px;">
        {{ $plantilla->descripcion }}
      </p>
    @endif

    <form action="{{ route('cliente.plantillas.agregar') }}" method="POST">
      @csrf
      <input type="hidden" name="plantilla_id" value="{{ $plantilla->id }}">

      {{-- Colores --}}
      @if(!empty($plantilla->colores))
        <div style="margin-bottom:20px;">
          <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);
            text-transform:uppercase;letter-spacing:0.03em;margin-bottom:10px;">
            Color
          </label>
          <div style="display:flex;gap:10px;flex-wrap:wrap;">
            @foreach($plantilla->colores as $i => $color)
              <label style="cursor:pointer;">
                <input type="radio" name="color" value="{{ $color }}" {{ $i == 0 ? 'checked' : '' }} style="display:none;" onchange="marcarColor(this)">
                <span class="color-opcion" style="width:32px;height:32px;border-radius:50%;background:{{ $color }};
                  border:3px solid {{ $i == 0 ? 'var(--blue)' : 'var(--border)' }};display:inline-block;
                  transition:border-color var(--tr);"></span>
              </label>
            @endforeach
          </div>
        </div>
      @endif

      {{-- Tallas --}}
      @if(!empty($plantilla->tallas))
        <div style="margin-bottom:20px;">
          <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);
            text-transform:uppercase;letter-spacing:0.03em;margin-bottom:10px;">
            Talla
          </label>
          <div style="display:flex;gap:10px;flex-wrap:wrap;">
            @foreach($plantilla->tallas as $i => $talla)
              <label style="cursor:pointer;">
                <input type="radio" name="talla" value="{{ $talla }}" {{ $i == 0 ? 'checked' : '' }} style="display:none;" onchange="marcarTalla(this)">
                <span class="talla-opcion" style="padding:10px 18px;border:1.5px solid {{ $i == 0 ? 'var(--blue)' : 'var(--border)' }};
                  border-radius:10px;background:{{ $i == 0 ? 'var(--blue-soft)' : 'var(--bg-2)' }};
                  color:{{ $i == 0 ? 'var(--blue)' : 'var(--text-1)' }};font-weight:600;font-size:0.85rem;
                  display:inline-block;transition:all var(--tr);">{{ $talla }}</span>
              </label>
            @endforeach
          </div>
        </div>
      @else
        <input type="hidden" name="talla" value="M">
      @endif

      {{-- Cantidad --}}
      <div style="margin-bottom:24px;">
        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);
          text-transform:uppercase;letter-spacing:0.03em;margin-bottom:10px;">
          Cantidad
        </label>
        <div style="display:flex;align-items:center;gap:12px;">
          <button type="button" onclick="cambiarCantidad(-1)"
            style="width:36px;height:36px;border-radius:8px;border:1.5px solid var(--border);
            background:var(--bg-2);color:var(--text-1);font-size:1.1rem;cursor:pointer;">−</button>
          <input type="number" name="cantidad" id="cantidad" value="1" min="1" readonly
            style="width:60px;text-align:center;padding:8px;border:1.5px solid var(--border);
            border-radius:8px;font-family:var(--font-b);font-size:0.95rem;color:var(--text-1);background:var(--bg-2);">
          <button type="button" onclick="cambiarCantidad(1)"
            style="width:36px;height:36px;border-radius:8px;border:1.5px solid var(--border);
            background:var(--bg-2);color:var(--text-1);font-size:1.1rem;cursor:pointer;">+</button>
        </div>
      </div>

      {{-- Subtotal estimado --}}
      <div style="background:var(--bg-3);border:1px solid var(--border);border-radius:10px;
        padding:14px 16px;margin-bottom:24px;">
        <div style="display:flex;justify-content:space-between;font-size:0.85rem;color:var(--text-2);">
          <span>Subtotal</span>
          <span id="total-precio" style="font-weight:700;color:var(--text-1);">${{ number_format($plantilla->precio, 2) }}</span>
        </div>
      </div>

      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
        <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
        Agregar al carrito
      </button>
    </form>

  </div>
</div>

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
    document.querySelectorAll('.color-opcion').forEach(s => s.style.borderColor = 'var(--border)');
    radio.nextElementSibling.style.borderColor = 'var(--blue)';
  }

  function marcarTalla(radio) {
    document.querySelectorAll('.talla-opcion').forEach(s => {
      s.style.borderColor = 'var(--border)';
      s.style.background = 'var(--bg-2)';
      s.style.color = 'var(--text-1)';
    });
    const span = radio.nextElementSibling;
    span.style.borderColor = 'var(--blue)';
    span.style.background = 'var(--blue-soft)';
    span.style.color = 'var(--blue)';
  }
</script>
@endpush