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
</style>
@endpush

@section('contenido')

<div class="pedidos-container">
  @if(session('success'))
    <div class="badge-success" style="display:block;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
      {{ session('success') }}
    </div>
  @endif

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

  @if($pedidos->isEmpty())
    <div class="card empty-state reveal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="width:40px;height:40px;margin:0 auto 10px;display:block;">
        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
      <p>Aún no hay pedidos.</p>
    </div>
  @else
    <div class="card reveal" style="overflow:auto;">
      <table class="admin-table" id="tablaPedidos" style="min-width:950px;">
        <thead>
          <tr>
            <th>Código</th>
            <th>Tipo</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Adelanto (50%)</th>
            <th>Estado pago</th>
            <th></th>
            <th>Pago completo</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pedidos as $entrada)
            @php
              $pedido = $entrada['pedido'];
              $pagos = [
                'pendiente'             => ['badge-warning', 'Pago pendiente'],
                'adelanto_enviado'      => ['badge-info', 'Adelanto enviado'],
                'adelanto_verificado'   => ['badge-success', 'Adelanto verificado'],
                'pago_completo_enviado' => ['badge-info', 'Pago completo enviado'],
                'saldo_enviado'         => ['badge-info', 'Saldo enviado'],
                'pagado_completo'       => ['badge-success', 'Pagado completo'],
              ];
              [$claseBadge, $texto] = $pagos[$pedido->estado_pago] ?? ['badge-neutral', $pedido->estado_pago];

              $rutaPagoCompleto = match($entrada['tipo']) {
                'Combinado' => 'admin.pedidos-tienda.pago-completo',
                'Uniforme'  => 'admin.pedidos-uniformes.pago-completo',
                'Chompa'    => 'admin.pedidos-chompas.pago-completo',
                'Ropa'      => 'admin.pedidos-plantillas.pago-completo',
                'Camiseta'  => 'admin.pedidos.pago-completo',
              };
              $rutaDetalle = match($entrada['tipo']) {
                'Combinado' => 'admin.pedidos-tienda.show',
                'Uniforme'  => 'admin.pedidos-uniformes.show',
                'Chompa'    => 'admin.pedidos-chompas.show',
                'Ropa'      => 'admin.pedidos-plantillas.show',
                'Camiseta'  => 'admin.pedidos.show',
              };
              // El pedido combinado (PedidoMaestro) no tiene un "estado" de
              // producción propio: cada hijo (ropa/uniforme/chompa) lleva el suyo.
              // Se marca como "combinado" para el filtro de estado en vez de
              // fallar o mostrar un valor incorrecto.
              $estadoFila = $pedido->estado ?? 'combinado';
            @endphp
            <tr class="pedido-fila"
                data-tipo="{{ strtolower($entrada['tipo']) }}"
                data-estado="{{ $estadoFila }}"
                data-pago="{{ $pedido->estado_pago }}"
                data-codigo="{{ strtolower($pedido->codigo) }}"
                data-cliente="{{ strtolower($pedido->cliente->nombre . ' ' . $pedido->cliente->apellido . ' ' . $pedido->cliente->email) }}">
              <td class="cell-strong" style="color:var(--blue);">
                {{ $pedido->codigo }}
                @if($entrada['nuevo'])
                  <span style="background:var(--accent-soft);color:var(--accent);border:1px solid var(--accent-border);padding:2px 8px;border-radius:20px;font-size:0.65rem;font-weight:700;margin-left:6px;vertical-align:middle;">🆕 Nuevo</span>
                @endif
              </td>
              <td>
                <span style="background:var(--bg-3);border:1px solid var(--border);padding:3px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;">
                  {{ $entrada['tipo'] }}
                </span>
              </td>
              <td>
                <div class="cell-strong">{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</div>
                <div class="cell-muted">{{ $pedido->cliente->email }}</div>
              </td>
              <td class="cell-strong">${{ number_format($pedido->precio_total, 2) }}</td>
              <td>${{ number_format($pedido->precio_adelanto, 2) }}</td>
              <td>
                <span class="badge {{ $claseBadge }}">{{ $texto }}</span>
              </td>
              <td class="cell-actions">
                <a href="{{ route($rutaDetalle, $pedido->id) }}">Ver detalle</a>
              </td>
              <td class="cell-actions">
                @if($pedido->estado_pago !== 'pagado_completo')
                  <form action="{{ route($rutaPagoCompleto, $pedido->id) }}" method="POST" onsubmit="return confirm('¿Marcar este pedido como pagado por completo?');">
                    @csrf
                    <button type="submit" class="btn-marcar-pagado">Marcar pagado</button>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div id="sinResultados" style="display:none;text-align:center;padding:2rem;color:var(--text-3);">
      No se encontraron pedidos con los filtros actuales.
    </div>
  @endif
</div>

<script>
  function aplicarFiltrosPedidos() {
    const tipoFiltro = document.getElementById('filtroTipo').value;
    const estadoFiltro = document.getElementById('filtroEstado').value;
    const pagoFiltro = document.getElementById('filtroPago').value;
    const busqueda = document.getElementById('buscarPedido').value.toLowerCase();
    const filas = document.querySelectorAll('.pedido-fila');
    let visibles = 0;

    filas.forEach(fila => {
      const tipo = fila.getAttribute('data-tipo');
      const estado = fila.getAttribute('data-estado');
      const pago = fila.getAttribute('data-pago');
      const codigo = fila.getAttribute('data-codigo');
      const cliente = fila.getAttribute('data-cliente');

      const coincideTipo = (tipoFiltro === 'todos' || tipo === tipoFiltro);
      const coincideEstado = (estadoFiltro === 'todos' || estado === estadoFiltro);
      const coincidePago = (pagoFiltro === 'todos' || pago === pagoFiltro);
      const coincideBusqueda = (busqueda === '' || codigo.includes(busqueda) || cliente.includes(busqueda));

      if (coincideTipo && coincideEstado && coincidePago && coincideBusqueda) {
        fila.style.display = '';
        visibles++;
      } else {
        fila.style.display = 'none';
      }
    });

    const sinResultados = document.getElementById('sinResultados');
    if (sinResultados) sinResultados.style.display = visibles === 0 ? 'block' : 'none';
  }

  ['filtroTipo', 'filtroEstado', 'filtroPago'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', aplicarFiltrosPedidos);
  });
  document.getElementById('buscarPedido')?.addEventListener('input', aplicarFiltrosPedidos);
</script>

@endsection
