@extends('Admin.panel_admin')

@section('titulo', 'Pedidos')
@section('page-title', 'Gestión de Pedidos')
@section('admin-content')
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
    @media (max-width: 768px) {
        .admin-table thead { display: none; }
        .admin-table tr { display: block; margin-bottom: 1rem; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-2); }
        .admin-table td { display: flex; justify-content: space-between; align-items: center; border-top: none; border-bottom: 1px solid var(--border); }
        .admin-table td:last-child { border-bottom: none; }
        .admin-table td:before { content: attr(data-label); font-weight: 700; color: var(--text-2); width: 35%; font-size: 0.75rem; }
        .search-input input { width: 100%; }
    }
</style>
@endpush

@section('contenido')

<div class="pedidos-container">
    @if(session('success'))
        <div class="badge-success" style="display:block;padding:0.75rem 1rem;margin-bottom:1.5rem;border-radius:8px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Barra de filtros y resumen --}}
    <div class="filters-card">
        <div class="stats-badge">
            <span class="stats-number">{{ $pedidos->count() }}</span>
            <span class="stats-label">pedidos totales</span>
        </div>
        <div class="filter-group">
            <select id="filtroEstado" class="filter-select" onchange="aplicarFiltros()">
                <option value="todos">Todos los estados</option>
                <option value="recibido">Recibido</option>
                <option value="en_produccion">En producción</option>
                <option value="listo">Listo</option>
                <option value="entregado">Entregado</option>
                <option value="cancelado">Cancelado</option>
            </select>
            <select id="filtroPago" class="filter-select" onchange="aplicarFiltros()">
                <option value="todos">Todos los pagos</option>
                <option value="pendiente">Pendiente</option>
                <option value="adelanto_verificado">Adelanto verificado</option>
                <option value="pagado_completo">Pagado completo</option>
            </select>
            <div class="search-input">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="buscarPedido" placeholder="Buscar código o cliente..." oninput="aplicarFiltros()">
            </div>
        </div>
    </div>

    {{-- Tabla de pedidos --}}
    @if($pedidos->isEmpty())
        <div class="card empty-state reveal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p>No hay pedidos aún.</p>
        </div>
    @else
        <div class="card reveal" style="overflow:auto;">
        <table class="admin-table" id="tablaPedidos">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Pago</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidos as $pedido)
                    @php
                        $estadoClase = match($pedido->estado) {
                            'recibido' => 'badge-warning',
                            'en_produccion' => 'badge-info',
                            'listo' => 'badge-success',
                            'entregado' => 'badge-neutral',
                            'cancelado' => 'badge-danger',
                            default => 'badge-warning'
                        };
                        $estadoTexto = match($pedido->estado) {
                            'recibido' => 'Recibido',
                            'en_produccion' => 'En producción',
                            'listo' => 'Listo',
                            'entregado' => 'Entregado',
                            'cancelado' => 'Cancelado',
                            default => $pedido->estado
                        };
                        $pagoClase = match($pedido->estado_pago) {
                            'pendiente' => 'badge-warning',
                            'adelanto_verificado' => 'badge-success',
                            'pagado_completo' => 'badge-success',
                            default => 'badge-warning'
                        };
                        $pagoTexto = match($pedido->estado_pago) {
                            'pendiente' => 'Pendiente',
                            'adelanto_verificado' => 'Adelanto verificado',
                            'pagado_completo' => 'Pagado completo',
                            default => $pedido->estado_pago
                        };
                    @endphp
                    <tr class="pedido-fila"
                        data-estado="{{ $pedido->estado }}"
                        data-pago="{{ $pedido->estado_pago }}"
                        data-codigo="{{ strtolower($pedido->codigo) }}"
                        data-cliente="{{ strtolower($pedido->cliente->nombre . ' ' . $pedido->cliente->apellido . ' ' . $pedido->cliente->email) }}">
                        <td data-label="Código" class="cell-strong" style="color:var(--blue);">{{ $pedido->codigo }}</td>
                        <td data-label="Cliente">
                            <div class="cell-strong">{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</div>
                            <div class="cell-muted">{{ $pedido->cliente->email }}</div>
                        </td>
                        <td data-label="Estado"><span class="badge {{ $estadoClase }}">{{ $estadoTexto }}</span></td>
                        <td data-label="Pago">
                            <span class="badge {{ $pagoClase }}">{{ $pagoTexto }}</span>
                            @if($pedido->estado_pago !== 'pagado_completo')
                                <form action="{{ route('admin.pedidos.pago-completo', $pedido->id) }}" method="POST" style="display:inline-block;margin-left:6px;" onsubmit="return confirm('¿Marcar este pedido como pagado por completo?');">
                                    @csrf
                                    <button type="submit" class="btn-marcar-pagado">Marcar pagado</button>
                                </form>
                            @endif
                        </td>
                        <td data-label="Fecha">{{ $pedido->created_at->format('d M Y') }}</td>
                        <td data-label="Acción" class="cell-actions">
                            <a href="{{ route('admin.pedidos.show', $pedido->id) }}">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div id="sinResultados" style="display: none; text-align: center; padding: 2rem; color: var(--text-3);">
            No se encontraron pedidos con los filtros actuales.
        </div>
    @endif
</div>

<script>
    function aplicarFiltros() {
        const estadoFiltro = document.getElementById('filtroEstado').value;
        const pagoFiltro = document.getElementById('filtroPago').value;
        const busqueda = document.getElementById('buscarPedido').value.toLowerCase();
        const filas = document.querySelectorAll('.pedido-fila');
        let visibles = 0;

        filas.forEach(fila => {
            const estado = fila.getAttribute('data-estado');
            const pago = fila.getAttribute('data-pago');
            const codigo = fila.getAttribute('data-codigo');
            const cliente = fila.getAttribute('data-cliente');

            const coincideEstado = (estadoFiltro === 'todos' || estado === estadoFiltro);
            const coincidePago = (pagoFiltro === 'todos' || pago === pagoFiltro);
            const coincideBusqueda = (busqueda === '' || codigo.includes(busqueda) || cliente.includes(busqueda));

            if (coincideEstado && coincidePago && coincideBusqueda) {
                fila.style.display = '';
                visibles++;
            } else {
                fila.style.display = 'none';
            }
        });

        const sinResultados = document.getElementById('sinResultados');
        if (sinResultados) {
            sinResultados.style.display = visibles === 0 ? 'block' : 'none';
        }
    }

    document.getElementById('filtroEstado').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroPago').addEventListener('change', aplicarFiltros);
    document.getElementById('buscarPedido').addEventListener('input', aplicarFiltros);
</script>

@endsection
