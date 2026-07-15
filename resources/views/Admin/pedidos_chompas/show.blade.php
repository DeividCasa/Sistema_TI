@extends('Admin.panel_admin')

@section('titulo', 'Detalle Pedido ' . $pedido->codigo)
@section('page-title', 'Pedido ' . $pedido->codigo)
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Pedido {{ $pedido->codigo }}</div>
  <a href="{{ route('admin.pedidos-tienda.index') }}" class="btn-secondary">← Volver</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

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

    <form action="{{ route('admin.pedidos-chompas.update', $pedido->id) }}" method="POST">
      @csrf
      @method('PUT')
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:7px;">Estado del pedido</label>
      <div style="display:flex;gap:10px;">
        <select name="estado"
          style="flex:1;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);">
          @foreach(['recibido','en_produccion','listo','enviado','entregado','cancelado'] as $estado)
            <option value="{{ $estado }}" {{ $pedido->estado === $estado ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$estado)) }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn-primary" style="padding:10px 18px;">Actualizar</button>
      </div>
    </form>
  </div>
</div>

{{-- ITEMS DEL PEDIDO --}}
<div class="card reveal" style="margin-top:24px;overflow:hidden;">
  <div style="padding:16px 20px;font-size:1rem;font-weight:700;color:var(--text-1);border-bottom:1px solid var(--border);">
    Chompas solicitadas
  </div>
  <table style="width:100%;border-collapse:collapse;font-size:0.87rem;">
    <thead>
      <tr style="background:var(--bg-3);text-align:left;">
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Foto</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Chompa</th>
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
            <img src="{{ asset('storage/' . $item->chompa->imagen) }}" alt="" onclick="abrirLightbox(this.src)"
                 style="width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid var(--border);cursor:zoom-in;">
          </td>
          <td style="padding:10px 16px;font-weight:600;color:var(--text-1);">{{ $item->chompa->nombre }}</td>
          <td style="padding:10px 16px;color:var(--text-2);">{{ $item->chompa->tipo_tela }}</td>
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

{{-- COMPROBANTES DE PAGO --}}
<div class="card reveal" style="margin-top:24px;overflow:hidden;">
  <div style="padding:16px 20px;font-size:1rem;font-weight:700;color:var(--text-1);border-bottom:1px solid var(--border);">
    Comprobantes de pago (vouchers)
  </div>

  @forelse($pedido->comprobantes as $comprobante)
    <div style="display:flex;gap:20px;padding:18px 20px;border-top:1px solid var(--border);align-items:flex-start;flex-wrap:wrap;">
      @if(Str::endsWith(strtolower($comprobante->archivo), '.pdf'))
        <a href="{{ asset('storage/' . $comprobante->archivo) }}" target="_blank">
          <div style="width:130px;height:130px;border-radius:10px;background:var(--bg-3);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;">
            <svg viewBox="0 0 24 24" style="width:40px;height:40px;stroke:#EF4444;fill:none;stroke-width:1.5;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          </div>
        </a>
      @else
        <img src="{{ asset('storage/' . $comprobante->archivo) }}" alt="Voucher" onclick="abrirLightbox(this.src)"
             style="width:130px;height:130px;object-fit:cover;border-radius:10px;border:1px solid var(--border);cursor:zoom-in;">
      @endif

      <div style="flex:1;min-width:220px;font-size:0.87rem;color:var(--text-2);line-height:1.9;">
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
          <form action="{{ route('admin.comprobantes-chompas.verificar', $comprobante->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-primary" style="padding:9px 20px;width:100%;">Verificar</button>
          </form>
          <form action="{{ route('admin.comprobantes-chompas.rechazar', $comprobante->id) }}" method="POST"
                onsubmit="return confirm('¿Rechazar este comprobante?');">
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

@endsection
