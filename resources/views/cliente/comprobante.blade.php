@extends('Plantilla/Plantilla')

@section('titulo', 'Subir comprobante')
@section('page-title', 'Comprobante de pago')

@section('topbar')
    @include('cliente.componentes.topbar-cliente')
@endsection

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    ✓ {{ session('success') }}
  </div>
@endif

@if($errors->any())
  <div style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    @foreach($errors->all() as $error)
      <div>⚠ {{ $error }}</div>
    @endforeach
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Comprobante de pago — Pedido {{ $pedido->codigo }}</div>
  <a href="{{ route('cliente.pedidos.index') }}" class="btn-secondary">← Mis pedidos</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

  {{-- RESUMEN DEL PEDIDO --}}
  <div class="card card-pad reveal">
    <div class="sec-title" style="margin-bottom:16px;">Resumen del pedido</div>

    <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border);">
      <div style="width:64px;height:64px;border-radius:10px;background:var(--bg-3);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
        @if(optional($pedido->disenio->plantilla ?? null)->imagen_preview)
          <img src="{{ asset('storage/'.$pedido->disenio->plantilla->imagen_preview) }}"
               alt="{{ $pedido->disenio->nombre }}"
               style="width:100%;height:100%;object-fit:cover;">
        @else
          <svg viewBox="0 0 24 24" style="width:28px;height:28px;stroke:var(--text-3);fill:none;stroke-width:1.5;">
            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
          </svg>
        @endif
      </div>
      <div>
        <div class="t-text" style="font-weight:700;">{{ $pedido->disenio->nombre ?? 'Diseño personalizado' }}</div>
        <div class="t-muted" style="font-size:0.8rem;">Código: {{ $pedido->codigo }}</div>
      </div>
    </div>

    @if($pedido->tallas->isNotEmpty())
      <div style="margin-bottom:20px;">
        <div style="font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:10px;">
          Tallas y cantidades
        </div>
        <div class="tabla-box">
          <div class="tabla-head" style="grid-template-columns:1fr 1fr;">
            <span>Talla</span><span>Cantidad</span>
          </div>
          @foreach($pedido->tallas as $talla)
            <div class="tabla-row" style="grid-template-columns:1fr 1fr;">
              <span class="t-text">{{ $talla->talla }}</span>
              <span class="t-sub">{{ $talla->cantidad }} uds.</span>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <div style="background:var(--bg-3);border:1px solid var(--border);border-radius:10px;padding:14px 16px;">
      <div style="display:flex;justify-content:space-between;font-size:0.85rem;color:var(--text-2);margin-bottom:6px;">
        <span>Total del pedido</span>
        <span class="t-text" style="font-weight:700;">${{ number_format($pedido->precio_total, 2) }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:0.85rem;color:var(--text-2);margin-bottom:6px;">
        <span>Adelanto a pagar (50%)</span>
        <span style="font-weight:700;color:var(--blue);">${{ number_format($pedido->precio_adelanto, 2) }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:0.85rem;color:var(--text-2);">
        <span>Saldo restante</span>
        <span class="t-text" style="font-weight:700;">${{ number_format($pedido->precio_saldo, 2) }}</span>
      </div>
    </div>

    <div style="margin-top:20px;padding:14px 16px;background:var(--blue-soft);border:1px solid var(--blue-border);border-radius:10px;">
      <div style="font-size:0.78rem;font-weight:700;color:var(--blue);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:6px;">
        Datos para la transferencia
      </div>
      <div class="t-text" style="font-size:0.85rem;line-height:1.7;">
        Banco: Banco Pichincha<br>
        Cuenta de ahorros: 22-XXXXXXX-X<br>
        A nombre de: Leo José<br>
        Cédula / RUC: 0102030405001
      </div>
    </div>
  </div>

  {{-- FORMULARIO COMPROBANTE --}}
  <div class="card card-pad reveal">
    <div class="sec-title" style="margin-bottom:16px;">Subir comprobante</div>
    <p style="font-size:0.85rem;color:var(--text-2);line-height:1.6;margin-bottom:20px;">
      Realiza el pago del adelanto (${{ number_format($pedido->precio_adelanto, 2) }}) y sube
      una foto o PDF del comprobante. Nuestro equipo lo verificará y empezaremos a trabajar en tu pedido.
    </p>

    <form action="{{ route('cliente.pedidos.comprobante.store', $pedido->id) }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div style="margin-bottom:20px;">
        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);
          text-transform:uppercase;letter-spacing:0.03em;margin-bottom:10px;">
          Archivo del comprobante
        </label>

        <label for="archivo" id="drop-area" style="display:flex;flex-direction:column;align-items:center;justify-content:center;
          gap:10px;padding:32px 16px;border:1.5px dashed var(--border-2);border-radius:12px;
          background:var(--bg-3);cursor:pointer;transition:all var(--tr);text-align:center;">
          <svg viewBox="0 0 24 24" style="width:32px;height:32px;stroke:var(--blue);fill:none;stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round;">
            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
          </svg>
          <span class="t-text" style="font-weight:600;font-size:0.9rem;" id="archivo-label">Haz clic para seleccionar tu archivo</span>
          <span class="t-muted" style="font-size:0.78rem;">JPG, PNG o PDF — máximo 4MB</span>
          <input type="file" id="archivo" name="archivo" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" onchange="mostrarArchivo(this)" required>
        </label>
      </div>

      <div style="margin-bottom:24px;">
        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);
          text-transform:uppercase;letter-spacing:0.03em;margin-bottom:10px;">
          Número de referencia (opcional)
        </label>
        <input type="text" name="referencia" maxlength="100" placeholder="Ej: 000123456789"
          style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
          font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
      </div>

      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
        <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;"><path d="M5 13l4 4L19 7"/></svg>
        Enviar comprobante
      </button>
    </form>
  </div>

</div>

@endsection

@push('scripts')
<script>
  function mostrarArchivo(input) {
    const label = document.getElementById('archivo-label');
    if (input.files && input.files.length > 0) {
      label.textContent = input.files[0].name;
      document.getElementById('drop-area').style.borderColor = 'var(--blue)';
      document.getElementById('drop-area').style.background = 'var(--blue-soft)';
    } else {
      label.textContent = 'Haz clic para seleccionar tu archivo';
    }
  }
</script>
@endpush