
@extends('Admin.panel_admin')
@section('titulo', 'Detalle Pedido')
@section('page-title', 'Detalle del Pedido')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')


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
  <div class="sec-title">Pedido {{ $pedido->codigo }}</div>
  <a href="{{ route('admin.pedidos-tienda.index') }}" class="btn-secondary">← Volver</a>
</div>
{{-- IMAGEN --}}
        <div style="width:140px;height:140px;border-radius:12px;background:var(--bg-3);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;margin-bottom:20px;">
          @php
            $imagenPedido = optional($pedido->disenio)->imagen_generada
              ?: optional($pedido->disenio->plantilla ?? null)->imagen_preview;
          @endphp
          @if($imagenPedido)
            <img src="{{ asset('storage/'.$imagenPedido) }}"
                 alt="{{ $pedido->disenio->nombre }}"
                 onclick="abrirLightbox(this.src)"
                 style="width:100%;height:100%;object-fit:cover;cursor:zoom-in;">
          @else
            <svg viewBox="0 0 24 24" style="width:40px;height:40px;stroke:var(--text-3);fill:none;stroke-width:1.5;">
              <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
            </svg>
          @endif
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

  @foreach($pedido->comprobantes as $comp)
    <div style="background:var(--bg-3);border-radius:10px;padding:14px;margin-bottom:10px;border:1px solid var(--border);">
      <div style="display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap;">
        @if(Str::endsWith(strtolower($comp->archivo), '.pdf'))
          <a href="{{ asset('storage/'.$comp->archivo) }}" target="_blank" style="flex-shrink:0;">
            <div style="width:100px;height:100px;border-radius:10px;background:var(--bg-2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;">
              <svg viewBox="0 0 24 24" style="width:34px;height:34px;stroke:#EF4444;fill:none;stroke-width:1.5;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
          </a>
        @else
          <img src="{{ asset('storage/'.$comp->archivo) }}" alt="Voucher" onclick="abrirLightbox(this.src)"
            style="width:100px;height:100px;object-fit:cover;border-radius:10px;border:1px solid var(--border);cursor:zoom-in;flex-shrink:0;">
        @endif

        <div style="flex:1;min-width:180px;">
          <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;">
            <div>
              <div class="t-text" style="text-transform:capitalize;">{{ $comp->tipo }}</div>
              <div class="t-muted">Ref: {{ $comp->referencia ?? '—' }} · ${{ number_format($comp->monto, 2) }}</div>
            </div>
            @if($comp->estado == 'verificado')
              <span class="est est-listo">Verificado</span>
            @elseif($comp->estado == 'rechazado')
              <span class="est est-pendiente">Rechazado</span>
            @else
              <span class="est est-recibido">Pendiente</span>
            @endif
          </div>

      <div style="display:flex;gap:8px;flex-wrap:wrap;">
        @if($comp->estado == 'pendiente')
          <form action="{{ route('admin.comprobantes.verificar', $comp->id) }}" method="POST">
            @csrf
            <button type="submit"
              style="padding:6px 14px;border-radius:8px;background:#DCFCE7;
              color:#15803D;font-size:0.78rem;font-weight:600;border:1px solid #BBF7D0;cursor:pointer;">
              Verificar
            </button>
          </form>
  
          <form action="{{ route('admin.comprobantes.rechazar', $comp->id) }}" method="POST"
                onsubmit="return confirm('¿Rechazar este comprobante?')">
            @csrf
            <input type="hidden" name="nota" value="Comprobante no válido.">
            <button type="submit"
              style="padding:6px 14px;border-radius:8px;background:#FEE2E2;
              color:#991B1B;font-size:0.78rem;font-weight:600;border:1px solid #FECACA;cursor:pointer;">
              Rechazar
            </button>
          </form>
        @endif
  
        @if($comp->nota_admin)
          <div style="width:100%;font-size:0.78rem;color:var(--text-3);margin-top:6px;">
            Nota: {{ $comp->nota_admin }}
          </div>
        @endif
      </div>
        </div>
      </div>
    </div>
  @endforeach

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
      @if(!\App\Support\PedidoEstados::pagoVerificado($pedido->estado_pago))
        <div style="background:#FEF3C7;border:1px solid #FDE68A;color:#92400E;padding:14px 16px;border-radius:10px;font-size:0.85rem;line-height:1.5;">
          🔒 Verifica el comprobante de pago del cliente antes de poder cambiar el estado de este pedido.
        </div>
      @else
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
            Tiempo estimado de entrega
          </label>
          <input type="text" name="tiempo_estimado" value="{{ $pedido->tiempo_estimado }}"
            placeholder="Ej: 3 días, 1 semana, 1 mes..."
            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
            font-family:var(--font-b);font-size:0.88rem;color:var(--text-1);background:var(--bg-2);outline:none;">
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
      @endif
    </div>

  </div>
</div>

@endsection