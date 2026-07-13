@extends('Admin.panel_admin')

@section('titulo', 'Clientes')
@section('page-title', 'Clientes registrados')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@push('estilos')
<style>
    .customers-container { max-width: 1400px; margin: 0 auto; }
    .summary-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .summary-stats { display: flex; align-items: baseline; gap: 0.75rem; flex-wrap: wrap; }
    .summary-number { font-family: var(--font-d); font-size: 1.6rem; font-weight: 800; color: var(--text-1); line-height: 1; }
    .summary-label { font-size: 0.8rem; color: var(--text-2); font-weight: 500; }
    .search-box-custom {
        display: flex;
        align-items: center;
        background: var(--bg-3);
        border: 1px solid var(--border);
        border-radius: 40px;
        padding: 0.4rem 1rem;
        gap: 0.5rem;
    }
    .search-box-custom svg { width: 14px; height: 14px; color: var(--text-3); flex-shrink: 0; }
    .search-box-custom input { background: transparent; border: none; outline: none; font-size: 0.85rem; color: var(--text-1); width: 200px; }
    .search-box-custom input::placeholder { color: var(--text-3); }
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
    @media (max-width: 768px) {
        .search-box-custom input { width: 150px; }
        .summary-card { flex-direction: column; align-items: stretch; }
        .summary-stats { justify-content: space-between; }
    }
    @media (max-width: 640px) {
        .admin-table thead { display: none; }
        .admin-table tr { display: block; margin-bottom: 1rem; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-2); }
        .admin-table td { display: flex; justify-content: space-between; align-items: center; border-top: none; border-bottom: 1px solid var(--border); }
        .admin-table td:last-child { border-bottom: none; }
        .admin-table td:before { content: attr(data-label); font-weight: 700; color: var(--text-2); width: 35%; font-size: 0.75rem; }
        .search-box-custom { width: 100%; }
        .search-box-custom input { width: 100%; }
    }
</style>
@endpush

@section('contenido')

<div class="customers-container">
    @if(session('success'))
        <div class="badge-success" style="display:block;padding:0.75rem 1rem;margin-bottom:1.5rem;border-radius:8px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="summary-card">
        <div class="summary-stats">
            <span class="summary-number">{{ $clientes->count() }}</span>
            <span class="summary-label">clientes registrados</span>
        </div>
        <div class="search-box-custom">
            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="searchCliente" placeholder="Buscar por nombre, email o ciudad..." oninput="filtrarClientes()">
        </div>
    </div>

    @if($clientes->isEmpty())
        <div class="card empty-state reveal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
            </svg>
            <p>No hay clientes registrados aún.</p>
        </div>
    @else
        <div class="card reveal" style="overflow:auto;">
        <table class="admin-table" id="tablaClientes">
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
                            <div class="cell-strong">{{ $cliente->nombre }} {{ $cliente->apellido }}</div>
                            @if($cliente->telefono)
                                <div class="cell-muted">{{ $cliente->telefono }}</div>
                            @endif
                        </td>
                        <td data-label="Correo">{{ $cliente->email }}</td>
                        <td data-label="Ciudad">
                            @if($cliente->ciudad)
                                <span class="city-badge">{{ $cliente->ciudad }}</span>
                            @else
                                <span class="cell-muted">—</span>
                            @endif
                        </td>
                        <td data-label="Pedidos">
                            <span class="orders-badge">{{ $cliente->pedidos_count }}</span>
                        </td>
                        <td data-label="Acción" class="cell-actions">
                            <a href="{{ route('admin.clientes.show', $cliente->id) }}">Ver detalles</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div id="sinResultados" style="display: none; text-align: center; padding: 2rem; color: var(--text-3);">
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
