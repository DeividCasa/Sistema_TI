@extends('Admin.panel_admin')

@section('titulo', 'Detalle Pedido ' . $pedido->codigo)
@section('page-title', 'Pedido ' . $pedido->codigo)
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

<div class="sec-header reveal">
  <div class="sec-title">Pedido {{ $pedido->codigo }}</div>
  <a href="{{ route('admin.pedidos-tienda.index') }}" class="btn-secondary">← Volver</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

  <div style="display:flex;flex-direction:column;gap:24px;">
    {{-- DATOS DEL CLIENTE --}}
    <div class="card card-pad reveal">
      <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:14px;">Datos del cliente</div>
      <div style="font-size:0.88rem;color:var(--text-2);line-height:2;">
        <div><strong style="color:var(--text-1);">Nombre:</strong> {{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</div>
        <div><strong style="color:var(--text-1);">Email:</strong> {{ $pedido->cliente->email }}</div>
        <div><strong style="color:var(--text-1);">Teléfono:</strong> {{ $pedido->cliente->telefono ?? 'No registrado' }}</div>
        <div><strong style="color:var(--text-1);">Ciudad:</strong> {{ $pedido->cliente->ciudad ?? 'No registrada' }}</div>
        <div><strong style="color:var(--text-1);">Dirección:</strong> {{ $pedido->cliente->direccion ?? 'No registrada' }}</div>
        <div><strong style="color:var(--text-1);">Fecha del pedido:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</div>
      </div>
    </div>

    {{-- ENTREGA --}}
    <div class="card card-pad reveal">
      <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:14px;">Entrega</div>
      @if($pedido->tipo_entrega)
        <div style="font-size:0.88rem;color:var(--text-2);line-height:2;">
          <div><strong style="color:var(--text-1);">Tipo:</strong> {{ \App\Support\PedidoEstados::etiquetaEntrega($pedido->tipo_entrega) }}</div>
          @if($pedido->tipo_entrega === 'domicilio')
            <div><strong style="color:var(--text-1);">Dirección:</strong> {{ $pedido->direccion_entrega ?? 'No especificada' }}</div>
          @endif
        </div>
      @else
        <div class="t-muted" style="font-size:0.85rem;">El cliente aún no eligió el tipo de entrega (lo hace al subir su comprobante de pago).</div>
      @endif
    </div>

    {{-- COMPROBANTES DE PAGO --}}
    <div class="card reveal" style="overflow:hidden;">
      <div style="padding:16px 20px;font-size:1rem;font-weight:700;color:var(--text-1);border-bottom:1px solid var(--border);">
        Comprobantes de pago (vouchers)
      </div>

      @forelse($pedido->comprobantes as $comprobante)
        <div style="display:flex;gap:20px;padding:18px 20px;border-top:1px solid var(--border);align-items:flex-start;flex-wrap:wrap;">
          @if(Str::endsWith(strtolower($comprobante->archivo), '.pdf'))
            <a href="{{ asset('storage/' . $comprobante->archivo) }}" target="_blank">
              <div style="width:110px;height:110px;border-radius:10px;background:var(--bg-3);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;">
                <svg viewBox="0 0 24 24" style="width:40px;height:40px;stroke:#EF4444;fill:none;stroke-width:1.5;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              </div>
            </a>
          @else
            <img src="{{ asset('storage/' . $comprobante->archivo) }}" alt="Voucher" onclick="abrirLightbox(this.src)"
                 style="width:110px;height:110px;object-fit:cover;border-radius:10px;border:1px solid var(--border);cursor:zoom-in;">
          @endif

          <div style="flex:1;min-width:180px;font-size:0.87rem;color:var(--text-2);line-height:1.9;">
            <div>
              <strong style="color:var(--text-1);">Tipo:</strong>
              @if($comprobante->tipo === 'adelanto') Adelanto del 50%
              @elseif($comprobante->tipo === 'pago_completo') Pago completo (100%)
              @else Saldo final (50% restante) @endif
            </div>
            <div><strong style="color:var(--text-1);">Monto:</strong> ${{ number_format($comprobante->monto, 2) }}</div>
            <div><strong style="color:var(--text-1);">Referencia:</strong> {{ $comprobante->referencia ?? 'Sin referencia' }}</div>
            <div><strong style="color:var(--text-1);">Fecha:</strong> {{ $comprobante->created_at->format('d/m/Y H:i') }}</div>
            <div>
              <strong style="color:var(--text-1);">Estado:</strong>
              @if($comprobante->estado === 'verificado')
                <span style="background:#DCFCE7;color:#15803D;padding:3px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;">Verificado</span>
              @elseif($comprobante->estado === 'rechazado')
                <span style="background:#FEF2F2;color:#B91C1C;padding:3px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;">Rechazado</span>
                @if($comprobante->nota_admin)<span style="font-size:0.78rem;color:var(--text-3);"> — {{ $comprobante->nota_admin }}</span>@endif
              @else
                <span style="background:#FEF9C3;color:#A16207;padding:3px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;">Pendiente</span>
              @endif
            </div>
          </div>

          @if($comprobante->estado === 'pendiente')
            <div style="display:flex;flex-direction:column;gap:8px;">
              <form action="{{ route('admin.comprobantes-uniformes.verificar', $comprobante->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-verificar">Verificar</button>
              </form>
              <form action="{{ route('admin.comprobantes-uniformes.rechazar', $comprobante->id) }}" method="POST"
                    data-confirm="¿Rechazar este comprobante?">
                @csrf
                <input type="hidden" name="nota_admin" value="Comprobante no válido.">
                <button type="submit"
                  style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;padding:9px 20px;border-radius:8px;font-weight:600;font-size:0.85rem;cursor:pointer;width:100%;">
                  Rechazar
                </button>
              </form>
            </div>
          @endif
        </div>
      @empty
        <div style="padding:28px;text-align:center;color:var(--text-3);font-size:0.88rem;">
          El cliente aún no ha subido ningún comprobante de pago.
        </div>
      @endforelse
    </div>
  </div>

  {{-- RESUMEN DE PAGO + CAMBIO DE ESTADO --}}
  <div class="card card-pad reveal">
    <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:14px;">Resumen de pago</div>
    <div style="font-size:0.9rem;color:var(--text-2);line-height:2.1;">
      <div style="display:flex;justify-content:space-between;"><span>Precio total:</span> <strong style="color:var(--text-1);">${{ number_format($pedido->precio_total, 2) }}</strong></div>
      <div style="display:flex;justify-content:space-between;"><span>Adelanto (50%):</span> <strong style="color:var(--blue);">${{ number_format($pedido->precio_adelanto, 2) }}</strong></div>
      <div style="display:flex;justify-content:space-between;"><span>Saldo restante:</span> <strong style="color:var(--text-1);">${{ number_format($pedido->precio_saldo, 2) }}</strong></div>
      <div style="display:flex;justify-content:space-between;"><span>Estado de pago:</span> <strong style="color:var(--text-1);text-transform:capitalize;">{{ str_replace('_', ' ', $pedido->estado_pago) }}</strong></div>
    </div>

    <hr style="border:none;border-top:1px solid var(--border);margin:16px 0;">

    @if(!\App\Support\PedidoEstados::pagoVerificado($pedido->estado_pago))
      <div style="background:#FEF3C7;border:1px solid #FDE68A;color:#92400E;padding:14px 16px;border-radius:10px;font-size:0.85rem;line-height:1.5;">
        🔒 Verifica el comprobante de pago del cliente antes de poder cambiar el estado de este pedido.
      </div>
    @else
    <form action="{{ route('admin.pedidos-uniformes.update', $pedido->id) }}" method="POST">
      @csrf
      @method('PUT')
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:7px;">Estado del pedido</label>
      <select name="estado"
        style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);">
        @foreach(\App\Support\PedidoEstados::estadosDisponibles($pedido->tipo_entrega) as $estado)
          <option value="{{ $estado }}" {{ $pedido->estado === $estado ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$estado)) }}</option>
        @endforeach
      </select>
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin:14px 0 7px;">Tiempo estimado de entrega</label>
      <input type="text" name="tiempo_estimado" value="{{ $pedido->tiempo_estimado }}"
        placeholder="Ej: 3 días, 1 semana, 1 mes..."
        style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);">

      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin:14px 0 7px;">Notificar al cliente al actualizar</label>
      <div class="canal-toggles">
        <input type="checkbox" id="notif-wa-{{ $pedido->id }}" name="notificar_whatsapp" value="1" class="canal-toggle-input">
        <label for="notif-wa-{{ $pedido->id }}" class="canal-toggle-btn canal-toggle-whatsapp">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm5.8 14.15c-.24.68-1.4 1.3-1.93 1.38-.5.08-1.11.11-1.79-.11a16.5 16.5 0 01-1.6-.59c-2.83-1.22-4.67-4.08-4.81-4.27-.14-.19-1.15-1.53-1.15-2.92 0-1.39.73-2.07.99-2.35.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.41-.07.64.49.24.58.81 2 .88 2.14.07.14.12.31.02.5-.1.19-.15.31-.29.48-.14.17-.3.37-.43.5-.14.14-.29.29-.12.57.17.28.75 1.24 1.62 2.01 1.11.99 2.05 1.3 2.33 1.44.28.14.44.12.6-.07.17-.19.72-.84.91-1.13.19-.29.38-.24.64-.14.26.1 1.66.78 1.94.92.28.14.47.21.53.33.07.12.07.68-.17 1.36z"/></svg>
          WhatsApp
        </label>
        <input type="checkbox" id="notif-email-{{ $pedido->id }}" name="notificar_email" value="1" class="canal-toggle-input">
        <label for="notif-email-{{ $pedido->id }}" class="canal-toggle-btn canal-toggle-email">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg>
          Correo
        </label>
      </div>
      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;margin-top:16px;">Actualizar</button>
    </form>
    @endif
  </div>
</div>

{{-- ITEMS DEL PEDIDO --}}
<div class="card reveal" style="margin-top:24px;overflow:hidden;">
  <div style="padding:16px 20px;font-size:1rem;font-weight:700;color:var(--text-1);border-bottom:1px solid var(--border);">
    Uniformes solicitados
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:0.87rem;">
    <thead>
      <tr style="background:var(--bg-3);text-align:left;">
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Foto</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Uniforme</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Tela</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Talla</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Precio unit.</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Cantidad</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pedido->items as $item)
        <tr style="border-top:1px solid var(--border);">
          <td style="padding:10px 16px;">
            <img src="{{ asset('storage/' . $item->uniforme->imagen) }}" alt="" onclick="abrirLightbox(this.src)"
                 style="width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid var(--border);cursor:zoom-in;">
          </td>
          <td style="padding:10px 16px;font-weight:600;color:var(--text-1);">{{ $item->uniforme->nombre }}</td>
          <td style="padding:10px 16px;color:var(--text-2);">{{ $item->uniforme->tipo_tela }}</td>
          <td style="padding:10px 16px;">
            <span style="background:var(--blue-soft);color:var(--blue);padding:4px 12px;border-radius:6px;font-weight:700;">{{ $item->talla }}</span>
          </td>
          <td style="padding:10px 16px;color:var(--text-2);">${{ number_format($item->precio_unitario, 2) }}</td>
          <td style="padding:10px 16px;color:var(--text-2);">{{ $item->cantidad }}</td>
          <td style="padding:10px 16px;font-weight:700;color:var(--text-1);">${{ number_format($item->subtotal, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection
