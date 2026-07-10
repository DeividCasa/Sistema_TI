@extends('Plantilla/Plantilla')

@section('titulo', 'Pedir cotización')
@section('page-title', 'Pedir cotización')

@section('topbar')
    @include('cliente.componentes.topbar-cliente')
@endsection

@section('contenido')

<style>
    .cotizar-container { max-width: 1150px; margin: 0 auto; }
    .cotizar-grid {
        display: grid;
        grid-template-columns: minmax(380px, 1fr) 1.1fr;
        gap: 1.5rem;
        align-items: stretch;
    }
    @media (max-width: 900px) {
        .cotizar-grid { grid-template-columns: 1fr; }
    }
    .preview-card, .form-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .preview-card { display: flex; flex-direction: column; }
    .preview-imagenes { display: flex; flex: 1; }
    .preview-imagen-col { flex: 1; display: flex; flex-direction: column; }
    .preview-imagen-col + .preview-imagen-col { border-left: 1px solid var(--border); }
    .preview-imagen {
        flex: 1;
        min-height: 420px;
        background: var(--bg-3);
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
    }
    .preview-imagen img { width: 100%; height: 100%; object-fit: contain; cursor: zoom-in; transition: transform var(--tr); }
    .preview-imagen img:hover { transform: scale(1.04); }
    .preview-imagen svg { width: 40px; height: 40px; stroke: var(--text-3); }
    .preview-imagen-label {
        text-align: center; font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.04em;
        color: var(--text-3); background: var(--bg-3); padding: 8px 0;
        border-top: 1px solid var(--border);
    }
    .preview-nombre {
        padding: 14px 16px; font-family: var(--font-d); font-weight: 700;
        font-size: 0.95rem; color: var(--text-1);
    }
    .form-card { padding: 1.5rem 1.75rem; }
    .talla-fila { display: flex; gap: 0.75rem; margin-bottom: 0.6rem; align-items: center; }
    .talla-fila input { flex: 1; }
    .campo-label {
        display: block; font-size: 0.78rem; font-weight: 600; color: var(--text-2);
        text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 8px;
    }
    .campo-input, .campo-select, .campo-textarea {
        width: 100%; padding: 10px 12px; border: 1.5px solid var(--border);
        border-radius: 9px; background: var(--bg-2); color: var(--text-1);
        font-family: var(--font-b); font-size: 0.9rem; transition: border-color var(--tr);
    }
    .campo-input:focus, .campo-select:focus, .campo-textarea:focus {
        outline: none; border-color: var(--blue-border);
    }
    .btn-quitar-talla {
        background: transparent; border: 1px solid var(--border); color: var(--text-2);
        width: 38px; height: 38px; cursor: pointer; border-radius: 9px; transition: all var(--tr);
    }
    .btn-quitar-talla:hover { border-color: #fecaca; color: #b91c1c; background: #ffe4e2; }

    .lightbox-overlay {
        display: none; position: fixed; inset: 0; background: rgba(15,23,42,.75);
        align-items: center; justify-content: center; z-index: 1000; padding: 30px;
    }
    .lightbox-overlay.visible { display: flex; }
    .lightbox-overlay img {
        max-width: min(90vw, 700px); max-height: 85vh; object-fit: contain;
        background: var(--bg-2); border-radius: 8px;
    }
    .lightbox-cerrar {
        position: absolute; top: 20px; right: 30px; background: transparent;
        border: none; color: #fff; font-size: 2rem; line-height: 1; cursor: pointer;
    }
</style>

<div class="cotizar-container">
    <div class="sec-header">
        <div class="sec-title">Pedir cotización — {{ $disenio->nombre }}</div>
        <a href="{{ route('cliente.disenios.index') }}" class="btn-secondary">← Mis diseños</a>
    </div>

    <div class="cotizar-grid">
        {{-- COLUMNA IZQUIERDA: PREVIEW DEL DISEÑO --}}
        <div class="preview-card">
            <div class="preview-imagenes">
                <div class="preview-imagen-col">
                    <div class="preview-imagen">
                        @if($disenio->imagen_generada)
                            <img src="{{ asset('storage/'.$disenio->imagen_generada) }}" alt="{{ $disenio->nombre }} - frente"
                                 onclick="abrirLightbox('{{ asset('storage/'.$disenio->imagen_generada) }}')">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                            </svg>
                        @endif
                    </div>
                    <div class="preview-imagen-label">Frente</div>
                </div>
                <div class="preview-imagen-col">
                    <div class="preview-imagen">
                        @if($disenio->imagen_atras)
                            <img src="{{ asset('storage/'.$disenio->imagen_atras) }}" alt="{{ $disenio->nombre }} - atrás"
                                 onclick="abrirLightbox('{{ asset('storage/'.$disenio->imagen_atras) }}')">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                            </svg>
                        @endif
                    </div>
                    <div class="preview-imagen-label">Atrás</div>
                </div>
            </div>
            <div class="preview-nombre">{{ $disenio->nombre }}</div>
        </div>

        {{-- COLUMNA DERECHA: FORMULARIO --}}
        <div class="form-card">
            <form action="{{ route('solicitudes.store', $disenio->id) }}" method="POST">
                @csrf

                <div style="margin-bottom:20px;">
                    <label class="campo-label">Tela</label>
                    <select name="tela" class="campo-select" required>
                        @foreach($telas as $tela)
                            <option value="{{ $tela }}">{{ $tela }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom:20px;">
                    <label class="campo-label">Tallas y cantidades</label>
                    <div id="tallas-filas">
                        <div class="talla-fila">
                            <input type="text" name="tallas[0][talla]" class="campo-input" placeholder="Talla (ej. M)" required>
                            <input type="number" name="tallas[0][cantidad]" class="campo-input" placeholder="Cantidad" min="1" value="1" required>
                        </div>
                    </div>
                    <button type="button" class="btn-secondary" onclick="agregarFilaTalla()">+ Agregar talla</button>
                </div>

                <div style="margin-bottom:24px;">
                    <label class="campo-label">Descripción (opcional)</label>
                    <textarea name="descripcion" class="campo-textarea" rows="4" maxlength="1000"
                        placeholder="Detalles adicionales para tu pedido..."></textarea>
                </div>

                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                    Enviar solicitud de cotización
                </button>
            </form>
        </div>
    </div>
</div>

<div class="lightbox-overlay" id="lightbox-overlay" onclick="cerrarLightbox(event)">
    <button type="button" class="lightbox-cerrar" onclick="cerrarLightbox(event)">&times;</button>
    <img id="lightbox-img" src="" alt="Vista ampliada del diseño">
</div>

@endsection

@push('scripts')
<script>
    let contadorTallas = 1;
    function agregarFilaTalla() {
        const cont = document.getElementById('tallas-filas');
        const fila = document.createElement('div');
        fila.className = 'talla-fila';
        fila.innerHTML = `
            <input type="text" name="tallas[${contadorTallas}][talla]" class="campo-input" placeholder="Talla (ej. M)" required>
            <input type="number" name="tallas[${contadorTallas}][cantidad]" class="campo-input" placeholder="Cantidad" min="1" value="1" required>
            <button type="button" class="btn-quitar-talla" onclick="this.parentElement.remove()">×</button>
        `;
        cont.appendChild(fila);
        contadorTallas++;
    }
    function abrirLightbox(url) {
        document.getElementById('lightbox-img').src = url;
        document.getElementById('lightbox-overlay').classList.add('visible');
    }
    function cerrarLightbox(e) {
        if (e.target.id === 'lightbox-img') return;
        document.getElementById('lightbox-overlay').classList.remove('visible');
    }
</script>
@endpush
