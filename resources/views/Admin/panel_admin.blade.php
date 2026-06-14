@extends('Plantilla/Plantilla')

@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@push('sidebar-menu')

<style>
    /* Sidebar simplificado - sin efectos hover/active */
    .sidebar-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-3);
        margin: 1rem 0 0.5rem 1rem;
    }
    .nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 1rem;
        margin: 0.2rem 0.5rem;
        border-radius: 10px;
        color: var(--text-2);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        position: relative;
    }
    .nav-item svg {
        width: 1.2rem;
        height: 1.2rem;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.5;
        flex-shrink: 0;
    }
    /* Eliminado: .nav-item:hover y .nav-item.active */
    .sidebar-menu {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        margin-top: 0.5rem;
    }
</style>

<div class="sidebar-label">Principal</div>
<div class="sidebar-menu">
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

    <a href="{{ route('admin.clientes.index') }}" class="nav-item">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
        Clientes
    </a>
</div>

@endpush

@section('contenido')
    @yield('admin-content')
@endsection