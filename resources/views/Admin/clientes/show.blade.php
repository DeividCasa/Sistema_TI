@extends('Admin.panel_admin')

@section('titulo', 'Detalle Cliente')
@section('page-title', 'Detalle del Cliente')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@push('estilos')
<style>
    .cliente-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    /* Header */
    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .detail-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .back-btn {
        background: var(--bg-2);
        border: 1px solid var(--border);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        color: var(--text-2);
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .back-btn:hover {
        background: var(--bg-3);
        border-color: var(--blue-border);
        color: var(--blue);
    }
    /* Grid principal */
    .cliente-grid {
        display: grid;
        grid-template-columns: 1fr 1.8fr;
        gap: 1.5rem;
    }
    /* Tarjeta de información */
    .info-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }
    .info-header {
        background: var(--bg-3);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
    }
    .info-header h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-1);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .info-body {
        padding: 1.5rem;
    }
    .avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--blue), var(--blue-light));
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-d);
        font-weight: 800;
        font-size: 2rem;
        color: white;
        margin-bottom: 1.5rem;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding: 0.6rem 0;
        border-bottom: 1px solid var(--border);
    }
    .info-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-3);
    }
    .info-value {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-1);
        text-align: right;
        word-break: break-word;
        max-width: 60%;
    }
    .status-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: 20px;
        border: 1px solid transparent;
    }
    .status-active {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }
    .status-inactive {
        background: #ffe4e2;
        color: #b91c1c;
        border-color: #fecaca;
    }
    [data-theme="dark"] .status-active {
        background: #14532d;
        color: #86efac;
        border-color: #166534;
    }
    [data-theme="dark"] .status-inactive {
        background: #7f1d1d;
        color: #fecaca;
        border-color: #991b1b;
    }
    /* Tarjeta de pedidos */
    .pedidos-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .pedidos-header {
        background: var(--bg-3);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .pedidos-header h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-1);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .pedidos-count {
        background: var(--blue-soft);
        color: var(--blue);
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
    }
    /* Tabla de pedidos dentro de la tarjeta */
    .pedidos-table {
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
    }
    .empty-pedidos {
        text-align: center;
        padding: 2rem;
        color: var(--text-3);
    }
    .btn-small {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        background: var(--blue-soft);
        color: var(--blue);
        border: 1px solid var(--blue-border);
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-small:hover {
        background: var(--blue);
        color: white;
        border-color: var(--blue);
    }
    /* Responsive */
    @media (max-width: 900px) {
        .cliente-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }
        .info-value {
            text-align: left;
            max-width: 100%;
        }
    }
    @media (max-width: 768px) {
        .pedidos-table thead {
            display: none;
        }
        .pedidos-table tr {
            display: block;
            margin-bottom: 0.8rem;
            border: 1px solid var(--border);
            border-radius: 8px;
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
    }
</style>
@endpush

@section('contenido')

<div class="cliente-container">
    {{-- Header --}}
    <div class="detail-header">
        <div class="detail-title">
            <span>{{ $cliente->nombre }} {{ $cliente->apellido }}</span>
        </div>
        <a href="{{ route('admin.clientes.index') }}" class="back-btn">← Volver a clientes</a>
    </div>

    <div class="cliente-grid">
        {{-- Columna izquierda: información del cliente --}}
        <div class="info-card">
            <div class="info-header">
                <h3><i class="fas fa-user-circle"></i> Información personal</h3>
            </div>
            <div class="info-body">
                <div class="avatar-large">
                    {{ strtoupper(substr($cliente->nombre, 0, 1)) }}
                </div>
                <div class="info-row">
                    <span class="info-label">Nombre completo</span>
                    <span class="info-value">{{ $cliente->nombre }} {{ $cliente->apellido }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Correo electrónico</span>
                    <span class="info-value">{{ $cliente->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Teléfono</span>
                    <span class="info-value">{{ $cliente->telefono ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ciudad</span>
                    <span class="info-value">{{ $cliente->ciudad ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dirección</span>
                    <span class="info-value">{{ $cliente->direccion ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Registrado</span>
                    <span class="info-value">{{ $cliente->created_at->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estado</span>
                    <span class="info-value">
                        @if($cliente->activo)
                            <span class="status-badge status-active">Activo</span>
                        @else
                            <span class="status-badge status-inactive">Inactivo</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- Columna derecha: pedidos del cliente --}}
        <div class="pedidos-card">
            <div class="pedidos-header">
                <h3><i class="fas fa-shopping-bag"></i> Pedidos realizados</h3>
                <span class="pedidos-count">{{ $cliente->pedidos->count() }} pedidos</span>
            </div>

            @if($cliente->pedidos->isEmpty())
                <div class="empty-pedidos">
                    <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                    <p>Este cliente no tiene pedidos aún.</p>
                </div>
            @else
                <table class="pedidos-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cliente->pedidos as $pedido)
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
                            @endphp
                            <tr>
                                <td data-label="Código"><span class="codigo-pedido">{{ $pedido->codigo }}</span></td>
                                <td data-label="Estado"><span class="badge-state {{ $estadoClase }}">{{ $estadoTexto }}</span></td>
                                <td data-label="Total">${{ number_format($pedido->precio_total, 2) }}</td>
                                <td data-label="Acción">
                                    <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn-small">Ver detalles</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<style>
    /* Estilos auxiliares para badges de estado (reutilizados de pedidos) */
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
    [data-theme="dark"] .state-recibido { background: #78350f; color: #fde68a; border-color: #92400e; }
    [data-theme="dark"] .state-produccion { background: #1e3a8a; color: #bfdbfe; border-color: #3b82f6; }
    [data-theme="dark"] .state-listo { background: #14532d; color: #bbf7d0; border-color: #22c55e; }
    [data-theme="dark"] .state-entregado { background: #2e1065; color: #c7d2fe; border-color: #6366f1; }
    [data-theme="dark"] .state-cancelado { background: #7f1d1d; color: #fecaca; border-color: #ef4444; }
</style>

@endsection