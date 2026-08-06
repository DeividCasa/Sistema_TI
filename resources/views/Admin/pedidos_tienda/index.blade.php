@extends('Admin.panel_admin')

@section('titulo', 'Pedidos')
@section('page-title', 'Pedidos')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@push('estilos')
<style>
    .pedidos-container { max-width: 1400px; margin: 0 auto; }
    .filters-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .stats-badge {
        display: inline-flex;
        align-items: baseline;
        gap: 0.5rem;
        background: var(--bg-3);
        padding: 0.4rem 1rem;
        border-radius: 40px;
        border: 1px solid var(--border);
    }
    .stats-number { font-size: 1.3rem; font-weight: 800; font-family: var(--font-d); color: var(--text-1); }
    .stats-label { font-size: 0.75rem; color: var(--text-2); }
    .filter-group { display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; }
    .filter-select {
        background: var(--bg-3);
        border: 1px solid var(--border);
        border-radius: 40px;
        padding: 0.4rem 1rem;
        font-size: 0.8rem;
        color: var(--text-1);
        cursor: pointer;
        outline: none;
    }
    .search-input {
        display: flex;
        align-items: center;
        background: var(--bg-3);
        border: 1px solid var(--border);
        border-radius: 40px;
        padding: 0.4rem 1rem;
        gap: 0.5rem;
    }
    .search-input svg { width: 14px; height: 14px; color: var(--text-3); flex-shrink: 0; }
    .search-input input {
        background: transparent;
        border: none;
        outline: none;
        font-size: 0.8rem;
        color: var(--text-1);
        width: 180px;
    }
    .search-input input::placeholder { color: var(--text-3); }
    @media (max-width: 900px) {
        .filters-card { flex-direction: column; align-items: stretch; }
        .filter-group { justify-content: space-between; }
    }
    .cliente-nombre, .cliente-email {
        max-width: 120px; overflow: hidden; text-overflow: ellipsis;
        white-space: nowrap; display: block;
    }
    .celda-codigo { white-space: nowrap; }
    .badge-nuevo {
        background: var(--accent-soft); color: var(--accent);
        border: 1px solid var(--accent-border); padding: 1px 7px;
        border-radius: 20px; font-size: 0.62rem; font-weight: 700;
        margin-left: 6px; white-space: nowrap; display: inline-block;
    }
    #tablaPedidos { table-layout: fixed; }
    #tablaPedidos .col-codigo   { width: 175px; }
    #tablaPedidos .col-tipo     { width: 80px; }
    #tablaPedidos .col-cliente  { width: 150px; }
    #tablaPedidos .col-total    { width: 65px; }
    #tablaPedidos .col-adelanto { width: 75px; }
    #tablaPedidos .col-estado   { width: 120px; }
    #tablaPedidos .col-verdetalle { width: 85px; }
    #tablaPedidos .col-pagocompleto { width: 105px; }
</style>
@endpush

@section('contenido')

<div class="pedidos-container">
  <div class="sec-header reveal">
    <div class="sec-title">Pedidos</div>
  </div>

  {{-- Barra de filtros y resumen --}}
  <div class="filters-card">
    <div class="stats-badge">
      <span class="stats-number">{{ $pedidos->count() }}</span>
      <span class="stats-label">pedidos totales</span>
    </div>
    <div class="filter-group">
      <select id="filtroTipo" class="filter-select">
        <option value="todos">Todos los tipos</option>
        <option value="combinado">Combinado</option>
        <option value="uniforme">Uniforme</option>
        <option value="chompa">Chompa</option>
        <option value="ropa">Ropa</option>
        <option value="camiseta">Camiseta</option>
      </select>
      <select id="filtroEstado" class="filter-select">
        <option value="todos">Todos los estados</option>
        <option value="recibido">Recibido</option>
        <option value="en_produccion">En producción</option>
        <option value="listo">Listo</option>
        <option value="enviado">Enviado</option>
        <option value="entregado">Entregado</option>
        <option value="cancelado">Cancelado</option>
      </select>
      <select id="filtroPago" class="filter-select">
        <option value="todos">Todos los pagos</option>
        <option value="pendiente">Pendiente</option>
        <option value="adelanto_verificado">Adelanto verificado</option>
        <option value="pagado_completo">Pagado completo</option>
      </select>
      <div class="search-input">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="buscarPedido" placeholder="Buscar código o cliente...">
      </div>
    </div>
  </div>

  @include('Admin.pedidos_tienda._tabla_pedidos', ['pedidos' => $pedidos])
</div>

@endsection
