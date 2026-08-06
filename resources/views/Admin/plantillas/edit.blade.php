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
        Nombre de la prenda
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

    {{-- Tela --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Tipo de tela
      </label>
      <input type="text" name="tela" value="{{ old('tela', $plantilla->tela) }}"
        placeholder="Ej: Algodón, Poliéster, Licra, Piqué"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
      @error('tela')<div style="color:#EF4444;font-size:0.78rem;margin-top:5px;">{{ $message }}</div>@enderror
    </div>

    {{-- Tipo de prenda --}}
    <div style="margin-bottom:18px;">
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

    {{-- Género --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Para quién es
      </label>
      @php $generoActual = old('genero', $plantilla->genero); @endphp
      <select name="genero"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
        <option value="unisex" {{ $generoActual === 'unisex' ? 'selected' : '' }}>Unisex</option>
        <option value="hombre" {{ $generoActual === 'hombre' ? 'selected' : '' }}>Para Hombre</option>
        <option value="mujer" {{ $generoActual === 'mujer' ? 'selected' : '' }}>Para Mujer</option>
      </select>
      @error('genero')<div style="color:#EF4444;font-size:0.78rem;margin-top:5px;">{{ $message }}</div>@enderror
    </div>

    {{-- Tallas y precios --}}
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
      <div style="font-size:0.75rem;color:var(--text-3);margin-top:6px;">
        Si eliminas una talla que ya tiene pedidos asociados, se marcará como no disponible en lugar de eliminarse.
      </div>
      @error('tallas')<div style="color:#EF4444;font-size:0.78rem;margin-top:5px;">{{ $message }}</div>@enderror
      @foreach($errors->keys() as $campoError)
        @if(str_starts_with($campoError, 'tallas.'))
          <div style="color:#EF4444;font-size:0.78rem;margin-top:5px;">{{ $errors->first($campoError) }}</div>
        @endif
      @endforeach
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

    {{-- Destacado --}}
    <div style="margin-bottom:24px;display:flex;align-items:center;gap:10px;">
      <input type="checkbox" name="destacado" id="destacado" value="1"
        {{ $plantilla->destacado ? 'checked' : '' }}
        style="width:16px;height:16px;accent-color:var(--blue);cursor:pointer;">
      <label for="destacado" style="font-size:0.88rem;color:var(--text-2);cursor:pointer;">
        Destacar en la página de inicio
      </label>
    </div>

    <div style="display:flex;gap:10px;">
      <button type="submit" class="btn-primary">Actualizar prenda</button>
      <a href="{{ route('admin.plantillas.index') }}" class="btn-secondary">Cancelar</a>
    </div>

  </form>
</div>

<script>
let contadorTallas = 0;

function agregarFilaTalla(talla = '', precio = '') {
  const contenedor = document.getElementById('contenedor-tallas');
  const fila = document.createElement('div');
  fila.style.cssText = 'display:flex;gap:10px;align-items:center;margin-bottom:8px;';
  fila.innerHTML = `
    <input type="text" name="tallas[${contadorTallas}][talla]" value="${talla}" placeholder="Talla"
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

// Pre-cargar tallas existentes
window.addEventListener('DOMContentLoaded', () => {
  @foreach($plantilla->tallas as $t)
    agregarFilaTalla('{{ $t->talla }}', '{{ $t->precio }}');
  @endforeach
});
</script>

@endsection
