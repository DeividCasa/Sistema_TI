@extends('layouts.catalogo')

@section('titulo', 'Mis Diseños')

@section('contenido')

<style>
    .disenios-container { max-width: 1400px; margin: 0 auto; }
    .disenios-header {
        display: flex; align-items: baseline; gap: 0.75rem;
        margin-bottom: 1.5rem; flex-wrap: wrap;
    }
    .disenios-titulo {
        font-family: var(--font-d); font-size: 1.6rem; font-weight: 800;
        color: var(--text-1); margin: 0;
    }
    .disenios-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 1.5rem;
    }
    .disenio-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: box-shadow var(--tr), transform var(--tr);
    }
    .disenio-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .disenio-imagenes { width: 100%; display: flex; }
    .disenio-imagen-col { flex: 1; display: flex; flex-direction: column; }
    .disenio-imagen-col + .disenio-imagen-col { border-left: 1px solid var(--border); }
    .disenio-imagen {
        height: 190px;
        background: var(--bg-3);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .disenio-imagen img { width: 100%; height: 100%; object-fit: contain; cursor: zoom-in; transition: transform var(--tr); }
    .disenio-imagen img:hover { transform: scale(1.04); }
    .disenio-imagen svg { width: 32px; height: 32px; stroke: var(--text-3); }
    .disenio-imagen-label {
        text-align: center; font-size: 0.66rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.04em;
        color: var(--text-3); background: var(--bg-3); padding: 6px 0;
        border-top: 1px solid var(--border);
    }

    /* Lightbox para ver la foto en grande sin abrir otra pestaña */
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
    .disenio-body { padding: 1.1rem 1.2rem 1.2rem; }
    .disenio-nombre-row {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
        margin-bottom: 0.15rem;
    }
    .disenio-nombre {
        font-family: var(--font-d); font-weight: 700; font-size: 1rem;
        color: var(--text-1);
    }
    .disenio-tipo-tag {
        font-size: 0.66rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em;
        padding: 0.2rem 0.55rem; border-radius: 20px;
        background: var(--blue-soft); color: var(--blue); border: 1px solid var(--blue-border);
    }
    .disenio-fecha { font-size: 0.72rem; color: var(--text-3); margin-bottom: 0.85rem; }
    .disenio-estado {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
        border: 1px solid transparent;
        margin-bottom: 0.85rem;
    }
    .disenio-estado::before { content:''; width:6px; height:6px; border-radius:50%; }
    .est-sin-solicitud { background: var(--bg-3); color: var(--text-3); border-color: var(--border); }
    .est-sin-solicitud::before { background: var(--text-3); }
    .est-pendiente { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .est-pendiente::before { background: #f59e0b; }
    .est-cotizado { background: var(--blue-soft); color: var(--blue); border-color: var(--blue-border); }
    .est-cotizado::before { background: var(--blue); }
    .est-aceptado { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .est-aceptado::before { background: #22c55e; }
    .est-rechazado { background: #ffe4e2; color: #b91c1c; border-color: #fecaca; }
    .est-rechazado::before { background: #ef4444; }
    .cotizacion-box {
        background: var(--bg-3);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0.85rem 0.9rem;
        margin-bottom: 0.9rem;
        font-size: 0.82rem;
        color: var(--text-2);
        line-height: 1.5;
    }
    .cotizacion-precio {
        font-family: var(--font-d); font-size: 1.3rem; font-weight: 800;
        color: var(--text-1); margin-bottom: 0.35rem;
    }
    .disenio-acciones { display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .disenio-acciones form { display: contents; }
    .disenio-acciones .btn-primary,
    .disenio-acciones .btn-secondary {
        font-size: 0.82rem; padding: 8px 16px;
    }
    .btn-eliminar {
        display: inline-flex; align-items: center; gap: 6px;
        background: transparent; border: 1px solid #fecaca; color: #b91c1c;
        border-radius: 9px; padding: 8px 16px; font-size: 0.82rem;
        font-family: var(--font-b); font-weight: 600; cursor: pointer;
        transition: all var(--tr);
    }
    .btn-eliminar:hover { background: #ffe4e2; border-color: #ef4444; }
    .empty-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        padding: 4rem 2rem;
        text-align: center;
    }
    .empty-card svg { width: 56px; height: 56px; stroke: var(--text-3); margin-bottom: 1rem; }
</style>

<div class="disenios-container">
    @if(session('success'))
        <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#FFE4E2;border:1px solid #FECACA;color:#B91C1C;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
            {{ session('error') }}
        </div>
    @endif

    <div class="disenios-header">
        <h1 class="disenios-titulo">Mis diseños</h1>
        <span class="sec-badge">{{ $disenios->count() }} en total</span>
    </div>

    @if($disenios->isEmpty())
        <div class="empty-card">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
            </svg>
            <p style="color: var(--text-2);">Aún no has guardado ningún diseño. ¡Ve al editor y crea el tuyo!</p>
        </div>
    @else
        <div class="disenios-grid">
            @foreach($disenios as $disenio)
                @php
                    $solicitud = $disenio->solicitudes->first();
                    $tipoPrenda = $disenio->configuracion['tipo_prenda'] ?? null;

                    $esConjuntoCompleto = ($tipoPrenda === 'camiseta'
                            && !empty($disenio->configuracion['pantaloneta_activa'])
                            && !empty($disenio->configuracion['medias_activas']))
                        || ($tipoPrenda === 'chompa'
                            && !empty($disenio->configuracion['pantalon_chompa_activo']));

                    $etiquetaTipo = $esConjuntoCompleto ? 'Conjunto completo' : ($tipoPrenda ? ucfirst($tipoPrenda) : null);
                @endphp
                <div class="disenio-card">
                    <div class="disenio-imagenes">
                        <div class="disenio-imagen-col">
                            <div class="disenio-imagen">
                                @if($disenio->imagen_generada)
                                    <img src="{{ asset('storage/'.$disenio->imagen_generada) }}" alt="{{ $disenio->nombre }} - frente"
                                         onclick="abrirLightbox('{{ asset('storage/'.$disenio->imagen_generada) }}')">
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="disenio-imagen-label">Frente</div>
                        </div>
                        <div class="disenio-imagen-col">
                            <div class="disenio-imagen">
                                @if($disenio->imagen_atras)
                                    <img src="{{ asset('storage/'.$disenio->imagen_atras) }}" alt="{{ $disenio->nombre }} - atrás"
                                         onclick="abrirLightbox('{{ asset('storage/'.$disenio->imagen_atras) }}')">
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="disenio-imagen-label">Atrás</div>
                        </div>
                    </div>
                    <div class="disenio-body">
                        <div class="disenio-nombre-row">
                            <div class="disenio-nombre">{{ $disenio->nombre }}</div>
                            @if($etiquetaTipo)
                                <span class="disenio-tipo-tag">{{ $etiquetaTipo }}</span>
                            @endif
                        </div>
                        <div class="disenio-fecha">{{ $disenio->created_at->format('d M Y') }}</div>

                        @if(!$solicitud)
                            <div class="disenio-estado est-sin-solicitud">Sin cotización</div>
                            <div class="disenio-acciones">
                                <a href="{{ route('solicitudes.create', $disenio->id) }}" class="btn-primary">Pedir cotización</a>
                            </div>
                        @elseif($solicitud->estado === 'pendiente')
                            <div class="disenio-estado est-pendiente">Esperando cotización</div>
                        @elseif($solicitud->estado === 'cotizado')
                            <div class="disenio-estado est-cotizado">Cotización recibida</div>
                            <div class="cotizacion-box">
                                <div class="cotizacion-precio">${{ number_format($solicitud->precio, 2) }}</div>
                                @if($solicitud->mensaje_admin)
                                    <div>{{ $solicitud->mensaje_admin }}</div>
                                @endif
                            </div>
                            <div class="disenio-acciones">
                                <form action="{{ route('solicitudes.aceptar', $solicitud->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-primary">Aceptar</button>
                                </form>
                                <form action="{{ route('solicitudes.rechazar', $solicitud->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-secondary">Rechazar</button>
                                </form>
                            </div>
                        @elseif($solicitud->estado === 'aceptado')
                            <div class="disenio-estado est-aceptado">Aceptado</div>
                            <div class="disenio-acciones">
                                <a href="{{ route('cliente.pedidos.comprobante', $solicitud->pedido_id) }}" class="btn-secondary">Ver pedido</a>
                            </div>
                        @else
                            <div class="disenio-estado est-rechazado">Rechazado</div>
                        @endif

                        @if(!$solicitud || $solicitud->estado !== 'aceptado')
                            <form action="{{ route('disenios.destroy', $disenio->id) }}" method="POST"
                                  style="margin-top:0.7rem;" onsubmit="return confirm('¿Eliminar este diseño? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-eliminar">Eliminar</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="lightbox-overlay" id="lightbox-overlay" onclick="cerrarLightbox(event)">
    <button type="button" class="lightbox-cerrar" onclick="cerrarLightbox(event)">&times;</button>
    <img id="lightbox-img" src="" alt="Vista ampliada del diseño">
</div>

@endsection

@push('scripts')
<script>
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
