@extends('Plantilla/Plantilla')

@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@push('sidebar-menu')

<div class="sidebar-label">Principal</div>

<a href="{{ route('admin.inicio') }}"
   class="nav-item {{ request()->routeIs('admin.inicio') ? 'active' : '' }}">
    Dashboard
</a>

<a href="/admin/pedidos"
   class="nav-item">
    Pedidos
</a>

<a href="{{ route('admin.plantillas.index') }}"
   class="nav-item {{ request()->routeIs('admin.plantillas.*') ? 'active' : '' }}">
    Plantillas
</a>

<a href="/admin/clientes"
   class="nav-item">
    Clientes
</a>

@endpush

@section('contenido')

    @yield('admin-content')

@endsection