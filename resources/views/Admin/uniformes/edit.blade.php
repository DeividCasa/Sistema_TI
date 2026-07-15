@extends('Admin.panel_admin')

@section('titulo', 'Editar Uniforme')
@section('page-title', 'Editar Uniforme Escolar')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

@if($errors->any())
  <div style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    @foreach($errors->all() as $error)
      <div>{{ $error }}</div>
    @endforeach
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Editar: {{ $uniforme->nombre }}</div>
  <a href="{{ route('admin.uniformes.index') }}" class="btn-secondary">← Volver</a>
</div>

<div class="card card-pad reveal" style="max-width:750px;">
  <form action="{{ route('admin.uniformes.update', $uniforme->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Nombre del uniforme
      </label>
      <input type="text" name="nombre" value="{{ old('nombre', $uniforme->nombre) }}"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
    </div>

    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Tipo de tela
      </label>
      <input type="text" name="tipo_tela" value="{{ old('tipo_tela', $uniforme->tipo_tela) }}"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
    </div>

    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Descripción
      </label>
      <textarea name="descripcion" rows="3"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;resize:vertical;">{{ old('descripcion', $uniforme->descripcion) }}</textarea>
    </div>

    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Foto actual
      </label>
      <img src="{{ asset('storage/' . $uniforme->imagen) }}" alt="{{ $uniforme->nombre }}"
           style="width:120px;height:120px;object-fit:cover;border-radius:10px;border:1px solid var(--border);margin-bottom:10px;display:block;">
      <label for="imagen-uniforme" id="drop-area-uniforme" style="display:flex;flex-direction:column;align-items:center;justify-content:center;
        gap:8px;padding:22px 16px;border:1.5px dashed var(--border-2);border-radius:12px;
        background:var(--bg-3);cursor:pointer;transition:all var(--tr);text-align:center;">
        <svg viewBox="0 0 24 24" style="width:26px;height:26px;stroke:var(--blue);fill:none;stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round;">
          <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
        </svg>
        <span style="font-weight:600;font-size:0.86rem;color:var(--text-1);">Haz clic para seleccionar una imagen</span>
        <span style="font-size:0.74rem;color:var(--text-3);">JPG, PNG o WEBP — máximo 2MB</span>
        <input type="file" id="imagen-uniforme" name="imagen" accept="image/*"
          onchange="previsualizarArchivo(this, 'preview-imagen-uniforme', 'drop-area-uniforme')" style="display:none;">
      </label>
      <div id="preview-imagen-uniforme" style="display:none;margin-top:10px;"></div>
      <div style="font-size:0.75rem;color:var(--text-3);margin-top:5px;">Sube una nueva foto solo si deseas reemplazar la actual.</div>
    </div>

    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Tallas y precios
      </label>
      <div id="contenedor-tallas"></div>
      <button type="button" onclick="agregarFilaTalla()"
        style="margin-top:8px;background:var(--blue-soft);border:1px solid var(--blue-border);color:var(--blue);
        padding:8px 16px;border-radius:8px;font-size:0.82rem;font-weight:600;cursor:pointer;">
        + Agregar talla
      </button>
    </div>

    <div style="margin-bottom:24px;display:flex;align-items:center;gap:8px;">
      <input type="checkbox" name="activo" id="activo" {{ $uniforme->activo ? 'checked' : '' }} style="width:16px;height:16px;">
      <label for="activo" style="font-size:0.88rem;color:var(--text-2);">Uniforme visible para los clientes</label>
    </div>

    <button type="submit" class="btn-primary" style="width:100%;padding:13px;font-size:0.95rem;">
      Actualizar uniforme
    </button>
  </form>
</div>

<script>
let contadorTallas = 0;

function agregarFilaTalla(talla = '', precio = '') {
  const contenedor = document.getElementById('contenedor-tallas');
  const fila = document.createElement('div');
  fila.style.cssText = 'display:flex;gap:10px;align-items:center;margin-bottom:8px;';
  fila.innerHTML = `
    <input type="text" name="tallas[${contadorTallas}][talla]" value="${talla}" placeholder="Talla (ej: 32)"
      style="flex:1;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);outline:none;">
    <div style="flex:1;display:flex;align-items:center;gap:6px;">
      <span style="color:var(--text-3);font-weight:600;">$</span>
      <input type="number" name="tallas[${contadorTallas}][precio]" value="${precio}" placeholder="Precio" step="0.01" min="0.01"
        style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);outline:none;">
    </div>
    <button type="button" onclick="this.parentElement.remove()"
      style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;width:36px;height:36px;border-radius:8px;font-weight:700;cursor:pointer;flex-shrink:0;">✕</button>
  `;
  contenedor.appendChild(fila);
  contadorTallas++;
}

window.addEventListener('DOMContentLoaded', () => {
  const tallasExistentes = @json($uniforme->tallas->where('disponible', 1)->map(fn($t) => ['talla' => $t->talla, 'precio' => $t->precio])->values());
  if (tallasExistentes.length > 0) {
    tallasExistentes.forEach(t => agregarFilaTalla(t.talla, t.precio));
  } else {
    agregarFilaTalla();
  }
});
</script>

@endsection
