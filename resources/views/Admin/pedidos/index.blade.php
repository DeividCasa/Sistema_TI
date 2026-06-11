@extends('Admin.panel_admin')
@section('titulo', 'Pedidos')
@section('page-title', 'Gestión de Pedidos')
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
  <div class="sec-title">
    Pedidos
    <span class="sec-badge">{{ $pedidos->count() }} en total</span>
  </div>
</div>

<div class="tabla-box reveal">
  <div class="tabla-head" style="grid-template-columns:1fr 1.5fr 1fr 1fr 1fr 0.8fr;">
    <span>Código</span>
    <span>Cliente</span>
    <span>Estado</span>
    <span>Pago</span>
    <span>Fecha</span>
    <span>Acción</span>
  </div>

  @forelse($pedidos as $pedido)
    <div class="tabla-row" style="grid-template-columns:1fr 1.5fr 1fr 1fr 1fr 0.8fr;">
      <span class="t-code">{{ $pedido->codigo }}</span>
      <div>
        <div class="t-text">{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</div>
        <div class="t-muted">{{ $pedido->cliente->email }}</div>
      </div>
      <span>
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
      </span>
      <span>
        @if($pedido->estado_pago == 'pendiente')
          <div class="est est-pendiente">Pendiente</div>
        @elseif($pedido->estado_pago == 'adelanto_verificado')
          <div class="est est-produccion">Adelanto ✓</div>
        @elseif($pedido->estado_pago == 'pagado_completo')
          <div class="est est-listo">Pagado</div>
        @else
          <div class="est est-recibido">{{ $pedido->estado_pago }}</div>
        @endif
      </span>
      <span class="t-muted">{{ $pedido->created_at->format('d M Y') }}</span>
      <span>
        <a href="{{ route('admin.pedidos.show', $pedido->id) }}"
           style="padding:6px 14px;border-radius:8px;background:var(--blue-soft);
           color:var(--blue);font-size:0.78rem;font-weight:600;text-decoration:none;
           border:1px solid var(--blue-border);">
          Ver
        </a>
      </span>
    </div>
  @empty
    <div class="empty-state">
      <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <p>No hay pedidos aún.</p>
    </div>
  @endforelse
</div>

@endsection