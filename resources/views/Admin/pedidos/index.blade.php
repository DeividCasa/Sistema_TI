@extends('Admin.panel_admin')

@section('titulo', 'Pedidos')
@section('page-title', 'Gestión de Pedidos')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@push('estilos')
<style>
    /* Contenedor principal */
    .pedidos-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    /* Tarjeta de resumen + filtros */
    .filters-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
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
    .stats-number {
        font-size: 1.3rem;
        font-weight: 800;
        font-family: var(--font-d);
        color: var(--text-1);
    }
    .stats-label {
        font-size: 0.75rem;
        color: var(--text-2);
    }
    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
    }
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
    .search-input i {
        color: var(--text-3);
        font-size: 0.8rem;
    }
    .search-input input {
        background: transparent;
        border: none;
        outline: none;
        font-size: 0.8rem;
        color: var(--text-1);
        width: 180px;
    }
    .search-input input::placeholder {
        color: var(--text-3);
    }
    /* Tabla moderna */
    .pedidos-table {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow-x: auto;
        width: 100%;
        border-collapse: collapse;
    }
    .pedidos-table th,
    .pedidos-table td {
        padding: 1rem 1.2rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
        font-size: 0.85rem;
    }
    .pedidos-table th {
        background: var(--bg-3);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: var(--text-2);
    }
    .pedidos-table tr:last-child td {
        border-bottom: none;
    }
    .pedidos-table tr:hover td {
        background: var(--bg-3);
    }
    .codigo-pedido {
        font-family: monospace;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-1);
        letter-spacing: 0.5px;
    }
    .cliente-info {
        display: flex;
        flex-direction: column;
    }
    .cliente-nombre {
        font-weight: 600;
        color: var(--text-1);
        margin-bottom: 0.2rem;
    }
    .cliente-email {
        font-size: 0.7rem;
        color: var(--text-3);
    }
    /* Badges de estado */
    .badge-state {
        display: inline-block;
        padding: 0.25rem 0.7rem;
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: 20px;
        border: 1px solid transparent;
    }
    .state-recibido { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .state-produccion { background: #dbeafe; color: #1e40af; border-color: #bfdbfe; }
    .state-listo { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .state-entregado { background: #e0e7ff; color: #3730a3; border-color: #c7d2fe; }
    .state-cancelado { background: #ffe4e2; color: #b91c1c; border-color: #fecaca; }
    .state-pendiente { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .state-verificado { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .state-pagado { background: #dbeafe; color: #1e40af; border-color: #bfdbfe; }
    [data-theme="dark"] .state-recibido { background: #78350f; color: #fde68a; border-color: #92400e; }
    [data-theme="dark"] .state-produccion { background: #1e3a8a; color: #bfdbfe; border-color: #3b82f6; }
    [data-theme="dark"] .state-listo { background: #14532d; color: #bbf7d0; border-color: #22c55e; }
    [data-theme="dark"] .state-entregado { background: #2e1065; color: #c7d2fe; border-color: #6366f1; }
    [data-theme="dark"] .state-cancelado { background: #7f1d1d; color: #fecaca; border-color: #ef4444; }
    [data-theme="dark"] .state-pendiente { background: #78350f; color: #fde68a; border-color: #92400e; }
    .btn-ver {
        display: inline-block;
        padding: 0.3rem 0.9rem;
        background: var(--blue-soft);
        color: var(--blue);
        border: 1px solid var(--blue-border);
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-ver:hover {
        background: var(--blue);
        color: white;
        border-color: var(--blue);
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
    }
    .empty-state svg {
        width: 60px;
        height: 60px;
        stroke: var(--text-3);
        margin-bottom: 1rem;
    }
    /* Responsive */
    @media (max-width: 900px) {
        .filters-card {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-group {
            justify-content: space-between;
        }
        .pedidos-table th,
        .pedidos-table td {
            padding: 0.8rem;
        }
    }
    @media (max-width: 768px) {
        .pedidos-table thead {
            display: none;
        }
        .pedidos-table tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--bg-2);
        }
        .pedidos-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem;
            border-bottom: 1px solid var(--border);
        }
        .pedidos-table td:last-child {
            border-bottom: none;
        }
        .pedidos-table td:before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--text-2);
            width: 35%;
            font-size: 0.75rem;
        }
        .search-input input {
            width: 100%;
        }
    }
</style>
@endpush

@section('contenido')

<div class="pedidos-container">
    @if(session('success'))
        <div style="background:#DCFCE7; border-left:4px solid #15803D; color:#15803D; padding:0.75rem 1rem; margin-bottom:1.5rem; border-radius:4px;">
            ✓ {{ session('success') }}
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
                <i class="fas fa-search"></i>
                <input type="text" id="buscarPedido" placeholder="Buscar código o cliente..." oninput="aplicarFiltros()">
            </div>
        </div>
    </div>

    {{-- Tabla de pedidos --}}
    @if($pedidos->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p style="color: var(--text-2);">No hay pedidos aún.</p>
        </div>
    @else
        <table class="pedidos-table" id="tablaPedidos">
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
                            'recibido' => 'state-recibido',
                            'en_produccion' => 'state-produccion',
                            'listo' => 'state-listo',
                            'entregado' => 'state-entregado',
                            'cancelado' => 'state-cancelado',
                            default => 'state-pendiente'
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
                            'pendiente' => 'state-pendiente',
                            'adelanto_verificado' => 'state-verificado',
                            'pagado_completo' => 'state-pagado',
                            default => 'state-pendiente'
                        };
                        $pagoTexto = match($pedido->estado_pago) {
                            'pendiente' => 'Pendiente',
                            'adelanto_verificado' => 'Adelanto ✓',
                            'pagado_completo' => 'Pagado completo',
                            default => $pedido->estado_pago
                        };
                    @endphp
                    <tr class="pedido-fila"
                        data-estado="{{ $pedido->estado }}"
                        data-pago="{{ $pedido->estado_pago }}"
                        data-codigo="{{ strtolower($pedido->codigo) }}"
                        data-cliente="{{ strtolower($pedido->cliente->nombre . ' ' . $pedido->cliente->apellido . ' ' . $pedido->cliente->email) }}">
                        <td data-label="Código"><span class="codigo-pedido">{{ $pedido->codigo }}</span></td>
                        <td data-label="Cliente">
                            <div class="cliente-info">
                                <span class="cliente-nombre">{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</span>
                                <span class="cliente-email">{{ $pedido->cliente->email }}</span>
                            </div>
                        </td>
                        <td data-label="Estado"><span class="badge-state {{ $estadoClase }}">{{ $estadoTexto }}</span></td>
                        <td data-label="Pago"><span class="badge-state {{ $pagoClase }}">{{ $pagoTexto }}</span></td>
                        <td data-label="Fecha">{{ $pedido->created_at->format('d M Y') }}</td>
                        <td data-label="Acción">
                            <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn-ver">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div id="sinResultados" style="display: none; text-align: center; padding: 2rem; color: var(--text-3);">
            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
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

    // Escuchar cambios en los filtros
    document.getElementById('filtroEstado').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroPago').addEventListener('change', aplicarFiltros);
    document.getElementById('buscarPedido').addEventListener('input', aplicarFiltros);
</script>

@endsection