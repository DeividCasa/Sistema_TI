@extends('layouts.catalogo')

@section('titulo', 'Mis pedidos')

@section('contenido')

<style>
    .pedidos-container { max-width: 1100px; margin: 0 auto; }
    .header-actions {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .title-section { display: flex; align-items: baseline; gap: 0.75rem; flex-wrap: wrap; }
    .page-title { font-size: 1.6rem; font-weight: 700; color: var(--text-1); margin: 0; }
    .badge-count {
        background: var(--bg-3); color: var(--text-2); padding: 0.25rem 0.75rem;
        font-size: 0.8rem; font-weight: 500; border: 1px solid var(--border); border-radius: 6px;
    }
    .empty-card {
        background: var(--bg-2); border: 1px solid var(--border); border-radius: 12px;
        padding: 3rem; text-align: center;
    }
    .empty-svg { width: 64px; height: 64px; stroke: var(--text-3); margin-bottom: 1rem; }
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
        <a href="{{ route('cliente.uniformes.index') }}" class="btn-primary" style="text-decoration:none;">+ Nuevo pedido</a>
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

                // Líneas de producto (nombre, detalle, subtotal, imagen) según el tipo de pedido
                $lineas = [];

                if ($tipo === 'maestro') {
                    if ($pedido->pedidoPlantilla) {
                        foreach ($pedido->pedidoPlantilla->items as $item) {
                            $detalle = ($item->talla ? 'Talla '.$item->talla.' × ' : '').$item->cantidad;
                            $lineas[] = ['nombre' => $item->plantilla->nombre ?? 'Producto', 'detalle' => $detalle, 'subtotal' => $item->subtotal, 'imagen' => optional($item->plantilla)->imagen_preview];
                        }
                    }
                    if ($pedido->pedidoUniforme) {
                        foreach ($pedido->pedidoUniforme->items as $item) {
                            $lineas[] = ['nombre' => $item->uniforme->nombre, 'detalle' => 'Talla '.$item->talla.' × '.$item->cantidad, 'subtotal' => $item->subtotal, 'imagen' => $item->uniforme->imagen];
                        }
                    }
                    if ($pedido->pedidoChompa) {
                        foreach ($pedido->pedidoChompa->items as $item) {
                            $lineas[] = ['nombre' => $item->chompa->nombre, 'detalle' => 'Talla '.$item->talla.' × '.$item->cantidad, 'subtotal' => $item->subtotal, 'imagen' => $item->chompa->imagen];
                        }
                    }
                } elseif ($tipo === 'uniforme') {
                    foreach ($pedido->items as $item) {
                        $lineas[] = ['nombre' => $item->uniforme->nombre, 'detalle' => 'Talla '.$item->talla.' × '.$item->cantidad, 'subtotal' => $item->subtotal, 'imagen' => $item->uniforme->imagen];
                    }
                } elseif ($tipo === 'chompa') {
                    foreach ($pedido->items as $item) {
                        $lineas[] = ['nombre' => $item->chompa->nombre, 'detalle' => 'Talla '.$item->talla.' × '.$item->cantidad, 'subtotal' => $item->subtotal, 'imagen' => $item->chompa->imagen];
                    }
                } elseif ($tipo === 'ropa') {
                    foreach ($pedido->items as $item) {
                        $detalle = ($item->talla ? 'Talla '.$item->talla.' × ' : '').$item->cantidad;
                        $lineas[] = ['nombre' => $item->plantilla->nombre ?? 'Producto', 'detalle' => $detalle, 'subtotal' => $item->subtotal, 'imagen' => optional($item->plantilla)->imagen_preview];
                    }
                } else { // camiseta
                    $imagenCamiseta = optional($pedido->disenio)->imagen_generada ?: optional($pedido->disenio->plantilla ?? null)->imagen_preview;
                    $lineas[] = ['nombre' => $pedido->disenio->nombre ?? 'Diseño personalizado', 'detalle' => $pedido->cantidad_total.' unidad(es)', 'subtotal' => null, 'imagen' => $imagenCamiseta];
                }

                // Estado(s) de producción a mostrar
                $estadoMap = [
                    'recibido' => 'Recibido', 'en_produccion' => 'En producción', 'listo' => 'Listo',
                    'enviado' => 'Enviado', 'entregado' => 'Entregado', 'cancelado' => 'Cancelado',
                ];
                $estadosProduccion = [];
                if ($tipo === 'maestro') {
                    if ($pedido->pedidoPlantilla) $estadosProduccion['Ropa'] = $pedido->pedidoPlantilla->estado;
                    if ($pedido->pedidoUniforme) $estadosProduccion['Uniforme'] = $pedido->pedidoUniforme->estado;
                    if ($pedido->pedidoChompa) $estadosProduccion['Chompa'] = $pedido->pedidoChompa->estado;
                } else {
                    $estadosProduccion[''] = $pedido->estado;
                }

                // Estado de pago
                $pagos = [
                    'pendiente'             => ['#FEF9C3', '#A16207', 'Sin comprobante'],
                    'adelanto_enviado'      => ['#DBEAFE', '#1D4ED8', 'Adelanto en revisión'],
                    'adelanto_verificado'   => ['#DCFCE7', '#15803D', 'Adelanto verificado'],
                    'pago_completo_enviado' => ['#DBEAFE', '#1D4ED8', 'Pago en revisión'],
                    'saldo_enviado'         => ['#DBEAFE', '#1D4ED8', 'Saldo en revisión'],
                    'saldo_pendiente'       => ['#FEF9C3', '#A16207', 'Saldo pendiente'],
                    'pagado_completo'       => ['#DCFCE7', '#15803D', 'Pagado completo'],
                ];
                [$pagoBg, $pagoColor, $pagoTexto] = $pagos[$pedido->estado_pago] ?? ['#F1F5F9', '#475569', $pedido->estado_pago];

                $enRevision = in_array($pedido->estado_pago, ['adelanto_enviado', 'pago_completo_enviado', 'saldo_enviado']);

                $rutaPago = $tipo === 'camiseta'
                    ? route('cliente.pedidos.comprobante', $pedido->id)
                    : route('cliente.mis-pedidos.pago', [$tipo, $pedido->id]);
            @endphp

            <div class="card reveal" style="margin-bottom:18px;overflow:hidden;">

                <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 20px;background:var(--bg-3);border-bottom:1px solid var(--border);flex-wrap:wrap;gap:10px;">
                    <div>
                        <span style="font-weight:800;color:var(--blue);font-size:0.95rem;">{{ $pedido->codigo }}</span>
                        <span style="font-size:0.78rem;color:var(--text-3);margin-left:10px;">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        <span style="background:var(--blue-soft);border:1px solid var(--blue-border, var(--blue));color:var(--blue);padding:4px 12px;border-radius:6px;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.03em;">
                            {{ $etiquetaTipo }}
                        </span>

                        @foreach($estadosProduccion as $etiquetaHijo => $estado)
                            <span style="background:var(--bg-2);border:1px solid var(--border);color:var(--text-2);padding:4px 12px;border-radius:6px;font-size:0.75rem;font-weight:600;">
                                @if($etiquetaHijo){{ $etiquetaHijo }}: @endif{{ $estadoMap[$estado] ?? ucfirst($estado) }}
                            </span>
                        @endforeach

                        @if($pedido->tiempo_estimado)
                            <span style="background:var(--blue-soft);border:1px solid var(--blue);color:var(--blue);padding:4px 12px;border-radius:6px;font-size:0.75rem;font-weight:600;">
                                Entrega estimada: {{ $pedido->tiempo_estimado }}
                            </span>
                        @endif

                        <span style="background:{{ $pagoBg }};color:{{ $pagoColor }};padding:4px 12px;border-radius:6px;font-size:0.75rem;font-weight:600;">
                            {{ $pagoTexto }}
                        </span>
                    </div>
                </div>

                <div style="padding:14px 20px;">
                    @foreach($lineas as $linea)
                        <div style="display:flex;align-items:center;gap:14px;padding:8px 0;border-bottom:1px dashed var(--border);">
                            @if($linea['imagen'])
                                <img src="{{ asset('storage/'.$linea['imagen']) }}"
                                     style="width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid var(--border);flex-shrink:0;">
                            @else
                                <div style="width:44px;height:44px;border-radius:8px;border:1px solid var(--border);background:var(--bg-3);flex-shrink:0;"></div>
                            @endif
                            <div style="flex:1;font-size:0.85rem;">
                                <div style="font-weight:600;color:var(--text-1);">{{ $linea['nombre'] }}</div>
                                <div style="color:var(--text-3);font-size:0.76rem;">{{ $linea['detalle'] }}</div>
                            </div>
                            @if($linea['subtotal'] !== null)
                                <strong style="color:var(--text-1);font-size:0.88rem;">${{ number_format($linea['subtotal'], 2) }}</strong>
                            @endif
                        </div>
                    @endforeach

                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;flex-wrap:wrap;gap:10px;">
                        <div style="font-size:0.85rem;color:var(--text-2);">
                            Total: <strong style="color:var(--text-1);font-size:1rem;">${{ number_format($pedido->precio_total, 2) }}</strong>
                            <span style="margin-left:12px;">
                                Adelanto (50%): <strong style="color:var(--blue);">${{ number_format($pedido->precio_adelanto, 2) }}</strong>
                            </span>
                        </div>

                        @if($enRevision)
                            <a href="{{ $rutaPago }}" style="text-decoration:none;background:var(--bg-3);padding:0.5rem 0.9rem;font-size:0.75rem;font-weight:600;color:var(--text-2);border:1px solid var(--border);border-radius:6px;">
                                Verificando... <span style="text-decoration:underline;">ver detalle</span>
                            </a>
                        @elseif($pedido->estado_pago === 'pagado_completo')
                            <a href="{{ $rutaPago }}" class="btn-secondary" style="text-decoration:none;">
                                Ver comprobantes
                            </a>
                        @else
                            <a href="{{ $rutaPago }}" class="btn-primary" style="text-decoration:none;padding:9px 20px;font-size:0.85rem;">
                                💳 Subir comprobante
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

@endsection
