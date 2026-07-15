@extends('Admin.panel_admin')

@section('titulo', 'Uniformes Escolares')
@section('page-title', 'Uniformes Escolares')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

<style>
    /* Estilos específicos para esta vista (mejoras visuales) */
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.8rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .admin-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--text-1);
        margin: 0;
        display: flex;
        align-items: baseline;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .admin-badge {
        background: var(--bg-3);
        color: var(--text-2);
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1px solid var(--border);
    }
    .btn-new {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--blue);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        font-weight: 500;
        font-size: 0.85rem;
        cursor: pointer;
        transition: opacity 0.15s;
        text-decoration: none;
    }
    .btn-new:hover {
        opacity: 0.9;
    }
    /* Grid de prendas */
    .plantillas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-top: 0.5rem;
    }
    .plantilla-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .plantilla-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--blue-border);
    }
    /* Contenedor de imagen cuadrado y centrado */
    .plantilla-image {
        position: relative;
        aspect-ratio: 1 / 1;
        background: var(--bg-3);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .plantilla-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.2s;
    }
    .plantilla-card:hover .plantilla-image img {
        transform: scale(1.02);
    }
    .plantilla-image .no-img {
        font-size: 48px;
        color: var(--text-3);
    }
    .badge-status {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
    .estado-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.7rem;
        display: inline-block;
        border: 1px solid transparent;
    }
    .est-activa {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }
    .est-inactiva {
        background: #ffe4e2;
        color: #b91c1c;
        border-color: #fecaca;
    }
    [data-theme="dark"] .est-activa {
        background: #14532d;
        color: #86efac;
        border-color: #166534;
    }
    [data-theme="dark"] .est-inactiva {
        background: #7f1d1d;
        color: #fecaca;
        border-color: #991b1b;
    }
    .plantilla-info {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .plantilla-nombre {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text-1);
        line-height: 1.3;
    }
    .plantilla-tipo {
        font-size: 0.75rem;
        color: var(--text-3);
        text-transform: capitalize;
    }
    .tallas-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .talla-chip {
        background: var(--blue-soft);
        border: 1px solid var(--blue-border);
        color: var(--blue);
        padding: 3px 9px;
        font-size: 0.72rem;
        font-weight: 600;
    }
    .talla-chip.no-disponible {
        opacity: 0.4;
        text-decoration: line-through;
    }
    .acciones {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    .btn-edit {
        flex: 1;
        background: var(--blue);
        color: white;
        border: none;
        padding: 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition: opacity 0.15s;
    }
    .btn-edit:hover {
        opacity: 0.9;
    }
    .btn-delete {
        background: transparent;
        border: 1px solid var(--border);
        padding: 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-2);
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-delete:hover {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }
    /* Empty state */
    .empty-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        padding: 3rem;
        text-align: center;
    }
    .empty-svg {
        width: 64px;
        height: 64px;
        stroke: var(--text-3);
        margin-bottom: 1rem;
    }
    @media (max-width: 640px) {
        .plantillas-grid {
            grid-template-columns: 1fr;
        }
        .admin-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

{{-- Mensaje de éxito --}}
@if(session('success'))
  <div class="badge-success" style="display:block;padding:0.75rem 1rem;margin-bottom:1.5rem;font-size:0.85rem;border-radius:8px;">
    {{ session('success') }}
  </div>
@endif

{{-- Header --}}
<div class="admin-header">
    <div class="admin-title">
        Uniformes Escolares
        <span class="admin-badge">{{ $uniformes->count() }} registrados</span>
    </div>
    <a href="{{ route('admin.uniformes.create') }}" class="btn-new">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nuevo uniforme
    </a>
</div>

{{-- Grid de uniformes --}}
@if($uniformes->isEmpty())
    <div class="empty-card">
        <div class="empty-svg">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="3" width="7" height="7"/>
                <rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/>
            </svg>
        </div>
        <p style="color: var(--text-2);">Aún no hay uniformes registrados. Crea el primero con el botón "Nuevo uniforme".</p>
    </div>
@else
    <div class="plantillas-grid">
        @foreach($uniformes as $uniforme)
            <div class="plantilla-card">
                <div class="plantilla-image">
                    @if($uniforme->imagen)
                        <img src="{{ asset('storage/'.$uniforme->imagen) }}"
                             alt="{{ $uniforme->nombre }}"
                             loading="lazy">
                    @else
                        <div class="no-img">
                            <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" fill="none" stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                    @endif
                    <div class="badge-status">
                        @if($uniforme->activo)
                            <span class="estado-badge est-activa">Activo</span>
                        @else
                            <span class="estado-badge est-inactiva">Inactivo</span>
                        @endif
                    </div>
                </div>
                <div class="plantilla-info">
                    <div class="plantilla-nombre">{{ $uniforme->nombre }}</div>
                    <div class="plantilla-tipo">{{ $uniforme->tipo_tela }}</div>
                    <div class="tallas-chips">
                        @foreach($uniforme->tallas as $talla)
                            <span class="talla-chip {{ !$talla->disponible ? 'no-disponible' : '' }}">
                                {{ $talla->talla }} — ${{ number_format($talla->precio, 2) }}
                            </span>
                        @endforeach
                    </div>
                    <div class="acciones">
                        <a href="{{ route('admin.uniformes.edit', $uniforme->id) }}" class="btn-edit">Editar</a>
                        <form action="{{ route('admin.uniformes.destroy', $uniforme->id) }}" method="POST"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este uniforme?');" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
