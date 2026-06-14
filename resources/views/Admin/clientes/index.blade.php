@extends('Admin.panel_admin')

@section('titulo', 'Clientes')
@section('page-title', 'Clientes registrados')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@push('estilos')
<style>
    /* Contenedor principal */
    .customers-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    /* Tarjeta de resumen */
    .summary-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .summary-stats {
        display: flex;
        align-items: baseline;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .summary-number {
        font-size: 2rem;
        font-weight: 400;
        font-family: var(--font-d);
        color: var(--text-1);
        line-height: 1;
    }
    .summary-label {
        font-size: 0.8rem;
        color: var(--text-2);
        font-weight: 500;
    }
    .search-box-custom {
        display: flex;
        align-items: center;
        background: var(--bg-3);
        border: 1px solid var(--border);
        border-radius: 40px;
        padding: 0.4rem 1rem;
        gap: 0.5rem;
    }
    .search-box-custom i {
        color: var(--text-3);
        font-size: 0.85rem;
    }
    .search-box-custom input {
        background: transparent;
        border: none;
        outline: none;
        font-size: 0.85rem;
        color: var(--text-1);
        width: 200px;
    }
    .search-box-custom input::placeholder {
        color: var(--text-3);
    }
    /* Tabla moderna */
    .customers-table {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
        border-collapse: collapse;
    }
    .customers-table th,
    .customers-table td {
        padding: 1rem 1.2rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
        font-size: 0.85rem;
    }
    .customers-table th {
        background: var(--bg-3);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: var(--text-2);
    }
    .customers-table tr:last-child td {
        border-bottom: none;
    }
    .customers-table tr:hover td {
        background: var(--bg-3);
    }
    .customer-name {
        font-weight: 600;
        color: var(--text-1);
        margin-bottom: 0.2rem;
    }
    .customer-phone {
        font-size: 0.7rem;
        color: var(--text-3);
    }
    .customer-email {
        color: var(--text-2);
        word-break: break-all;
    }
    .city-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        background: var(--blue-soft);
        color: var(--blue);
        border: 1px solid var(--blue-border);
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .orders-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        padding: 0.2rem 0.5rem;
        background: var(--bg-3);
        border: 1px solid var(--border);
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.8rem;
        color: var(--text-1);
    }
    .btn-view {
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
    .btn-view:hover {
        background: var(--blue);
        color: white;
        border-color: var(--blue);
    }
    .empty-state-custom {
        text-align: center;
        padding: 3rem;
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
    }
    .empty-state-custom svg {
        width: 60px;
        height: 60px;
        stroke: var(--text-3);
        margin-bottom: 1rem;
    }
    @media (max-width: 768px) {
        .customers-table th,
        .customers-table td {
            padding: 0.8rem;
        }
        .search-box-custom input {
            width: 150px;
        }
        .summary-card {
            flex-direction: column;
            align-items: stretch;
        }
        .summary-stats {
            justify-content: space-between;
        }
    }
    @media (max-width: 640px) {
        .customers-table thead {
            display: none;
        }
        .customers-table tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--bg-2);
        }
        .customers-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem;
            border-bottom: 1px solid var(--border);
        }
        .customers-table td:last-child {
            border-bottom: none;
        }
        .customers-table td:before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--text-2);
            width: 35%;
            font-size: 0.75rem;
        }
        .search-box-custom {
            width: 100%;
        }
        .search-box-custom input {
            width: 100%;
        }
    }
</style>
@endpush

@section('contenido')

<div class="customers-container">
    {{-- Mensaje de éxito (si existe) --}}
    @if(session('success'))
        <div style="background:#DCFCE7; border-left:4px solid #15803D; color:#15803D; padding:0.75rem 1rem; margin-bottom:1.5rem; border-radius:4px;">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Tarjeta de resumen y buscador --}}
    <div class="summary-card">
        <div class="summary-stats">
            <span class="summary-number">{{ $clientes->count() }}</span>
            <span class="summary-label">clientes registrados</span>
        </div>
        <div class="search-box-custom">
            <i class="fas fa-search"></i>
            <input type="text" id="searchCliente" placeholder="Buscar por nombre, email o ciudad..." oninput="filtrarClientes()">
        </div>
    </div>

    {{-- Tabla de clientes --}}
    @if($clientes->isEmpty())
        <div class="empty-state-custom">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
            </svg>
            <p style="color: var(--text-2);">No hay clientes registrados aún.</p>
        </div>
    @else
        <table class="customers-table" id="tablaClientes">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Ciudad</th>
                    <th>Pedidos</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                    <tr class="cliente-fila" data-nombre="{{ strtolower($cliente->nombre . ' ' . $cliente->apellido) }}" data-email="{{ strtolower($cliente->email) }}" data-ciudad="{{ strtolower($cliente->ciudad ?? '') }}">
                        <td data-label="Nombre">
                            <div class="customer-name">{{ $cliente->nombre }} {{ $cliente->apellido }}</div>
                            @if($cliente->telefono)
                                <div class="customer-phone"><i class="fas fa-phone-alt" style="font-size: 0.6rem;"></i> {{ $cliente->telefono }}</div>
                            @endif
                        </td>
                        <td data-label="Correo" class="customer-email">{{ $cliente->email }}</td>
                        <td data-label="Ciudad">
                            @if($cliente->ciudad)
                                <span class="city-badge">{{ $cliente->ciudad }}</span>
                            @else
                                <span class="t-muted">—</span>
                            @endif
                        </td>
                        <td data-label="Pedidos">
                            <span class="orders-badge">{{ $cliente->pedidos_count }}</span>
                        </td>
                        <td data-label="Acción">
                            <a href="{{ route('admin.clientes.show', $cliente->id) }}" class="btn-view">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div id="sinResultados" style="display: none; text-align: center; padding: 2rem; color: var(--text-3);">
            <i class="fas fa-user-slash" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
            No se encontraron clientes con esa búsqueda.
        </div>
    @endif
</div>

<script>
    function filtrarClientes() {
        const input = document.getElementById('searchCliente');
        const filtro = input.value.toLowerCase();
        const filas = document.querySelectorAll('.cliente-fila');
        let visibles = 0;

        filas.forEach(fila => {
            const nombre = fila.getAttribute('data-nombre') || '';
            const email = fila.getAttribute('data-email') || '';
            const ciudad = fila.getAttribute('data-ciudad') || '';
            const coincide = nombre.includes(filtro) || email.includes(filtro) || ciudad.includes(filtro);

            if (coincide || filtro === '') {
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
</script>

@endsection