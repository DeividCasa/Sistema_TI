@extends('Admin.panel_admin')

@section('titulo', 'Diseños 3D')
@section('page-title', 'Diseños 3D')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    ✓ {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Solicitudes de cotización — Diseños 3D</div>
</div>

@if($solicitudes->isEmpty())
  <div class="card card-pad" style="text-align:center;padding:3rem;">
    <p class="t-muted">Aún no hay solicitudes de diseños 3D.</p>
  </div>
@else
  <div class="card" style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr style="background:var(--bg-3);">
          <th style="padding:1rem 1.2rem;text-align:left;font-size:0.7rem;text-transform:uppercase;color:var(--text-2);">Diseño</th>
          <th style="padding:1rem 1.2rem;text-align:left;font-size:0.7rem;text-transform:uppercase;color:var(--text-2);">Cliente</th>
          <th style="padding:1rem 1.2rem;text-align:left;font-size:0.7rem;text-transform:uppercase;color:var(--text-2);">Tela</th>
          <th style="padding:1rem 1.2rem;text-align:left;font-size:0.7rem;text-transform:uppercase;color:var(--text-2);">Estado</th>
          <th style="padding:1rem 1.2rem;text-align:left;font-size:0.7rem;text-transform:uppercase;color:var(--text-2);">Fecha</th>
          <th style="padding:1rem 1.2rem;text-align:left;font-size:0.7rem;text-transform:uppercase;color:var(--text-2);">Acción</th>
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
          <tr style="border-bottom:1px solid var(--border);">
            <td style="padding:1rem 1.2rem;font-size:0.85rem;color:var(--text-1);">{{ $solicitud->disenio->nombre }}</td>
            <td style="padding:1rem 1.2rem;font-size:0.85rem;color:var(--text-1);">{{ $solicitud->cliente->nombre }} {{ $solicitud->cliente->apellido }}</td>
            <td style="padding:1rem 1.2rem;font-size:0.85rem;color:var(--text-2);">{{ $solicitud->tela }}</td>
            <td style="padding:1rem 1.2rem;"><span class="est {{ $estClase }}">{{ $estTexto }}</span></td>
            <td style="padding:1rem 1.2rem;font-size:0.8rem;color:var(--text-3);">{{ $solicitud->created_at->format('d M Y') }}</td>
            <td style="padding:1rem 1.2rem;">
              <a href="{{ route('admin.disenios3d.show', $solicitud->id) }}"
                 style="padding:0.3rem 0.9rem;background:var(--blue-soft);color:var(--blue);border:1px solid var(--blue-border);border-radius:6px;font-size:0.75rem;font-weight:600;text-decoration:none;">
                Ver
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif

@endsection
