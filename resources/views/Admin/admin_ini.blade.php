@extends('Plantilla/Plantilla')

@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')
@section('page-title', 'Panel Administrador')

@push('sidebar-menu')
  <div class="sidebar-label">Principal</div>
  
  <a href="#" class="nav-item active"> Dashboard </a>
  <a href="#" class="nav-item"> Pedidos </span> </a>
  <a href="#" class="nav-item"> Plantillas </a>
  <a href="#" class="nav-item">  Clientes </a>
@endpush

@section('contenido')

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px;">

  <div class="card card-pad">
    <div style="font-size:0.75rem; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Total Pedidos</div>
    <div style="font-family:var(--font-d); font-size:2rem; font-weight:800; color:var(--text-1);">24</div>
    <div style="font-size:0.78rem; color:var(--text-3); margin-top:4px;">↑ 3 esta semana</div>
  </div>

  <div class="card card-pad">
    <div style="font-size:0.75rem; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">En Producción</div>
    <div style="font-family:var(--font-d); font-size:2rem; font-weight:800; color:var(--blue);">8</div>
    <div style="font-size:0.78rem; color:var(--text-3); margin-top:4px;">↑ 2 nuevos hoy</div>
  </div>

  <div class="card card-pad">
    <div style="font-size:0.75rem; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Listos</div>
    <div style="font-family:var(--font-d); font-size:2rem; font-weight:800; color:#16A34A;">5</div>
    <div style="font-size:0.78rem; color:var(--text-3); margin-top:4px;">Para entregar</div>
  </div>

  <div class="card card-pad">
    <div style="font-size:0.75rem; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Clientes</div>
    <div style="font-family:var(--font-d); font-size:2rem; font-weight:800; color:#7C3AED;">12</div>
    <div style="font-size:0.78rem; color:var(--text-3); margin-top:4px;">Registrados</div>
  </div>

</div>

<div class="card card-pad">
  <h2 style="font-family:var(--font-d); font-size:1rem; font-weight:800; margin-bottom:16px;">Pedidos recientes</h2>

  <div class="tabla-head" style="grid-template-columns:1fr 2fr 1fr 1fr 1fr;">
    <span>Código</span>
    <span>Diseño</span>
    <span>Estado</span>
    <span>Cantidad</span>
    <span>Fecha</span>
  </div>

  <div class="tabla-row" style="grid-template-columns:1fr 2fr 1fr 1fr 1fr;">
    <span class="t-code">LJ-001</span>
    <span class="t-text">Clásica Azul — Nro. 10</span>
    <span><div class="est est-produccion">En producción</div></span>
    <span class="t-sub">15 uds.</span>
    <span class="t-muted">18 may 2026</span>
  </div>

  <div class="tabla-row" style="grid-template-columns:1fr 2fr 1fr 1fr 1fr;">
    <span class="t-code">LJ-002</span>
    <span class="t-text">Fuego Rojo — Nro. 7</span>
    <span><div class="est est-recibido">Recibido</div></span>
    <span class="t-sub">10 uds.</span>
    <span class="t-muted">20 may 2026</span>
  </div>

  <div class="tabla-row" style="grid-template-columns:1fr 2fr 1fr 1fr 1fr;">
    <span class="t-code">LJ-003</span>
    <span class="t-text">Verde Esmeralda — Nro. 9</span>
    <span><div class="est est-listo">Listo</div></span>
    <span class="t-sub">20 uds.</span>
    <span class="t-muted">21 may 2026</span>
  </div>

</div>

@endsection