@extends('Admin.panel_admin')

@section('titulo', 'Detalle Cliente')
@section('page-title', 'Detalle del Cliente')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@push('sidebar-menu')
<div class="sidebar-label">Principal</div>
<a href="{{ route('admin.inicio') }}" class="nav-item">
  <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
  Dashboard
</a>
<a href="{{ route('admin.pedidos.index') }}" class="nav-item">
  <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
  Pedidos
</a>
<a href="{{ route('admin.plantillas.index') }}" class="nav-item">
  <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
  Plantillas
</a>
<a href="{{ route('admin.clientes.index') }}" class="nav-item active">
  <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
  Clientes
</a>
@endpush

@section('contenido')

<div class="sec-header reveal">
  <div class="sec-title">{{ $cliente->nombre }} {{ $cliente->apellido }}</div>
  <a href="{{ route('admin.clientes.index') }}" class="btn-secondary">← Volver</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1.5fr;gap:20px;">

  {{-- INFO DEL CLIENTE --}}
  <div class="card card-pad reveal">
    <div class="sec-title" style="margin-bottom:16px;">Información</div>

    {{-- Avatar --}}
    <div style="width:64px;height:64px;border-radius:50%;
      background:linear-gradient(135deg,var(--blue),var(--blue-light));
      display:flex;align-items:center;justify-content:center;
      font-family:var(--font-d);font-weight:800;font-size:1.4rem;
      color:white;margin-bottom:20px;">
      {{ strtoupper(substr($cliente->nombre, 0, 1)) }}
    </div>

    <div style="display:flex;flex-direction:column;gap:12px;">
      <div>
        <div class="t-muted" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px;">Nombre completo</div>
        <div class="t-text">{{ $cliente->nombre }} {{ $cliente->apellido }}</div>
      </div>
      <div>
        <div class="t-muted" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px;">Correo</div>
        <div class="t-text">{{ $cliente->email }}</div>
      </div>
      <div>
        <div class="t-muted" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px;">Teléfono</div>
        <div class="t-text">{{ $cliente->telefono ?? '—' }}</div>
      </div>
      <div>
        <div class="t-muted" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px;">Ciudad</div>
        <div class="t-text">{{ $cliente->ciudad ?? '—' }}</div>
      </div>
      <div>
        <div class="t-muted" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px;">Dirección</div>
        <div class="t-text">{{ $cliente->direccion ?? '—' }}</div>
      </div>
      <div>
        <div class="t-muted" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px;">Registrado</div>
        <div class="t-text">{{ $cliente->created_at->format('d M Y') }}</div>
      </div>
      <div>
        <div class="t-muted" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px;">Estado</div>
        @if($cliente->activo)
          <span class="est est-listo">Activo</span>
        @else
          <span class="est est-pendiente">Inactivo</span>
        @endif
      </div>
    </div>
  </div>

  {{-- PEDIDOS DEL CLIENTE --}}
  <div class="card card-pad reveal">
    <div class="sec-title" style="margin-bottom:16px;">
      Pedidos
      <span class="sec-badge">{{ $cliente->pedidos->count() }}</span>
    </div>

    @if($cliente->pedidos->isEmpty())
      <div class="empty-state" style="padding:24px;">
        <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <p>Este cliente no tiene pedidos aún.</p>
      </div>
    @else
      <div class="tabla-box">
        <div class="tabla-head" style="grid-template-columns:1fr 1fr 1fr 0.8fr;">
          <span>Código</span>
          <span>Estado</span>
          <span>Total</span>
          <span>Ver</span>
        </div>
        @foreach($cliente->pedidos as $pedido)
          <div class="tabla-row" style="grid-template-columns:1fr 1fr 1fr 0.8fr;">
            <span class="t-code">{{ $pedido->codigo }}</span>
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
            <span class="t-text">${{ number_format($pedido->precio_total, 2) }}</span>
            <span>
              <a href="{{ route('admin.pedidos.show', $pedido->id) }}"
                style="padding:5px 12px;border-radius:8px;background:var(--blue-soft);
                color:var(--blue);font-size:0.75rem;font-weight:600;text-decoration:none;
                border:1px solid var(--blue-border);">
                Ver
              </a>
            </span>
          </div>
        @endforeach
      </div>
    @endif
  </div>

</div>

@endsection