@extends('layouts.catalogo')

@section('titulo', 'Mis pedidos')

@section('contenido')

<style>
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
        border-radius: 6px;
    }
    .btn-square {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--blue);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.1rem;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: opacity 0.15s;
        text-decoration: none;
    }
    .btn-square:hover { opacity: 0.9; }

    .pedido-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: box-shadow 0.15s;
    }
    .pedido-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }

    .pedido-tipo-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 0.2rem 0.6rem;
        border-radius: 5px;
        background: var(--blue-soft);
        color: var(--blue);
    }
    .tag-combinado { background: #f3e8ff; color: #7e22ce; }

    .pedido-inner {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        padding: 1.25rem;
    }

    .pedido-imagenes {
        display: flex;
        gap: 0.4rem;
        flex-shrink: 0;
    }
    .pedido-imagen {
        width: 72px;
        height: 72px;
        flex-shrink: 0;
        background: var(--bg-3);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
        overflow: hidden;
    }
    .pedido-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .pedido-imagen svg {
        width: 28px;
        height: 28px;
        stroke: var(--text-3);
    }

    .pedido-detalles {
        flex: 2;
        min-width: 220px;
    }
    .pedido-codigo-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.4rem;
        flex-wrap: wrap;
    }
    .pedido-codigo {
        font-family: monospace;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-1);
    }
    .pedido-item-linea {
        font-size: 0.82rem;
        color: var(--text-2);
        margin-bottom: 0.15rem;
    }
    .pedido-item-linea strong { color: var(--text-1); }
    .pedido-fecha {
        font-size: 0.7rem;
        color: var(--text-3);
        margin-top: 0.4rem;
    }

    .estados-grid {
        flex: 2;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-start;
    }
    .estado-item { min-width: 100px; }
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
        border-radius: 6px;
        display: inline-block;
        border: 1px solid transparent;
        margin-bottom: 0.25rem;
    }
    .est-recibido { background: var(--blue-soft); color: var(--blue); border-color: var(--blue); }
    .est-produccion { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .est-listo { background: #dbeafe; color: #1e40af; border-color: #bfdbfe; }
    .est-entregado { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .est-pendiente { background: #ffe4e2; color: #b91c1c; border-color: #fecaca; }

    .precios-box {
        flex: 1.3;
        min-width: 140px;
        text-align: right;
    }
    .precio-linea {
        font-size: 0.8rem;
        margin-bottom: 0.2rem;
        color: var(--text-2);
    }
    .precio-linea strong { color: var(--text-1); }
    .precio-adelanto strong { color: var(--blue); }

    .acciones-box {
        flex: 1;
        min-width: 140px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
        justify-content: center;
    }
    .btn-outline-square {
        background: transparent;
        border: 1.5px solid var(--blue);
        border-radius: 8px;
        padding: 0.5rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--blue);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }
    .btn-outline-square:hover { background: var(--blue-soft); }
    .info-pendiente {
        background: var(--bg-3);
        padding: 0.4rem 0.8rem;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-2);
        border: 1px solid var(--border);
        border-radius: 6px;
    }

    @media (max-width: 900px) {
        .pedido-inner { flex-direction: column; }
        .precios-box, .acciones-box { text-align: left; align-items: flex-start; }
        .estados-grid { gap: 0.6rem; }
    }
    @media (max-width: 480px) {
        .header-actions { flex-direction: column; align-items: flex-start; }
    }

    .empty-card {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
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
        <div style="background:var(--success-bg, #ecfdf5); border-left:4px solid var(--success, #10b981); color:var(--success-text, #065f46); padding:0.75rem 1rem; margin-bottom:1.5rem; font-size:0.85rem; border-radius:6px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="header-actions">
        <div class="title-section">
            <h1 class="page-title">Mis pedidos</h1>
            <span class="badge-count">{{ $pedidos->count() }} en total</span>
        </div>
        <a href="{{ route('cliente.uniformes.index') }}" class="btn-square">+ Nuevo pedido</a>
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
        @foreach($pedidos as $entrada)
            @php
                $pedido = $entrada['pedido'];
                $tipo   = $entrada['tipo'];

                $etiquetaTipo = match($tipo) {
                    'maestro'  => 'Pedido combinado',
                    'uniforme' => 'Uniforme',
                    'chompa'   => 'Chompa',
                    'ropa'     => 'Ropa',
                    'camiseta' => 'Camiseta personalizada',
                };

                // Líneas de producto e imágenes según el tipo de pedido
                $lineas = [];
                $imagenes = [];

                if ($tipo === 'maestro') {
                    if ($pedido->pedidoPlantilla) {
                        foreach ($pedido->pedidoPlantilla->items as $item) {
                            $detalle = ($item->talla ? 'Talla '.$item->talla.' × ' : '').$item->cantidad;
                            $lineas[] = ['nombre' => $item->plantilla->nombre ?? 'Producto', 'detalle' => $detalle, 'subtotal' => $item->subtotal];
                            if (optional($item->plantilla)->imagen_preview) $imagenes[] = $item->plantilla->imagen_preview;
                        }
                    }
                    if ($pedido->pedidoUniforme) {
                        foreach ($pedido->pedidoUniforme->items as $item) {
                            $lineas[] = ['nombre' => $item->uniforme->nombre, 'detalle' => 'Talla '.$item->talla.' × '.$item->cantidad, 'subtotal' => $item->subtotal];
                            $imagenes[] = $item->uniforme->imagen;
                        }
                    }
                    if ($pedido->pedidoChompa) {
                        foreach ($pedido->pedidoChompa->items as $item) {
                            $lineas[] = ['nombre' => $item->chompa->nombre, 'detalle' => 'Talla '.$item->talla.' × '.$item->cantidad, 'subtotal' => $item->subtotal];
                            $imagenes[] = $item->chompa->imagen;
                        }
                    }
                } elseif ($tipo === 'uniforme') {
                    foreach ($pedido->items as $item) {
                        $lineas[] = ['nombre' => $item->uniforme->nombre, 'detalle' => 'Talla '.$item->talla.' × '.$item->cantidad, 'subtotal' => $item->subtotal];
                        $imagenes[] = $item->uniforme->imagen;
                    }
                } elseif ($tipo === 'chompa') {
                    foreach ($pedido->items as $item) {
                        $lineas[] = ['nombre' => $item->chompa->nombre, 'detalle' => 'Talla '.$item->talla.' × '.$item->cantidad, 'subtotal' => $item->subtotal];
                        $imagenes[] = $item->chompa->imagen;
                    }
                } elseif ($tipo === 'ropa') {
                    foreach ($pedido->items as $item) {
                        $detalle = ($item->talla ? 'Talla '.$item->talla.' × ' : '').$item->cantidad;
                        $lineas[] = ['nombre' => $item->plantilla->nombre ?? 'Producto', 'detalle' => $detalle, 'subtotal' => $item->subtotal];
                        if (optional($item->plantilla)->imagen_preview) $imagenes[] = $item->plantilla->imagen_preview;
                    }
                } else { // camiseta
                    $lineas[] = ['nombre' => $pedido->disenio->nombre ?? 'Diseño personalizado', 'detalle' => $pedido->cantidad_total.' unidad(es)', 'subtotal' => null];
                    if (optional($pedido->disenio)->imagen_generada) {
                        $imagenes[] = $pedido->disenio->imagen_generada;
                    } elseif (optional($pedido->disenio->plantilla ?? null)->imagen_preview) {
                        $imagenes[] = $pedido->disenio->plantilla->imagen_preview;
                    }
                }
                $imagenes = array_slice(array_filter($imagenes), 0, 2);

                // Estado(s) de producción a mostrar
                $estadoMap = [
                    'recibido' => 'Recibido', 'en_produccion' => 'En producción', 'listo' => 'Listo',
                    'enviado' => 'Enviado', 'entregado' => 'Entregado', 'cancelado' => 'Cancelado',
                ];
                $claseEstado = fn($e) => match($e) {
                    'recibido' => 'est-recibido', 'en_produccion', 'enviado' => 'est-produccion',
                    'listo' => 'est-listo', 'entregado' => 'est-entregado', default => 'est-pendiente',
                };
                $estadosProduccion = [];
                if ($tipo === 'maestro') {
                    if ($pedido->pedidoPlantilla) $estadosProduccion['Ropa'] = $pedido->pedidoPlantilla->estado;
                    if ($pedido->pedidoUniforme) $estadosProduccion['Uniforme'] = $pedido->pedidoUniforme->estado;
                    if ($pedido->pedidoChompa) $estadosProduccion['Chompa'] = $pedido->pedidoChompa->estado;
                } else {
                    $estadosProduccion[''] = $pedido->estado;
                }

                // Estado de pago
                $pagoMap = [
                    'pendiente' => 'Sin comprobante', 'adelanto_enviado' => 'Adelanto en revisión',
                    'adelanto_verificado' => 'Adelanto verificado', 'pago_completo_enviado' => 'Pago en revisión',
                    'saldo_enviado' => 'Saldo en revisión', 'saldo_pendiente' => 'Saldo pendiente',
                    'pagado_completo' => 'Pagado completo',
                ];
                $clasePago = match($pedido->estado_pago) {
                    'pendiente' => 'est-pendiente',
                    'adelanto_enviado', 'pago_completo_enviado', 'saldo_enviado' => 'est-produccion',
                    'adelanto_verificado' => 'est-recibido',
                    'pagado_completo' => 'est-entregado',
                    default => 'est-pendiente',
                };

                // Acción según tipo
                $rutaPago = match($tipo) {
                    'maestro'  => route('cliente.pedido-maestro.pago', $pedido->id),
                    'uniforme' => route('cliente.uniformes.pago', $pedido->id),
                    'chompa'   => route('cliente.chompas.pago', $pedido->id),
                    'ropa'     => route('cliente.plantillas.pago', $pedido->id),
                    'camiseta' => route('cliente.pedidos.comprobante', $pedido->id),
                };
                $enRevision = in_array($pedido->estado_pago, ['adelanto_enviado','pago_completo_enviado','saldo_enviado']);
            @endphp

            <div class="pedido-card">
                <div class="pedido-inner">
                    <div class="pedido-imagenes">
                        @forelse($imagenes as $img)
                            <div class="pedido-imagen"><img src="{{ asset('storage/'.$img) }}" alt=""></div>
                        @empty
                            <div class="pedido-imagen">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                                </svg>
                            </div>
                        @endforelse
                    </div>

                    <div class="pedido-detalles">
                        <div class="pedido-codigo-row">
                            <span class="pedido-codigo">{{ $pedido->codigo }}</span>
                            <span class="pedido-tipo-tag {{ $tipo === 'maestro' ? 'tag-combinado' : '' }}">{{ $etiquetaTipo }}</span>
                        </div>
                        @foreach($lineas as $linea)
                            <div class="pedido-item-linea">
                                <strong>{{ $linea['nombre'] }}</strong> — {{ $linea['detalle'] }}
                                @if($linea['subtotal'] !== null) (${{ number_format($linea['subtotal'], 2) }}) @endif
                            </div>
                        @endforeach
                        <div class="pedido-fecha">{{ $pedido->created_at->format('d M Y') }}</div>
                    </div>

                    <div class="estados-grid">
                        <div class="estado-item">
                            <div class="estado-label">Estado pedido</div>
                            @foreach($estadosProduccion as $etiquetaHijo => $estado)
                                <div class="estado-badge {{ $claseEstado($estado) }}">
                                    @if($etiquetaHijo) {{ $etiquetaHijo }}: @endif{{ $estadoMap[$estado] ?? ucfirst($estado) }}
                                </div><br>
                            @endforeach
                        </div>
                        <div class="estado-item">
                            <div class="estado-label">Estado pago</div>
                            <div class="estado-badge {{ $clasePago }}">
                                {{ $pagoMap[$pedido->estado_pago] ?? ucfirst(str_replace('_',' ',$pedido->estado_pago)) }}
                            </div>
                        </div>
                    </div>

                    <div class="precios-box">
                        <div class="precio-linea">Total: <strong>${{ number_format($pedido->precio_total, 2) }}</strong></div>
                        <div class="precio-linea precio-adelanto">Adelanto: <strong>${{ number_format($pedido->precio_adelanto, 2) }}</strong></div>
                        <div class="precio-linea">Saldo: <strong>${{ number_format($pedido->precio_saldo, 2) }}</strong></div>
                    </div>

                    <div class="acciones-box">
                        @if($pedido->estado_pago !== 'pagado_completo')
                            @if($enRevision)
                                <div class="info-pendiente">Verificando...</div>
                            @else
                                <a href="{{ $rutaPago }}" class="btn-outline-square">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0-3-3m3 3 3-3M5 21h14"/></svg>
                                    Subir comprobante
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

@endsection
