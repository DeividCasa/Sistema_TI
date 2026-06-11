@extends('Admin.panel_admin')

@section('titulo', 'Nueva Plantilla')
@section('page-title', 'Nueva Plantilla')
@section('admin-content')

@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')



@section('contenido')

<div class="sec-header reveal">
  <div class="sec-title">Nueva Plantilla</div>
  <a href="{{ route('admin.plantillas.index') }}" class="btn-secondary">
    ← Volver
  </a>
</div>

<div class="card card-pad reveal" style="max-width:600px;">

  <form action="{{ route('admin.plantillas.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Nombre --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Nombre de la plantilla
      </label>
      <input type="text" name="nombre" value="{{ old('nombre') }}"
        placeholder="Ej: Clásica Azul"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
      @error('nombre')
        <div style="color:#EF4444;font-size:0.78rem;margin-top:5px;">{{ $message }}</div>
      @enderror
    </div>

    {{-- Tipo de prenda --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Tipo de prenda
      </label>
      <select name="tipo_prenda"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
        <option value="camiseta"  {{ old('tipo_prenda') == 'camiseta'  ? 'selected' : '' }}>Camiseta</option>
        <option value="short"     {{ old('tipo_prenda') == 'short'     ? 'selected' : '' }}>Short</option>
        <option value="conjunto"  {{ old('tipo_prenda') == 'conjunto'  ? 'selected' : '' }}>Conjunto</option>
        <option value="otro"      {{ old('tipo_prenda') == 'otro'      ? 'selected' : '' }}>Otro</option>
      </select>
      @error('tipo_prenda')
        <div style="color:#EF4444;font-size:0.78rem;margin-top:5px;">{{ $message }}</div>
      @enderror
    </div>

    {{-- Imagen --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Imagen de la plantilla
      </label>
      <input type="file" name="imagen_preview" accept="image/*" onchange="previewImagen(event)"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.88rem;color:var(--text-2);background:var(--bg-2);outline:none;">
      @error('imagen_preview')
        <div style="color:#EF4444;font-size:0.78rem;margin-top:5px;">{{ $message }}</div>
      @enderror

      {{-- Preview --}}
      <div id="preview-wrap" style="display:none;margin-top:12px;">
        <img id="preview-img" src=""
          style="width:100%;max-height:220px;object-fit:contain;border-radius:10px;border:1px solid var(--border);">
      </div>
    </div>

    {{-- Activa --}}
    <div style="margin-bottom:24px;display:flex;align-items:center;gap:10px;">
      <input type="checkbox" name="activa" id="activa" value="1"
        {{ old('activa', '1') ? 'checked' : '' }}
        style="width:16px;height:16px;accent-color:var(--blue);cursor:pointer;">
      <label for="activa" style="font-size:0.88rem;color:var(--text-2);cursor:pointer;">
        Plantilla activa (visible para los clientes)
      </label>
    </div>

    {{-- Botones --}}
    <div style="display:flex;gap:10px;">
      <button type="submit" class="btn-primary">
        <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        Guardar plantilla
      </button>
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