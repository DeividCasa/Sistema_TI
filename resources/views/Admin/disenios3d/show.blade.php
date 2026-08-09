@extends('Admin.panel_admin')

@section('titulo', 'Detalle Diseño 3D')
@section('page-title', 'Detalle del Diseño 3D')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

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

<div class="sec-header reveal">
  <div style="display:flex;align-items:center;gap:12px;">
    <div class="sec-title">{{ $solicitud->disenio->nombre }}</div>
    <div class="est {{ $estClase }}">{{ $estTexto }}</div>
  </div>
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

  </div>

  {{-- COLUMNA DERECHA --}}
  <div style="display:flex;flex-direction:column;gap:20px;">

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
          <div class="t-muted" style="margin-bottom:6px;">Descripción del cliente</div>
          <div style="background:var(--bg-3);border:1px solid var(--border);border-radius:10px;
            padding:12px 14px;color:var(--text-1);font-size:0.88rem;line-height:1.6;
            white-space:pre-wrap;word-break:break-word;">{{ $solicitud->descripcion }}</div>
        </div>
      @endif
    </div>

    {{-- Cotizar --}}
    <div class="card card-pad reveal">
      <div class="sec-title" style="margin-bottom:16px;">Enviar precio y mensaje</div>
      <form action="{{ route('admin.disenios3d.cotizar', $solicitud->id) }}" method="POST" data-confirm="¿Enviar esta cotización al cliente?">
        @csrf
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);
            text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
            Precio total
          </label>
          <input type="number" step="0.01" min="0" max="250" name="precio" value="{{ old('precio', $solicitud->precio) }}" required
            oninput="if (this.value.length > 6) this.value = this.value.slice(0, 6); if (parseFloat(this.value) > 250) this.value = 250;"
            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
            font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
          @error('precio')<div style="color:#EF4444;font-size:0.78rem;margin-top:6px;">{{ $message }}</div>@enderror
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
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);
            text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
            Notificar al cliente
          </label>
          <div class="canal-toggles">
            <input type="checkbox" id="notif-wa-{{ $solicitud->id }}" name="notificar_whatsapp" value="1" class="canal-toggle-input">
            <label for="notif-wa-{{ $solicitud->id }}" class="canal-toggle-btn canal-toggle-whatsapp">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm5.8 14.15c-.24.68-1.4 1.3-1.93 1.38-.5.08-1.11.11-1.79-.11a16.5 16.5 0 01-1.6-.59c-2.83-1.22-4.67-4.08-4.81-4.27-.14-.19-1.15-1.53-1.15-2.92 0-1.39.73-2.07.99-2.35.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.41-.07.64.49.24.58.81 2 .88 2.14.07.14.12.31.02.5-.1.19-.15.31-.29.48-.14.17-.3.37-.43.5-.14.14-.29.29-.12.57.17.28.75 1.24 1.62 2.01 1.11.99 2.05 1.3 2.33 1.44.28.14.44.12.6-.07.17-.19.72-.84.91-1.13.19-.29.38-.24.64-.14.26.1 1.66.78 1.94.92.28.14.47.21.53.33.07.12.07.68-.17 1.36z"/></svg>
              WhatsApp
            </label>
            <input type="checkbox" id="notif-email-{{ $solicitud->id }}" name="notificar_email" value="1" class="canal-toggle-input">
            <label for="notif-email-{{ $solicitud->id }}" class="canal-toggle-btn canal-toggle-email">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg>
              Correo
            </label>
          </div>
        </div>
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
          Enviar cotización
        </button>
      </form>
    </div>

  </div>
</div>

@endsection
