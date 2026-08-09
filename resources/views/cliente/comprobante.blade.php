@extends('layouts.catalogo')

@section('titulo', 'Subir comprobante')

@section('contenido')

<br />
<br />

<div class="main-content-compat">
<div class="sec-header reveal">
  <div class="sec-title">Comprobante de pago — Pedido {{ $pedido->codigo }}</div>
  <a href="{{ route('cliente.mis-pedidos') }}" class="btn-secondary">← Mis pedidos</a>
</div>

<div class="grid-2col" style="--cols:1fr 1fr;gap:24px;">

  {{-- RESUMEN DEL PEDIDO --}}
  <div class="card card-pad reveal">
    <div class="sec-title" style="margin-bottom:16px;">Resumen del pedido</div>

    <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border);">
      <div style="width:64px;height:64px;border-radius:10px;background:var(--bg-3);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
        @if(optional($pedido->disenio)->imagen_generada)
          <img src="{{ asset('storage/'.$pedido->disenio->imagen_generada) }}"
               alt="{{ $pedido->disenio->nombre }}"
               style="width:100%;height:100%;object-fit:cover;">
        @elseif(optional($pedido->disenio->plantilla ?? null)->imagen_preview)
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
      @if($info->cuenta_banco || $info->cuenta_numero)
        <div class="t-text" style="font-size:0.85rem;line-height:1.7;">
          @if($info->cuenta_banco)Banco: {{ $info->cuenta_banco }}<br>@endif
          @if($info->cuenta_tipo || $info->cuenta_numero)Cuenta {{ $info->cuenta_tipo ?: '' }}: {{ $info->cuenta_numero }}<br>@endif
          @if($info->cuenta_titular)A nombre de: {{ $info->cuenta_titular }}<br>@endif
          @if($info->cuenta_identificacion)Cédula / RUC: {{ $info->cuenta_identificacion }}@endif
        </div>
      @else
        <div class="t-muted" style="font-size:0.85rem;line-height:1.6;">
          Aún no se han configurado los datos de la cuenta. Ve a Admin → Información del local para agregarlos.
        </div>
      @endif
    </div>

    {{-- ENTREGA --}}
    <div style="margin-top:20px;">
      @if($pedido->tipo_entrega)
        <div style="padding:14px 16px;background:var(--bg-3);border:1px solid var(--border);border-radius:10px;">
          <div style="font-size:0.78rem;font-weight:700;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:4px;">Entrega</div>
          <div class="t-text" style="font-size:0.9rem;font-weight:700;">{{ \App\Support\PedidoEstados::etiquetaEntrega($pedido->tipo_entrega) }}</div>
          @if($pedido->tipo_entrega === 'domicilio' && $pedido->direccion_entrega)
            <div class="t-muted" style="font-size:0.82rem;margin-top:2px;">{{ $pedido->direccion_entrega }}</div>
          @endif
        </div>
      @else
        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:10px;">
          ¿Cómo quieres recibir tu pedido?
        </label>
        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
          <label style="cursor:pointer;">
            <input type="radio" name="tipo_entrega" value="retiro" form="form-comprobante-3d" style="display:none;" onchange="marcarEntrega3d(this)" required>
            <div class="opcion-entrega-3d" style="border:2px solid var(--border);border-radius:10px;padding:14px 16px;">
              <div style="font-weight:700;color:var(--text-1);font-size:0.9rem;">Retiro en tienda</div>
              <div style="font-size:0.82rem;color:var(--text-2);">Pasas a recoger tu pedido cuando esté listo.</div>
            </div>
          </label>
          <label style="cursor:pointer;">
            <input type="radio" name="tipo_entrega" value="domicilio" form="form-comprobante-3d" style="display:none;" onchange="marcarEntrega3d(this)" required>
            <div class="opcion-entrega-3d" style="border:2px solid var(--border);border-radius:10px;padding:14px 16px;">
              <div style="font-weight:700;color:var(--text-1);font-size:0.9rem;">Envío a domicilio</div>
              <div style="font-size:0.82rem;color:var(--text-2);">Te lo enviamos a la dirección que nos indiques.</div>
            </div>
          </label>
        </div>
        <div id="campo-direccion-entrega-3d" style="display:none;">
          <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:7px;">
            Dirección de entrega
          </label>
          <input type="text" name="direccion_entrega" id="input-direccion-entrega-3d" form="form-comprobante-3d" maxlength="255"
            placeholder="Calle, número, referencia, ciudad..."
            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:0.9rem;
            background:var(--bg-2);color:var(--text-1);outline:none;">
          <div id="error-direccion-entrega-3d" style="display:none;color:#EF4444;font-size:0.78rem;margin-top:6px;">Ingresa la dirección donde quieres recibir tu pedido.</div>
        </div>
      @endif
    </div>
  </div>

  {{-- FORMULARIO COMPROBANTE --}}
  <div class="card card-pad reveal">
    @php
      $adelantoVerificado = $pedido->comprobantes->where('tipo', 'adelanto')->where('estado', 'verificado')->count() > 0;
      $pagadoCompleto     = $pedido->estado_pago === 'pagado_completo';
      $pagoEnRevision     = $pedido->comprobantes->where('estado', 'pendiente')->count() > 0;
    @endphp

    @if($pagadoCompleto)
      <div class="sec-title" style="margin-bottom:16px;">Comprobante</div>
      <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:16px;border-radius:10px;font-size:0.9rem;font-weight:600;text-align:center;">
        Este pedido ya está pagado en su totalidad. ¡Gracias!
      </div>
    @elseif($pagoEnRevision)
      <div class="sec-title" style="margin-bottom:16px;">Comprobante</div>
      <div style="background:#DBEAFE;border:1px solid #BFDBFE;color:#1D4ED8;padding:16px;border-radius:10px;font-size:0.9rem;font-weight:600;text-align:center;">
        Verificando pago — tu comprobante fue enviado y está pendiente de revisión por el administrador.
      </div>
    @else
      <div class="sec-title" style="margin-bottom:16px;">Sube tu voucher de pago</div>
      <p style="font-size:0.85rem;color:var(--text-2);line-height:1.6;margin-bottom:20px;">
        Realiza la transferencia o depósito y sube la <strong>foto del voucher</strong>.
        @if(!$adelantoVerificado)
          Puedes pagar el <strong>50% para iniciar</strong> el pedido, o cancelar el <strong>pago completo</strong> de una vez.
        @else
          Tu adelanto ya fue verificado — ahora corresponde pagar el <strong>saldo final</strong>.
        @endif
      </p>

      <form action="{{ route('cliente.pedidos.comprobante.store', $pedido->id) }}" method="POST" enctype="multipart/form-data" id="form-comprobante-3d">
        @csrf

        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:10px;">
          ¿Qué pago vas a realizar?
        </label>
        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">
          @if(!$adelantoVerificado)
            <label style="cursor:pointer;">
              <input type="radio" name="tipo" value="adelanto" checked style="display:none;" onchange="marcarOpcion3d(this)">
              <div class="opcion-pago-3d" style="border:2px solid var(--blue);background:var(--blue-soft);border-radius:10px;padding:14px 16px;">
                <div style="font-weight:700;color:var(--text-1);font-size:0.9rem;">Pagar el 50% (adelanto)</div>
                <div style="font-size:0.82rem;color:var(--text-2);">Monto: <strong style="color:var(--blue);">${{ number_format($pedido->precio_adelanto, 2) }}</strong> — el saldo lo pagas al recibir.</div>
              </div>
            </label>
            <label style="cursor:pointer;">
              <input type="radio" name="tipo" value="pago_completo" style="display:none;" onchange="marcarOpcion3d(this)">
              <div class="opcion-pago-3d" style="border:2px solid var(--border);border-radius:10px;padding:14px 16px;">
                <div style="font-weight:700;color:var(--text-1);font-size:0.9rem;">Cancelar el pago completo</div>
                <div style="font-size:0.82rem;color:var(--text-2);">Monto: <strong style="color:var(--blue);">${{ number_format($pedido->precio_total, 2) }}</strong> — pagas todo de una vez.</div>
              </div>
            </label>
          @else
            <label style="cursor:pointer;">
              <input type="radio" name="tipo" value="saldo_final" checked style="display:none;" onchange="marcarOpcion3d(this)">
              <div class="opcion-pago-3d" style="border:2px solid var(--blue);background:var(--blue-soft);border-radius:10px;padding:14px 16px;">
                <div style="font-weight:700;color:var(--text-1);font-size:0.9rem;">Pagar el saldo final (50% restante)</div>
                <div style="font-size:0.82rem;color:var(--text-2);">Tu adelanto ya fue verificado. Monto restante: <strong style="color:var(--blue);">${{ number_format($pedido->precio_saldo, 2) }}</strong></div>
              </div>
            </label>
          @endif
        </div>

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
            <span class="t-muted" style="font-size:0.78rem;">JPG, PNG, WEBP o PDF — máximo 5MB</span>
            <input type="file" id="archivo" name="archivo" accept=".jpg,.jpeg,.png,.webp,.pdf"
              onchange="previsualizarArchivo(this, 'preview-archivo-camiseta', 'drop-area')" style="display:none;" required>
          </label>
          <div id="preview-archivo-camiseta" style="display:none;margin-top:12px;"></div>
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
</div>

<script>
function marcarOpcion3d(radio) {
  document.querySelectorAll('.opcion-pago-3d').forEach(el => {
    el.style.borderColor = 'var(--border)';
    el.style.background = 'transparent';
  });
  const caja = radio.nextElementSibling;
  caja.style.borderColor = 'var(--blue)';
  caja.style.background = 'var(--blue-soft)';
}

function marcarEntrega3d(radio) {
  document.querySelectorAll('.opcion-entrega-3d').forEach(el => {
    el.style.borderColor = 'var(--border)';
    el.style.background = 'transparent';
  });
  const caja = radio.nextElementSibling;
  caja.style.borderColor = 'var(--blue)';
  caja.style.background = 'var(--blue-soft)';

  const campoDireccion = document.getElementById('campo-direccion-entrega-3d');
  if (campoDireccion) {
    campoDireccion.style.display = radio.value === 'domicilio' ? 'block' : 'none';
    document.getElementById('error-direccion-entrega-3d').style.display = 'none';
  }
}

document.getElementById('form-comprobante-3d')?.addEventListener('submit', function (e) {
  const bloqueTipoEntrega = document.querySelector('input[name="tipo_entrega"]');
  if (!bloqueTipoEntrega) return;

  const elegido = document.querySelector('input[name="tipo_entrega"]:checked');
  if (!elegido) {
    e.preventDefault();
    alert('Selecciona cómo quieres recibir tu pedido (retiro en tienda o envío a domicilio).');
    return;
  }
  if (elegido.value === 'domicilio') {
    const direccion = document.getElementById('input-direccion-entrega-3d');
    if (!direccion.value.trim()) {
      e.preventDefault();
      document.getElementById('error-direccion-entrega-3d').style.display = 'block';
      direccion.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }
});
</script>

@endsection