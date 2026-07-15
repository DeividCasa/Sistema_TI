@extends('Admin.panel_admin')


@section('titulo', 'Editar prenda')
@section('page-title', 'Editar prenda')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

<div class="sec-header reveal">
  <div class="sec-title">Editar — {{ $plantilla->nombre }}</div>
  <a href="{{ route('admin.plantillas.index') }}" class="btn-secondary">← Volver</a>
</div>

<div class="card card-pad reveal" style="max-width:700px;">

  <form action="{{ route('admin.plantillas.update', $plantilla->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Nombre --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Nombre de la camiseta
      </label>
      <input type="text" name="nombre" value="{{ old('nombre', $plantilla->nombre) }}"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
      @error('nombre')<div style="color:#EF4444;font-size:0.78rem;margin-top:5px;">{{ $message }}</div>@enderror
    </div>

    {{-- Descripción --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Descripción
      </label>
      <textarea name="descripcion" rows="3"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.9rem;color:var(--text-1);background:var(--bg-2);
        outline:none;resize:vertical;">{{ old('descripcion', $plantilla->descripcion) }}</textarea>
    </div>

    {{-- Tipo de prenda + Precio --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;">
      <div>
        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
          Tipo de prenda
        </label>
        <select name="tipo_prenda"
          style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
          font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
          <option value="camiseta" {{ $plantilla->tipo_prenda == 'camiseta' ? 'selected' : '' }}>Camiseta</option>
          <option value="short"    {{ $plantilla->tipo_prenda == 'short'    ? 'selected' : '' }}>Short</option>
          <option value="conjunto" {{ $plantilla->tipo_prenda == 'conjunto' ? 'selected' : '' }}>Conjunto</option>
          <option value="otro"     {{ $plantilla->tipo_prenda == 'otro'     ? 'selected' : '' }}>Otro</option>
        </select>
      </div>

      <div>
        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
          Precio (USD)
        </label>
        <input type="number" name="precio" step="0.01" min="0" value="{{ old('precio', $plantilla->precio) }}"
          style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
          font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
        @error('precio')<div style="color:#EF4444;font-size:0.78rem;margin-top:5px;">{{ $message }}</div>@enderror
      </div>
    </div>

    {{-- Tallas disponibles --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:10px;">
        Tallas disponibles
      </label>
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        @php $tallasSel = $plantilla->tallas ?? []; @endphp
        @foreach(['XS','S','M','L','XL','XXL'] as $talla)
          <label style="display:flex;align-items:center;gap:6px;padding:8px 14px;
            border:1.5px solid var(--border);border-radius:10px;cursor:pointer;font-size:0.85rem;color:var(--text-1);">
            <input type="checkbox" name="tallas[]" value="{{ $talla }}"
              {{ in_array($talla, $tallasSel) ? 'checked' : '' }}
              style="width:15px;height:15px;accent-color:var(--blue);cursor:pointer;">
            {{ $talla }}
          </label>
        @endforeach
      </div>
    </div>

    {{-- Colores disponibles --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:10px;">
        Colores disponibles
      </label>
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        @php
          $coloresSel = $plantilla->colores ?? [];
          $colores = ['#2563EB'=>'Azul','#DC2626'=>'Rojo','#16A34A'=>'Verde','#0F172A'=>'Negro','#FFFFFF'=>'Blanco','#D97706'=>'Dorado'];
        @endphp
        @foreach($colores as $hex => $nombre)
          <label style="display:flex;align-items:center;gap:6px;padding:8px 14px;
            border:1.5px solid var(--border);border-radius:10px;cursor:pointer;font-size:0.85rem;color:var(--text-1);">
            <input type="checkbox" name="colores[]" value="{{ $hex }}"
              {{ in_array($hex, $coloresSel) ? 'checked' : '' }}
              style="width:15px;height:15px;accent-color:var(--blue);cursor:pointer;">
            <span style="width:14px;height:14px;border-radius:50%;background:{{ $hex }};border:1px solid var(--border-2);display:inline-block;"></span>
            {{ $nombre }}
          </label>
        @endforeach
      </div>
    </div>

    {{-- Imagen --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Imagen actual
      </label>
      @if($plantilla->imagen_preview)
        <img src="{{ asset('storage/'.$plantilla->imagen_preview) }}" onclick="abrirLightbox(this.src)"
          style="width:100%;max-height:200px;object-fit:contain;border-radius:10px;border:1px solid var(--border);margin-bottom:12px;cursor:zoom-in;">
      @endif

      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Cambiar imagen (opcional)
      </label>
      <label for="imagen-plantilla" id="drop-area-plantilla" style="display:flex;flex-direction:column;align-items:center;justify-content:center;
        gap:8px;padding:22px 16px;border:1.5px dashed var(--border-2);border-radius:12px;
        background:var(--bg-3);cursor:pointer;transition:all var(--tr);text-align:center;">
        <svg viewBox="0 0 24 24" style="width:26px;height:26px;stroke:var(--blue);fill:none;stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round;">
          <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
        </svg>
        <span style="font-weight:600;font-size:0.86rem;color:var(--text-1);">Haz clic para seleccionar una imagen</span>
        <span style="font-size:0.74rem;color:var(--text-3);">JPG, PNG o WEBP — máximo 2MB</span>
        <input type="file" id="imagen-plantilla" name="imagen_preview" accept="image/*"
          onchange="previsualizarArchivo(this, 'preview-imagen-plantilla', 'drop-area-plantilla')" style="display:none;">
      </label>
      <div id="preview-imagen-plantilla" style="display:none;margin-top:10px;"></div>
    </div>

    {{-- Activa --}}
    <div style="margin-bottom:24px;display:flex;align-items:center;gap:10px;">
      <input type="checkbox" name="activa" id="activa" value="1"
        {{ $plantilla->activa ? 'checked' : '' }}
        style="width:16px;height:16px;accent-color:var(--blue);cursor:pointer;">
      <label for="activa" style="font-size:0.88rem;color:var(--text-2);cursor:pointer;">
        Prenda activa (visible para los clientes)
      </label>
    </div>

    <div style="display:flex;gap:10px;">
      <button type="submit" class="btn-primary">Actualizar camiseta</button>
      <a href="{{ route('admin.plantillas.index') }}" class="btn-secondary">Cancelar</a>
    </div>

  </form>
</div>

@endsection