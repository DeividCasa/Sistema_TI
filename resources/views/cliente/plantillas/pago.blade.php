@extends('layouts.catalogo')

@section('titulo', 'Pago del pedido')

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    {{ session('success') }}
  </div>
@endif

@if($errors->any())
  <div style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    @foreach($errors->all() as $error)
      <div>{{ $error }}</div>
    @endforeach
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Pago del pedido {{ $pedido->codigo }}</div>
  <a href="{{ route('cliente.plantillas.mis-pedidos') }}" class="btn-secondary">← Mis pedidos</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1.2fr;gap:24px;align-items:start;">

  {{-- RESUMEN --}}
  <div class="card card-pad reveal">
    <div style="font-size:1rem;font-weight:800;color:var(--text-1);margin-bottom:14px;">Resumen</div>

    @foreach($pedido->items as $item)
      <div style="display:flex;justify-content:space-between;font-size:0.85rem;color:var(--text-2);padding:6px 0;border-bottom:1px dashed var(--border);">
        <span>{{ $item->plantilla->nombre }}
          @if($item->talla) — Talla {{ $item->talla }} @endif
          × {{ $item->cantidad }}</span>
        <strong style="color:var(--text-1);">${{ number_format($item->subtotal, 2) }}</strong>
      </div>
    @endforeach

    <div style="font-size:0.92rem;color:var(--text-2);line-height:2.2;margin-top:12px;">
      <div style="display:flex;justify-content:space-between;">
        <span>Total:</span>
        <strong style="color:var(--text-1);font-size:1.1rem;">${{ number_format($pedido->precio_total, 2) }}</strong>
      </div>
      <div style="display:flex;justify-content:space-between;">
        <span>Adelanto (50%):</span>
        <strong style="color:var(--blue);">${{ number_format($pedido->precio_adelanto, 2) }}</strong>
      </div>
      <div style="display:flex;justify-content:space-between;">
        <span>Saldo restante:</span>
        <strong style="color:var(--text-1);">${{ number_format($pedido->precio_saldo, 2) }}</strong>
      </div>
    </div>
  </div>

  {{-- FORMULARIO DE PAGO --}}
  <div class="card card-pad reveal">
    @php
      $adelantoVerificado = $pedido->comprobantes->where('tipo','adelanto')->where('estado','verificado')->count() > 0;
      $pagadoCompleto     = $pedido->estado_pago === 'pagado_completo';
      $pagoEnRevision     = in_array($pedido->estado_pago, ['adelanto_enviado','pago_completo_enviado','saldo_enviado']);
    @endphp

    @if($pagadoCompleto)
      <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:16px;border-radius:10px;font-size:0.9rem;font-weight:600;text-align:center;">
        Este pedido ya está pagado en su totalidad. ¡Gracias!
      </div>
    @elseif($pagoEnRevision)
      <div style="background:#DBEAFE;border:1px solid #BFDBFE;color:#1D4ED8;padding:16px;border-radius:10px;font-size:0.9rem;font-weight:600;text-align:center;">
         Tu comprobante fue enviado y está pendiente de verificación por el administrador.
      </div>
    @else

      <div style="font-size:1rem;font-weight:800;color:var(--text-1);margin-bottom:6px;">Sube tu voucher de pago</div>
      <div style="font-size:0.82rem;color:var(--text-2);margin-bottom:16px;line-height:1.6;">
        Realiza la transferencia o depósito y sube la <strong>foto del voucher</strong>.
        Puedes pagar el <strong>50% para iniciar</strong> el pedido, o cancelar el <strong>pago completo</strong> de una vez.
      </div>

      <form action="{{ route('cliente.plantillas.comprobante', $pedido->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:10px;">
          ¿Qué pago vas a realizar?
        </label>
        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">

          @if(!$adelantoVerificado)
            <label style="cursor:pointer;">
              <input type="radio" name="tipo" value="adelanto" checked style="display:none;" onchange="marcarOpcion(this)">
              <div class="opcion-pago" style="border:2px solid var(--blue);background:var(--blue-soft);border-radius:10px;padding:14px 16px;">
                <div style="font-weight:700;color:var(--text-1);font-size:0.9rem;">Pagar el 50% (adelanto)</div>
                <div style="font-size:0.82rem;color:var(--text-2);">Monto a cancelar: <strong style="color:var(--blue);">${{ number_format($pedido->precio_adelanto, 2) }}</strong> — el saldo lo pagas al recibir.</div>
              </div>
            </label>

            <label style="cursor:pointer;">
              <input type="radio" name="tipo" value="pago_completo" style="display:none;" onchange="marcarOpcion(this)">
              <div class="opcion-pago" style="border:2px solid var(--border);border-radius:10px;padding:14px 16px;">
                <div style="font-weight:700;color:var(--text-1);font-size:0.9rem;">Cancelar el pago completo</div>
                <div style="font-size:0.82rem;color:var(--text-2);">Monto a cancelar: <strong style="color:var(--blue);">${{ number_format($pedido->precio_total, 2) }}</strong> — pagas todo de una vez.</div>
              </div>
            </label>
          @else
            <label style="cursor:pointer;">
              <input type="radio" name="tipo" value="saldo_final" checked style="display:none;" onchange="marcarOpcion(this)">
              <div class="opcion-pago" style="border:2px solid var(--blue);background:var(--blue-soft);border-radius:10px;padding:14px 16px;">
                <div style="font-weight:700;color:var(--text-1);font-size:0.9rem;">Pagar el saldo final (50% restante)</div>
                <div style="font-size:0.82rem;color:var(--text-2);">Tu adelanto ya fue verificado. Monto restante: <strong style="color:var(--blue);">${{ number_format($pedido->precio_saldo, 2) }}</strong></div>
              </div>
            </label>
          @endif
        </div>

        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:10px;">
          Foto del voucher (imagen o PDF)
        </label>
        <label for="archivo-plantilla" id="drop-area-plantilla" style="display:flex;flex-direction:column;align-items:center;justify-content:center;
          gap:10px;padding:28px 16px;border:1.5px dashed var(--border-2);border-radius:12px;
          background:var(--bg-3);cursor:pointer;transition:all var(--tr);text-align:center;">
          <svg viewBox="0 0 24 24" style="width:30px;height:30px;stroke:var(--blue);fill:none;stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round;">
            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
          </svg>
          <span style="font-weight:600;font-size:0.9rem;color:var(--text-1);">Haz clic para seleccionar tu archivo</span>
          <span style="font-size:0.78rem;color:var(--text-3);">JPG, PNG o PDF — máximo 5MB</span>
          <input type="file" id="archivo-plantilla" name="archivo" accept="image/*,.pdf"
            onchange="previsualizarArchivo(this, 'preview-archivo-plantilla', 'drop-area-plantilla')" style="display:none;" required>
        </label>
        <div id="preview-archivo-plantilla" style="display:none;margin-top:12px;margin-bottom:16px;"></div>

        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:7px;">
          Número de referencia / transacción (opcional)
        </label>
        <input type="text" name="referencia" value="{{ old('referencia') }}" placeholder="Ej: 001234567"
          style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:0.9rem;
          background:var(--bg-2);color:var(--text-1);outline:none;margin-bottom:20px;">

        <button type="submit" class="btn-primary" style="width:100%;padding:14px;font-size:0.97rem;">
          Enviar comprobante
        </button>
      </form>
    @endif

    {{-- HISTORIAL DE COMPROBANTES --}}
    @if($pedido->comprobantes->count() > 0)
      <hr style="border:none;border-top:1px solid var(--border);margin:20px 0;">
      <div style="font-size:0.85rem;font-weight:700;color:var(--text-1);margin-bottom:10px;">Comprobantes enviados</div>
      @foreach($pedido->comprobantes as $c)
        <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.8rem;color:var(--text-2);padding:7px 0;border-bottom:1px dashed var(--border);">
          <span>
            @if($c->tipo === 'adelanto') Adelanto 50%
            @elseif($c->tipo === 'pago_completo') Pago completo
            @else Saldo final @endif
            — ${{ number_format($c->monto, 2) }}
          </span>
          @if($c->estado === 'verificado')
            <span class="badge badge-success">Verificado</span>
          @elseif($c->estado === 'rechazado')
            <span class="badge badge-danger" title="{{ $c->nota_admin }}">Rechazado</span>
          @else
            <span class="badge badge-warning">Pendiente</span>
          @endif
        </div>
      @endforeach
    @endif
  </div>
</div>

<script>
function marcarOpcion(radio) {
  document.querySelectorAll('.opcion-pago').forEach(el => {
    el.style.borderColor = 'var(--border)';
    el.style.background = 'transparent';
  });
  const caja = radio.nextElementSibling;
  caja.style.borderColor = 'var(--blue)';
  caja.style.background = 'var(--blue-soft)';
}
</script>

@endsection
