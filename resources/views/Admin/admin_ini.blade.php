@extends('Admin.panel_admin')

@section('titulo', 'Dashboard')
@section('page-title', 'Panel Administrador')
@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@push('estilos')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* Contenedor principal unificado */
    .dashboard-unified {
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px; /* suave, menos cuadrado */
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-sm);
    }
    /* Sección de números (métricas) */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0;
        border-bottom: 1px solid var(--border);
        background: var(--bg-2);
    }
    .stat-item {
        padding: 1.25rem 1rem;
        text-align: center;
        border-right: 1px solid var(--border);
        transition: background 0.2s;
    }
    .stat-item:last-child {
        border-right: none;
    }
    .stat-item:hover {
        background: var(--bg-3);
    }
    .stat-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-3);
        margin-bottom: 0.5rem;
    }
    .stat-number {
        font-family: var(--font-d);
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 0.2rem;
    }
    .stat-footer {
        font-size: 0.65rem;
        color: var(--text-3);
    }
    /* Sección de gráficos */
    .charts-row {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 1.5rem;
        padding: 1.5rem;
        background: var(--bg-2);
    }
    .chart-box {
        background: var(--bg-3);
        padding: 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
    }
    .chart-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-1);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid var(--border);
        padding-bottom: 0.5rem;
    }
    .chart-title i {
        color: var(--blue);
        font-size: 1rem;
    }
    canvas {
        max-height: 220px;
        width: 100%;
    }
    .legend-colors {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.8rem;
        margin-top: 1rem;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        color: var(--text-2);
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 2px;
    }
    /* Tabla de pedidos recientes (fuera del cuadro unificado, pero con estilo coherente) */
    .recent-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .recent-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-1);
    }
    .recent-link {
        font-size: 0.8rem;
        color: var(--blue);
        text-decoration: none;
        font-weight: 500;
    }
    .recent-link:hover {
        text-decoration: underline;
    }
    .tabla-modern {
        width: 100%;
        border-collapse: collapse;
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }
    .tabla-modern th,
    .tabla-modern td {
        padding: 0.85rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
        font-size: 0.8rem;
    }
    .tabla-modern th {
        background: var(--bg-3);
        font-weight: 700;
        color: var(--text-2);
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
    }
    .tabla-modern tr:last-child td {
        border-bottom: none;
    }
    .tabla-modern tr:hover td {
        background: var(--bg-3);
    }
    .badge-estado {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .badge-recibido { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .badge-produccion { background: #dbeafe; color: #1e40af; border-color: #bfdbfe; }
    .badge-listo { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .badge-entregado { background: #e0e7ff; color: #3730a3; border-color: #c7d2fe; }
    .badge-cancelado { background: #ffe4e2; color: #b91c1c; border-color: #fecaca; }
    .badge-pendiente { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .badge-verificado { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .badge-pagado { background: #dbeafe; color: #1e40af; border-color: #bfdbfe; }
    [data-theme="dark"] .badge-recibido { background: #78350f; color: #fde68a; border-color: #92400e; }
    [data-theme="dark"] .badge-produccion { background: #1e3a8a; color: #bfdbfe; border-color: #3b82f6; }
    [data-theme="dark"] .badge-listo { background: #14532d; color: #bbf7d0; border-color: #22c55e; }
    [data-theme="dark"] .badge-entregado { background: #2e1065; color: #c7d2fe; border-color: #6366f1; }
    [data-theme="dark"] .badge-cancelado { background: #7f1d1d; color: #fecaca; border-color: #ef4444; }
    [data-theme="dark"] .badge-pendiente { background: #78350f; color: #fde68a; border-color: #92400e; }
    .btn-ver {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        background: var(--blue-soft);
        color: var(--blue);
        border: 1px solid var(--blue-border);
        font-size: 0.7rem;
        font-weight: 600;
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.15s;
    }
    .btn-ver:hover {
        background: var(--blue);
        color: white;
        border-color: var(--blue);
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: var(--bg-2);
        border: 1px solid var(--border);
        border-radius: 12px;
    }
    .empty-state svg {
        width: 48px;
        height: 48px;
        stroke: var(--text-3);
        margin-bottom: 1rem;
    }
    @media (max-width: 900px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
        .stats-row .stat-item:nth-child(5) {
            grid-column: span 2;
            border-right: none;
        }
        .charts-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .tabla-modern th, .tabla-modern td {
            padding: 0.6rem;
        }
    }
    @media (max-width: 640px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
        .stats-row .stat-item {
            border-right: none;
            border-bottom: 1px solid var(--border);
        }
        .stats-row .stat-item:last-child {
            border-bottom: none;
        }
        .tabla-modern thead {
            display: none;
        }
        .tabla-modern tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
        }
        .tabla-modern td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem;
            border-bottom: 1px solid var(--border);
        }
        .tabla-modern td:before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--text-2);
            width: 40%;
        }
    }
</style>
@endpush

@section('contenido')

{{-- CUADRO UNIFICADO: números + gráficos --}}
<div class="dashboard-unified">
    {{-- Fila de números (métricas) --}}
    <div class="stats-row">
        <div class="stat-item">
            <div class="stat-title">Total Pedidos</div>
            <div class="stat-number">{{ $total_pedidos }}</div>
            <div class="stat-footer">Histórico</div>
        </div>
        <div class="stat-item">
            <div class="stat-title">En Producción</div>
            <div class="stat-number" style="color: var(--blue);">{{ $en_produccion }}</div>
            <div class="stat-footer">En proceso</div>
        </div>
        <div class="stat-item">
            <div class="stat-title">Listos</div>
            <div class="stat-number" style="color: #16A34A;">{{ $listos }}</div>
            <div class="stat-footer">Para entregar</div>
        </div>
        <div class="stat-item">
            <div class="stat-title">Clientes</div>
            <div class="stat-number" style="color: #7C3AED;">{{ $total_clientes }}</div>
            <div class="stat-footer">Registrados</div>
        </div>
        <div class="stat-item">
            <div class="stat-title">Camisetas</div>
            <div class="stat-number" style="color: #D97706;">{{ $total_plantillas }}</div>
            <div class="stat-footer">Activas</div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="charts-row">
        <div class="chart-box">
            <div class="chart-title">
                <i class="fas fa-chart-bar"></i> Pedidos por mes
            </div>
            <canvas id="chartBarras" height="200"></canvas>
        </div>
        <div class="chart-box">
            <div class="chart-title">
                <i class="fas fa-chart-pie"></i> Estados de pedidos
            </div>
            <canvas id="chartPastel" height="180"></canvas>
            <div class="legend-colors">
                <span class="legend-item"><span class="legend-dot" style="background:#F59E0B;"></span> Recibido</span>
                <span class="legend-item"><span class="legend-dot" style="background:#2563EB;"></span> Producción</span>
                <span class="legend-item"><span class="legend-dot" style="background:#16A34A;"></span> Listo</span>
                <span class="legend-item"><span class="legend-dot" style="background:#94A3B8;"></span> Entregado</span>
                <span class="legend-item"><span class="legend-dot" style="background:#EF4444;"></span> Cancelado</span>
            </div>
        </div>
    </div>
</div>

{{-- Pedidos recientes (separado pero con estilo) --}}
<div class="recent-header">
    <div class="recent-title">Pedidos recientes</div>
    <a href="{{ route('admin.pedidos.index') }}" class="recent-link">Ver todos →</a>
</div>

@if($pedidos_recientes->isEmpty())
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <p style="color: var(--text-2);">No hay pedidos aún.</p>
    </div>
@else
    <table class="tabla-modern">
        <thead>
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Pago</th>
                <th>Fecha</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos_recientes as $pedido)
                <tr>
                    <td data-label="Código"><span class="t-code">{{ $pedido->codigo }}</span></td>
                    <td data-label="Cliente">
                        <div>{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</div>
                        <div class="t-muted" style="font-size:0.7rem;">{{ $pedido->cliente->email }}</div>
                    </td>
                    <td data-label="Estado">
                        @php
                            $estadoClase = match($pedido->estado) {
                                'recibido' => 'badge-recibido',
                                'en_produccion' => 'badge-produccion',
                                'listo' => 'badge-listo',
                                'entregado' => 'badge-entregado',
                                'cancelado' => 'badge-cancelado',
                                default => 'badge-pendiente'
                            };
                            $estadoTexto = match($pedido->estado) {
                                'recibido' => 'Recibido',
                                'en_produccion' => 'En producción',
                                'listo' => 'Listo',
                                'entregado' => 'Entregado',
                                'cancelado' => 'Cancelado',
                                default => $pedido->estado
                            };
                        @endphp
                        <span class="badge-estado {{ $estadoClase }}">{{ $estadoTexto }}</span>
                    </td>
                    <td data-label="Pago">
                        @php
                            $pagoClase = match($pedido->estado_pago) {
                                'pendiente' => 'badge-pendiente',
                                'adelanto_enviado' => 'badge-produccion',
                                'adelanto_verificado' => 'badge-verificado',
                                'pagado_completo' => 'badge-pagado',
                                default => 'badge-pendiente'
                            };
                            $pagoTexto = match($pedido->estado_pago) {
                                'pendiente' => 'Pendiente',
                                'adelanto_enviado' => 'Comprobante enviado',
                                'adelanto_verificado' => 'Adelanto verificado',
                                'pagado_completo' => 'Pagado',
                                default => $pedido->estado_pago
                            };
                        @endphp
                        <span class="badge-estado {{ $pagoClase }}">{{ $pagoTexto }}</span>
                    </td>
                    <td data-label="Fecha">{{ $pedido->created_at->format('d M Y') }}</td>
                    <td data-label="Acción">
                        <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn-ver">Ver</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@endsection

@push('scripts')
<script>
    // Detectar tema para los gráficos
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDark ? '#94A3B8' : '#475569';
    const gridColor = isDark ? '#1E2D45' : '#E2E8F0';

    // Datos desde Laravel
    const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    const datosMes = @json($pedidos_por_mes);
    const labelsBarras = datosMes.map(d => meses[d.mes - 1]);
    const valoresBarras = datosMes.map(d => d.total);

    // Gráfico de barras
    new Chart(document.getElementById('chartBarras'), {
        type: 'bar',
        data: {
            labels: labelsBarras.length ? labelsBarras : ['Sin datos'],
            datasets: [{
                label: 'Pedidos',
                data: valoresBarras.length ? valoresBarras : [0],
                backgroundColor: 'rgba(37,99,235,0.85)',
                borderRadius: 4,
                borderSkipped: false,
                barPercentage: 0.7,
                categoryPercentage: 0.8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: isDark ? '#1e293b' : '#ffffff', titleColor: isDark ? '#f1f5f9' : '#0f172a', bodyColor: isDark ? '#cbd5e1' : '#475569' }
            },
            scales: {
                x: { grid: { color: gridColor, drawBorder: true }, ticks: { color: textColor, font: { size: 10 } } },
                y: { grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1, beginAtZero: true, font: { size: 10 } } }
            }
        }
    });

    // Gráfico de dona
    new Chart(document.getElementById('chartPastel'), {
        type: 'doughnut',
        data: {
            labels: ['Recibido','En producción','Listo','Entregado','Cancelado'],
            datasets: [{
                data: [{{ $recibidos }}, {{ $en_produccion }}, {{ $listos }}, {{ $entregados }}, {{ $cancelados }}],
                backgroundColor: ['#F59E0B','#2563EB','#16A34A','#94A3B8','#EF4444'],
                borderWidth: 0,
                hoverOffset: 6,
                cutout: '65%',
                borderRadius: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: isDark ? '#1e293b' : '#ffffff', titleColor: isDark ? '#f1f5f9' : '#0f172a', bodyColor: isDark ? '#cbd5e1' : '#475569' }
            }
        }
    });
</script>
@endpush