@extends('layouts.catalogo')

@section('titulo', 'Mis Pedidos')

@section('contenido')

<style>
    /* Estilos rectos, sin bordes redondeados excesivos */
    .pedidos-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .title-section {
        display: flex;
        align-items: baseline;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .page-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--text-1);
        margin: 0;
    }
    .badge-count {
        background: var(--bg-3);
        color: var(--text-2);
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1px solid var(--border);
    }
    .btn-square {
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
    .btn-square:hover {
        opacity: 0.9;
    }
    /* Cards de pedidos */
    .pedido-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .pedido-inner {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.25rem;
    }
    /* Imagen */
    .pedido-imagen {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        background: var(--bg-3);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
    }
    .pedido-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .pedido-imagen svg {
        width: 32px;
        height: 32px;
        stroke: var(--text-3);
    }
    /* Detalles principales */
    .pedido-detalles {
        flex: 2;
        min-width: 180px;
    }
    .pedido-codigo {
        font-family: monospace;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-1);
        margin-bottom: 0.25rem;
    }
    .pedido-nombre {
        font-weight: 600;
        color: var(--text-1);
        margin-bottom: 0.2rem;
    }
    .pedido-cantidad {
        font-size: 0.75rem;
        color: var(--text-3);
        margin-bottom: 0.5rem;
    }
    .pedido-fecha {
        font-size: 0.7rem;
        color: var(--text-3);
    }
    /* Estados */
    .estados-grid {
        flex: 3;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-start;
    }
    .estado-item {
        min-width: 100px;
    }
    .estado-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        font-weight: 600;
        color: var(--text-3);
        letter-spacing: 0.5px;
        margin-bottom: 0.3rem;
    }
    .estado-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        display: inline-block;
        border: 1px solid transparent;
    }
    /* Colores de estado (respetando variables) */
    .est-recibido { background: var(--blue-soft); color: var(--blue); border-color: var(--blue); }
    .est-produccion { background: #fef3c7; color: #92400e; border-color: #fde68a; } /* se mantienen tonos cálidos */
    .est-listo { background: #dbeafe; color: #1e40af; border-color: #bfdbfe; }
    .est-entregado { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .est-pendiente { background: #ffe4e2; color: #b91c1c; border-color: #fecaca; }
    /* Precios */
    .precios-box {
        flex: 2;
        text-align: right;
    }
    .precio-linea {
        font-size: 0.8rem;
        margin-bottom: 0.2rem;
        color: var(--text-2);
    }
    .precio-linea strong {
        color: var(--text-1);
    }
    .precio-adelanto strong {
        color: var(--blue);
    }
    /* Acciones */
    .acciones-box {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }
    .btn-outline-square {
        background: transparent;
        border: 1px solid var(--border);
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-1);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .btn-outline-square:hover {
        background: var(--bg-3);
        border-color: var(--text-3);
    }
    .info-pendiente {
        background: var(--bg-3);
        padding: 0.4rem 0.8rem;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-2);
        border: 1px solid var(--border);
    }
    /* Responsive */
    @media (max-width: 900px) {
        .pedido-inner {
            flex-direction: column;
        }
        .precios-box, .acciones-box {
            text-align: left;
            align-items: flex-start;
        }
        .estados-grid {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
    @media (max-width: 480px) {
        .header-actions {
            flex-direction: column;
            align-items: flex-start;
        }
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
</style>

<div class="pedidos-container">
    @if(session('success'))
        <div style="background:var(--success-bg, #ecfdf5); border-left:4px solid var(--success, #10b981); color:var(--success-text, #065f46); padding:0.75rem 1rem; margin-bottom:1.5rem; font-size:0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="header-actions">
        <div class="title-section">
            <h1 class="page-title">Mis pedidos</h1>
            <span class="badge-count">{{ $pedidos->count() }} en total</span>
        </div>
        <a href="{{ route('cliente.inicio') }}" class="btn-square">
            + Nuevo pedido
        </a>
    </div>

    @if($pedidos->isEmpty())
        <div class="empty-card">
            <div class="empty-svg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p style="color: var(--text-2);">Aún no tienes pedidos. ¡Explora el catálogo!</p>
        </div>
    @else
        @foreach($pedidos as $pedido)
            <div class="pedido-card">
                <div class="pedido-inner">
                    {{-- IMAGEN --}}
                    <div class="pedido-imagen">
                        @if(optional($pedido->disenio)->imagen_generada)
                            <img src="{{ asset('storage/'.$pedido->disenio->imagen_generada) }}"
                                 alt="{{ $pedido->disenio->nombre }}">
                        @elseif(optional($pedido->disenio->plantilla ?? null)->imagen_preview)
                            <img src="{{ asset('storage/'.$pedido->disenio->plantilla->imagen_preview) }}"
                                 alt="{{ $pedido->disenio->nombre }}">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        @endif
                    </div>

                    {{-- DETALLES PRINCIPALES --}}
                    <div class="pedido-detalles">
                        <div class="pedido-codigo">{{ $pedido->codigo }}</div>
                        <div class="pedido-nombre">{{ $pedido->disenio->nombre ?? '—' }}</div>
                        <div class="pedido-cantidad">{{ $pedido->cantidad_total }} unidades</div>
                        <div class="pedido-fecha">{{ $pedido->created_at->format('d M Y') }}</div>
                    </div>

                    {{-- ESTADOS (Pedido y Pago) --}}
                    <div class="estados-grid">
                        <div class="estado-item">
                            <div class="estado-label">Estado pedido</div>
                            @php
                                $estadoMap = [
                                    'recibido' => 'Recibido',
                                    'en_produccion' => 'En producción',
                                    'listo' => 'Listo',
                                    'enviado' => 'Enviado',
                                    'entregado' => 'Entregado',
                                    'cancelado' => 'Cancelado'
                                ];
                                $estadoClase = match($pedido->estado) {
                                    'recibido' => 'est-recibido',
                                    'en_produccion' => 'est-produccion',
                                    'listo' => 'est-listo',
                                    'enviado' => 'est-produccion',
                                    'entregado' => 'est-entregado',
                                    default => 'est-pendiente'
                                };
                            @endphp
                            <div class="estado-badge {{ $estadoClase }}">
                                {{ $estadoMap[$pedido->estado] ?? ucfirst($pedido->estado) }}
                            </div>
                        </div>
                        <div class="estado-item">
                            <div class="estado-label">Estado pago</div>
                            @php
                                $pagoMap = [
                                    'pendiente' => 'Sin comprobante',
                                    'adelanto_enviado' => 'Comprobante enviado',
                                    'adelanto_verificado' => 'Adelanto verificado',
                                    'pagado_completo' => 'Pagado completo'
                                ];
                                $pagoClase = match($pedido->estado_pago) {
                                    'pendiente' => 'est-pendiente',
                                    'adelanto_enviado' => 'est-produccion',
                                    'adelanto_verificado' => 'est-recibido',
                                    'pagado_completo' => 'est-entregado',
                                    default => 'est-pendiente'
                                };
                            @endphp
                            <div class="estado-badge {{ $pagoClase }}">
                                {{ $pagoMap[$pedido->estado_pago] ?? ucfirst($pedido->estado_pago) }}
                            </div>
                        </div>
                    </div>

                    {{-- PRECIOS --}}
                    <div class="precios-box">
                        <div class="precio-linea">Total: <strong>${{ number_format($pedido->precio_total, 2) }}</strong></div>
                        <div class="precio-linea precio-adelanto">Adelanto: <strong>${{ number_format($pedido->precio_adelanto, 2) }}</strong></div>
                        <div class="precio-linea">Saldo: <strong>${{ number_format($pedido->precio_saldo, 2) }}</strong></div>
                    </div>

                    {{-- ACCIONES --}}
                    <div class="acciones-box">
                        @if($pedido->estado_pago == 'pendiente')
                            <a href="{{ route('cliente.pedidos.comprobante', $pedido->id) }}" class="btn-outline-square">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0-3-3m3 3 3-3M5 21h14"/></svg>
                                Subir comprobante
                            </a>
                        @elseif($pedido->estado_pago == 'adelanto_enviado')
                            <div class="info-pendiente">
                                Verificando...
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

@endsection