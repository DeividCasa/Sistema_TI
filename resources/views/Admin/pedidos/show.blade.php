
@extends('Admin.panel_admin')
@section('titulo', 'Detalle Pedido')
@section('page-title', 'Detalle del Pedido')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')


@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    ✓ {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Pedido {{ $pedido->codigo }}</div>
  <a href="{{ route('admin.pedidos.index') }}" class="btn-secondary">← Volver</a>
</div>

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:20px;">

  {{-- COLUMNA IZQUIERDA --}}
  <div style="display:flex;flex-direction:column;gap:20px;">

    {{-- Info del cliente --}}
    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Cliente</div>
      <div style="display:flex;flex-direction:column;gap:10px;">
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Nombre</span>
          <span class="t-text">{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Correo</span>
          <span class="t-text">{{ $pedido->cliente->email }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Teléfono</span>
          <span class="t-text">{{ $pedido->cliente->telefono ?? '—' }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Ciudad</span>
          <span class="t-text">{{ $pedido->cliente->ciudad ?? '—' }}</span>
        </div>
      </div>
    </div>

    {{-- Tallas --}}
    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Tallas del pedido</div>
      @if($pedido->tallas->isEmpty())
        <p class="t-muted">No hay tallas registradas.</p>
      @else
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
      @endif
    </div>

    {{-- Comprobantes --}}
    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Comprobantes de pago</div>
      @if($pedido->comprobantes->isEmpty())
        <p class="t-muted">No hay comprobantes subidos aún.</p>
      @else
        @foreach($pedido->comprobantes as $comp)
          <div style="display:flex;align-items:center;justify-content:space-between;
            padding:12px;background:var(--bg-3);border-radius:10px;margin-bottom:10px;
            border:1px solid var(--border);">
            <div>
              <div class="t-text" style="text-transform:capitalize;">{{ $comp->tipo }}</div>
              <div class="t-muted">Ref: {{ $comp->referencia ?? '—' }} · ${{ number_format($comp->monto, 2) }}</div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
              @if($comp->estado == 'verificado')
                <span class="est est-listo">Verificado</span>
              @elseif($comp->estado == 'rechazado')
                <span class="est est-pendiente">Rechazado</span>
              @else
                <span class="est est-recibido">Pendiente</span>
              @endif
              <a href="{{ asset('storage/'.$comp->archivo) }}" target="_blank"
                style="padding:5px 12px;border-radius:8px;background:var(--blue-soft);
                color:var(--blue);font-size:0.75rem;font-weight:600;text-decoration:none;
                border:1px solid var(--blue-border);">
                Ver archivo
              </a>
            </div>
          </div>
        @endforeach
      @endif
    </div>

    {{-- Historial --}}
    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Historial de estados</div>
      @if($pedido->historial->isEmpty())
        <p class="t-muted">Sin cambios de estado aún.</p>
      @else
        @foreach($pedido->historial as $h)
          <div style="display:flex;gap:12px;margin-bottom:14px;">
            <div style="width:8px;height:8px;border-radius:50%;background:var(--blue);margin-top:5px;flex-shrink:0;"></div>
            <div>
              <div class="t-text" style="font-size:0.82rem;">
                {{ $h->estado_anterior ?? 'Inicio' }} → {{ $h->estado_nuevo }}
              </div>
              <div class="t-muted" style="font-size:0.75rem;">
                {{ $h->created_at->format('d M Y H:i') }}
                @if($h->administrador) · {{ $h->administrador->nombre }} @endif
              </div>
              @if($h->nota)
                <div class="t-sub" style="font-size:0.78rem;margin-top:3px;">{{ $h->nota }}</div>
              @endif
            </div>
          </div>
        @endforeach
      @endif
    </div>

  </div>

  {{-- COLUMNA DERECHA --}}
  <div style="display:flex;flex-direction:column;gap:20px;">

    {{-- Resumen del pedido --}}
    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Resumen</div>
      <div style="display:flex;flex-direction:column;gap:10px;">
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Código</span>
          <span class="t-code">{{ $pedido->codigo }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Cantidad total</span>
          <span class="t-text">{{ $pedido->cantidad_total }} uds.</span>
        </div>
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Precio total</span>
          <span class="t-text" style="font-weight:700;">${{ number_format($pedido->precio_total, 2) }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Adelanto (50%)</span>
          <span class="t-text">${{ number_format($pedido->precio_adelanto, 2) }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Saldo restante</span>
          <span class="t-text">${{ number_format($pedido->precio_saldo, 2) }}</span>
        </div>
        <div style="height:1px;background:var(--border);margin:4px 0;"></div>
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Estado pedido</span>
          @if($pedido->estado == 'recibido')
            <div class="est est-recibido">Recibido</div>
          @elseif($pedido->estado == 'en_produccion')
            <div class="est est-produccion">En producción</div>
          @elseif($pedido->estado == 'listo')
            <div class="est est-listo">Listo</div>
          @elseif($pedido->estado == 'entregado')
            <div class="est est-entregado">Entregado</div>
          @elseif($pedido->estado == 'cancelado')
            <div class="est est-pendiente">Cancelado</div>
          @endif
        </div>
      </div>
    </div>

    {{-- Cambiar estado --}}
    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Cambiar estado</div>
      <form action="{{ route('admin.pedidos.update', $pedido->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);
            text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
            Nuevo estado
          </label>
          <select name="estado"
            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
            font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
            <option value="recibido"      {{ $pedido->estado == 'recibido'      ? 'selected' : '' }}>Recibido</option>
            <option value="en_produccion" {{ $pedido->estado == 'en_produccion' ? 'selected' : '' }}>En producción</option>
            <option value="listo"         {{ $pedido->estado == 'listo'         ? 'selected' : '' }}>Listo</option>
            <option value="enviado"       {{ $pedido->estado == 'enviado'       ? 'selected' : '' }}>Enviado</option>
            <option value="entregado"     {{ $pedido->estado == 'entregado'     ? 'selected' : '' }}>Entregado</option>
            <option value="cancelado"     {{ $pedido->estado == 'cancelado'     ? 'selected' : '' }}>Cancelado</option>
          </select>
        </div>
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);
            text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
            Nota (opcional)
          </label>
          <textarea name="nota" rows="3"
            placeholder="Ej: Listo para retirar en tienda..."
            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
            font-family:var(--font-b);font-size:0.88rem;color:var(--text-1);background:var(--bg-2);
            outline:none;resize:vertical;"></textarea>
        </div>
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
          Actualizar estado
        </button>
      </form>
    </div>

  </div>
</div>

@endsection