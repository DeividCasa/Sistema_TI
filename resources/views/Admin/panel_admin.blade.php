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
        color: white;
        margin: 1rem 0 0.5rem 1rem;
    }
    .nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 1rem;
        margin: 0.2rem 0.5rem;
        border-radius: 10px;
        color: var(--sidebar-txt);
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
    .nav-badge {
        position: absolute;
        top: 6px;
        right: 10px;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        border-radius: 20px;
        background: var(--accent);
        color: #fff;
        font-size: 0.66rem;
        font-weight: 700;
        font-family: var(--font-d);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 2px var(--sidebar-bg);
    }
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

    <a href="{{ route('admin.plantillas.index') }}" class="nav-item">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Ropa
    </a>

    <a href="{{ route('admin.chompas.index') }}" class="nav-item">
    <svg viewBox="0 0 24 24">
        {{-- Icono: chompa / ropa --}}
        <path d="M3 9l3-5h12l3 5M3 9v10a1 1 0 001 1h16a1 1 0 001-1V9M3 9h18M9 9v6m6-6v6"/>
    </svg>
    Chompas
    </a>

    <a href="{{ route('admin.uniformes.index') }}" class="nav-item">
    <svg viewBox="0 0 24 24">
        <rect x="3" y="3" width="7" height="7"/>
    </svg>
    Uniformes escolares
    </a>

    <a href="{{ route('admin.pedidos-tienda.index') }}" class="nav-item" data-badge-key="pedidos">
        <svg viewBox="0 0 24 24">
            <path d="M3 7h18M5 7v11a2 2 0 002 2h10a2 2 0 002-2V7M9 11h6M9 15h6"/>
        </svg>
        Pedidos
        @if(($pedidosNuevosCount ?? 0) > 0)
          <span class="nav-badge">{{ $pedidosNuevosCount > 99 ? '99+' : $pedidosNuevosCount }}</span>
        @endif
    </a>

    <a href="{{ route('admin.pedidos-tienda.create') }}" class="nav-item">
        <svg viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nuevo pedido
    </a>

    <a href="{{ route('admin.disenios3d.index') }}" class="nav-item" data-badge-key="disenios3d">
        <svg viewBox="0 0 24 24"><path d="M12 2l9 4.9v10.2L12 22l-9-4.9V6.9L12 2z"/><path d="M12 22V12M21 6.9L12 12 3 6.9"/></svg>
        Diseños 3D
        @if(($disenios3dNuevosCount ?? 0) > 0)
          <span class="nav-badge">{{ $disenios3dNuevosCount > 99 ? '99+' : $disenios3dNuevosCount }}</span>
        @endif
    </a>

    <a href="{{ route('admin.clientes.index') }}" class="nav-item" data-badge-key="clientes">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
        Clientes
        @if(($clientesNuevosCount ?? 0) > 0)
          <span class="nav-badge">{{ $clientesNuevosCount > 99 ? '99+' : $clientesNuevosCount }}</span>
        @endif
    </a>

    <a href="{{ route('admin.informacion-local.edit') }}" class="nav-item">
        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
        Información del local
    </a>

    <a href="{{ route('admin.testimonios.index') }}" class="nav-item" data-badge-key="testimonios">
        <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
        Testimonios
        @if(($testimoniosPendientesCount ?? 0) > 0)
          <span class="nav-badge">{{ $testimoniosPendientesCount > 99 ? '99+' : $testimoniosPendientesCount }}</span>
        @endif
    </a>
</div>

<script>
(function () {
    var URL_CONTADOR = @json(route('admin.notificaciones.contador'));
    var INTERVALO_MS = 20000;

    function actualizarBadge(key, valor) {
        var item = document.querySelector('.nav-item[data-badge-key="' + key + '"]');
        if (!item) return;

        var badge = item.querySelector('.nav-badge');

        if (!valor || valor <= 0) {
            if (badge) badge.remove();
            return;
        }

        var texto = valor > 99 ? '99+' : String(valor);

        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'nav-badge';
            item.appendChild(badge);
        }
        badge.textContent = texto;
    }

    function consultarNotificaciones() {
        fetch(URL_CONTADOR, { headers: { 'Accept': 'application/json' } })
            .then(function (res) { return res.ok ? res.json() : Promise.reject(res.status); })
            .then(function (data) {
                actualizarBadge('pedidos', data.pedidos);
                actualizarBadge('clientes', data.clientes);
                actualizarBadge('disenios3d', data.disenios3d);
                actualizarBadge('testimonios', data.testimonios);
            })
            .catch(function () { /* silencioso: se reintenta en el siguiente intervalo */ });
    }

    setInterval(consultarNotificaciones, INTERVALO_MS);
})();
</script>

@endpush

@section('contenido')
    @yield('admin-content')
@endsection