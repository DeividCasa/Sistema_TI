@extends('Admin.panel_admin')

@section('titulo', 'Clientes')
@section('page-title', 'Clientes registrados')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')


@section('contenido')

<div class="sec-header reveal">
  <div class="sec-title">
    Clientes
    <span class="sec-badge">{{ $clientes->count() }} registrados</span>
  </div>
</div>

<div class="tabla-box reveal">
  <div class="tabla-head" style="grid-template-columns:2fr 2fr 1fr 1fr 1fr;">
    <span>Nombre</span>
    <span>Correo</span>
    <span>Ciudad</span>
    <span>Pedidos</span>
    <span>Acción</span>
  </div>

  @forelse($clientes as $cliente)
    <div class="tabla-row" style="grid-template-columns:2fr 2fr 1fr 1fr 1fr;">
      <div>
        <div class="t-text">{{ $cliente->nombre }} {{ $cliente->apellido }}</div>
        <div class="t-muted">{{ $cliente->telefono ?? '—' }}</div>
      </div>
      <span class="t-sub">{{ $cliente->email }}</span>
      <span class="t-sub">{{ $cliente->ciudad ?? '—' }}</span>
      <span>
        <span class="sec-badge">{{ $cliente->pedidos_count }}</span>
      </span>
      <span>
        <a href="{{ route('admin.clientes.show', $cliente->id) }}"
           style="padding:6px 14px;border-radius:8px;background:var(--blue-soft);
           color:var(--blue);font-size:0.78rem;font-weight:600;text-decoration:none;
           border:1px solid var(--blue-border);">
          Ver
        </a>
      </span>
    </div>
  @empty
    <div class="empty-state">
      <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
      <p>No hay clientes registrados aún.</p>
    </div>
  @endforelse
</div>

@endsection