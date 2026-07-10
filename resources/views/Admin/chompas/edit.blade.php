@extends('Admin.panel_admin')

@section('titulo', 'Editar Chompa')
@section('page-title', 'Editar Chompa')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

@if($errors->any())
  <div style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    @foreach($errors->all() as $error)
      <div>⚠ {{ $error }}</div>
    @endforeach
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Editar: {{ $chompa->nombre }}</div>
  <a href="{{ route('admin.chompas.index') }}" class="btn-secondary">← Volver</a>
</div>

<div class="card card-pad reveal" style="max-width:750px;">
  <form action="{{ route('admin.chompas.update', $chompa->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Nombre --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Nombre de la chompa
      </label>
      <input type="text" name="nombre" value="{{ old('nombre', $chompa->nombre) }}"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
    </div>

    {{-- Tipo de tela --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Tipo de tela
      </label>
      <input type="text" name="tipo_tela" value="{{ old('tipo_tela', $chompa->tipo_tela) }}"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;">
    </div>

    {{-- Descripción --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Descripción
      </label>
      <textarea name="descripcion" rows="3"
        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
        font-family:var(--font-b);font-size:0.93rem;color:var(--text-1);background:var(--bg-2);outline:none;resize:vertical;">{{ old('descripcion', $chompa->descripcion) }}</textarea>
    </div>

    {{-- Foto actual + nueva --}}
    <div style="margin-bottom:18px;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Foto actual
      </label>
      <img src="{{ asset('storage/' . $chompa->imagen) }}" alt="{{ $chompa->nombre }}"
           style="width:120px;height:120px;object-fit:cover;border-radius:10px;border:1px solid var(--border);margin-bottom:10px;display:block;">
      <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        Cambiar foto (dejar vacío para mantener la actual)
      </label>
      <input type="file" name="imagen" accept="image/*"
        style="width:100%;padding:11px 14px;border:1.5px dashed var(--border-2);border-radius:10px;
        font-family:var(--font-b);font-size:0.88rem;color:var(--text-2);background:var(--bg-3);">
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
    </div>

    {{-- Activo --}}
    <div style="margin-bottom:24px;display:flex;align-items:center;gap:8px;">
      <input type="checkbox" name="activo" id="activo" {{ $chompa->activo ? 'checked' : '' }} style="width:16px;height:16px;">
      <label for="activo" style="font-size:0.88rem;color:var(--text-2);">Chompa visible para los clientes</label>
    </div>

    <button type="submit" class="btn-primary" style="width:100%;padding:13px;font-size:0.95rem;">
      Guardar cambios
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
  @foreach($chompa->tallas as $t)
    agregarFilaTalla('{{ $t->talla }}', '{{ $t->precio }}');
  @endforeach
});
</script>

@endsection
