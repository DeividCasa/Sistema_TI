@extends('Admin.panel_admin')

@section('titulo', 'Diseños 3D')
@section('page-title', 'Diseños 3D')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

@if(session('success'))
  <div class="badge-success" style="display:block;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Solicitudes de cotización — Diseños 3D</div>
</div>

@if($solicitudes->isEmpty())
  <div class="card empty-state reveal">
    <p>Aún no hay solicitudes de diseños 3D.</p>
  </div>
@else
  <div class="card reveal" style="overflow-x:auto;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Diseño</th>
          <th>Cliente</th>
          <th>Tela</th>
          <th>Estado</th>
          <th>Fecha</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        @foreach($solicitudes as $solicitud)
          @php
            $estClase = match($solicitud->estado) {
                'pendiente' => 'est-recibido',
                'cotizado'  => 'est-produccion',
                'aceptado'  => 'est-listo',
                'rechazado' => 'est-pendiente',
                default => 'est-recibido',
            };
            $estTexto = match($solicitud->estado) {
                'pendiente' => 'Pendiente',
                'cotizado'  => 'Cotizado',
                'aceptado'  => 'Aceptado',
                'rechazado' => 'Rechazado',
                default => $solicitud->estado,
            };
          @endphp
          <tr>
            <td class="cell-strong">{{ $solicitud->disenio->nombre }}</td>
            <td>{{ $solicitud->cliente->nombre }} {{ $solicitud->cliente->apellido }}</td>
            <td>{{ $solicitud->tela }}</td>
            <td><span class="est {{ $estClase }}">{{ $estTexto }}</span></td>
            <td class="cell-muted">{{ $solicitud->created_at->format('d M Y') }}</td>
            <td class="cell-actions">
              <a href="{{ route('admin.disenios3d.show', $solicitud->id) }}">Ver</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif

@endsection
