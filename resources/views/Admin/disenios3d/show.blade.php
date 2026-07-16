@extends('Admin.panel_admin')

@section('titulo', 'Detalle Diseño 3D')
@section('page-title', 'Detalle del Diseño 3D')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">{{ $solicitud->disenio->nombre }}</div>
  <a href="{{ route('admin.disenios3d.index') }}" class="btn-secondary">← Volver</a>
</div>

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:20px;">

  {{-- COLUMNA IZQUIERDA --}}
  <div style="display:flex;flex-direction:column;gap:20px;">

    {{-- Fotos del modelo --}}
    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Fotos del diseño</div>
      <div style="display:flex;gap:14px;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
          <div class="t-muted" style="margin-bottom:6px;">Frente</div>
          @if($solicitud->disenio->imagen_generada)
            <img src="{{ asset('storage/'.$solicitud->disenio->imagen_generada) }}"
                 style="width:100%;border-radius:10px;border:1px solid var(--border);">
          @else
            <div class="t-muted">Sin imagen</div>
          @endif
        </div>
        <div style="flex:1;min-width:200px;">
          <div class="t-muted" style="margin-bottom:6px;">Atrás</div>
          @if($solicitud->disenio->imagen_atras)
            <img src="{{ asset('storage/'.$solicitud->disenio->imagen_atras) }}"
                 style="width:100%;border-radius:10px;border:1px solid var(--border);">
          @else
            <div class="t-muted">Sin imagen</div>
          @endif
        </div>
      </div>
    </div>

    {{-- Info del cliente --}}
    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Cliente</div>
      <div style="display:flex;flex-direction:column;gap:10px;">
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Nombre</span>
          <span class="t-text">{{ $solicitud->cliente->nombre }} {{ $solicitud->cliente->apellido }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Correo</span>
          <span class="t-text">{{ $solicitud->cliente->email }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Teléfono</span>
          <span class="t-text">{{ $solicitud->cliente->telefono ?? '—' }}</span>
        </div>
      </div>
    </div>

    {{-- Detalle de la solicitud --}}
    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Detalle de la solicitud</div>
      <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:14px;">
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Tela</span>
          <span class="t-text">{{ $solicitud->tela }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;">
          <span class="t-muted">Para quién</span>
          <span class="t-text">{{ ['hombre' => 'Para Hombre', 'mujer' => 'Para Mujer'][$solicitud->genero] ?? 'Unisex' }}</span>
        </div>
      </div>
      <div class="tabla-box">
        <div class="tabla-head" style="grid-template-columns:1fr 1fr;">
          <span>Talla</span><span>Cantidad</span>
        </div>
        @foreach($solicitud->tallas as $talla)
          <div class="tabla-row" style="grid-template-columns:1fr 1fr;">
            <span class="t-text">{{ $talla->talla }}</span>
            <span class="t-sub">{{ $talla->cantidad }} uds.</span>
          </div>
        @endforeach
      </div>
      @if($solicitud->descripcion)
        <div style="margin-top:14px;">
          <div class="t-muted" style="margin-bottom:4px;">Descripción del cliente</div>
          <div class="t-text">{{ $solicitud->descripcion }}</div>
        </div>
      @endif
    </div>

  </div>

  {{-- COLUMNA DERECHA --}}
  <div style="display:flex;flex-direction:column;gap:20px;">

    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Estado</div>
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
      <div class="est {{ $estClase }}">{{ $estTexto }}</div>
    </div>

    {{-- Cotizar --}}
    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Enviar precio y mensaje</div>
      <form action="{{ route('admin.disenios3d.cotizar', $solicitud->id) }}" method="POST">
        @csrf
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);
            text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
            Precio total
          </label>
          <input type="number" step="0.01" min="0" name="precio" value="{{ $solicitud->precio }}" required
            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
            font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
        </div>
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);
            text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
            Mensaje para el cliente
          </label>
          <textarea name="mensaje_admin" rows="4"
            placeholder="Ej: Tu diseño cuesta $XX por unidad, incluye..."
            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
            font-family:var(--font-b);font-size:0.88rem;color:var(--text-1);background:var(--bg-2);
            outline:none;resize:vertical;">{{ $solicitud->mensaje_admin }}</textarea>
        </div>
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
          Enviar cotización
        </button>
      </form>
    </div>

  </div>
</div>

@endsection
