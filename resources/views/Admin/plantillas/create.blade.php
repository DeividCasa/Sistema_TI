@extends('Admin.panel_admin')

@section('titulo', 'Nueva Camiseta')
@section('page-title', 'Nueva Camiseta')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')



@section('contenido')

<div class="sec-header reveal">
  <div class="sec-title">Nueva Camiseta</div>
  <a href="{{ route('admin.plantillas.index') }}" class="btn-secondary">← Volver</a>
</div>

<div class="card card-pad reveal" style="max-width:700px;">

  <form action="{{ route('admin.plantillas.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Nombre --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Nombre de la camiseta
      </label>
      <input type="text" name="nombre" value="{{ old('nombre') }}"
        placeholder="Ej: Clásica Azul"
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
        placeholder="Describe el uniforme, materiales, etc."
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.9rem;color:var(--text-1);background:var(--bg-2);
        outline:none;resize:vertical;">{{ old('descripcion') }}</textarea>
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
          <option value="camiseta">Camiseta</option>
          <option value="short">Short</option>
          <option value="conjunto">Conjunto</option>
          <option value="otro">Otro</option>
        </select>
        @error('tipo_prenda')<div style="color:#EF4444;font-size:0.78rem;margin-top:5px;">{{ $message }}</div>@enderror
      </div>

      <div>
        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
          Precio (USD)
        </label>
        <input type="number" name="precio" step="0.01" min="0" value="{{ old('precio') }}"
          placeholder="25.00"
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
        @foreach(['XS','S','M','L','XL','XXL'] as $talla)
          <label style="display:flex;align-items:center;gap:6px;padding:8px 14px;
            border:1.5px solid var(--border);border-radius:10px;cursor:pointer;font-size:0.85rem;color:var(--text-1);">
            <input type="checkbox" name="tallas[]" value="{{ $talla }}"
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
          $colores = ['#2563EB'=>'Azul','#DC2626'=>'Rojo','#16A34A'=>'Verde','#0F172A'=>'Negro','#FFFFFF'=>'Blanco','#D97706'=>'Dorado'];
        @endphp
        @foreach($colores as $hex => $nombre)
          <label style="display:flex;align-items:center;gap:6px;padding:8px 14px;
            border:1.5px solid var(--border);border-radius:10px;cursor:pointer;font-size:0.85rem;color:var(--text-1);">
            <input type="checkbox" name="colores[]" value="{{ $hex }}"
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
        Imagen de la camiseta
      </label>
      <input type="file" name="imagen_preview" accept="image/*" onchange="previewImagen(event)"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.88rem;color:var(--text-2);background:var(--bg-2);outline:none;">
      @error('imagen_preview')<div style="color:#EF4444;font-size:0.78rem;margin-top:5px;">{{ $message }}</div>@enderror

      <div id="preview-wrap" style="display:none;margin-top:12px;">
        <img id="preview-img" src=""
          style="width:100%;max-height:220px;object-fit:contain;border-radius:10px;border:1px solid var(--border);">
      </div>
    </div>

    {{-- Activa --}}
    <div style="margin-bottom:24px;display:flex;align-items:center;gap:10px;">
      <input type="checkbox" name="activa" id="activa" value="1" checked
        style="width:16px;height:16px;accent-color:var(--blue);cursor:pointer;">
      <label for="activa" style="font-size:0.88rem;color:var(--text-2);cursor:pointer;">
        Camiseta activa (visible para los clientes)
      </label>
    </div>

    {{-- Botones --}}
    <div style="display:flex;gap:10px;">
      <button type="submit" class="btn-primary">Guardar camiseta</button>
      <a href="{{ route('admin.plantillas.index') }}" class="btn-secondary">Cancelar</a>
    </div>

  </form>
</div>

@push('scripts')
<script>
  function previewImagen(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('preview-wrap').style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
  }
</script>
@endpush

@endsection