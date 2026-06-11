@extends('Plantilla/Plantilla')

@section('titulo', 'Dashboard')
@section('page-title', 'Panel Administrador')

@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@push('sidebar-menu')
<div class="sidebar-label">Principal</div>
<a href="{{ route('admin.inicio') }}" class="nav-item active">
  <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
  Dashboard
</a>
<a href="{{ route('admin.pedidos.index') }}" class="nav-item">
  <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
  Pedidos
</a>
<a href="{{ route('admin.plantillas.index') }}" class="nav-item">
  <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
  Plantillas
</a>
<a href="{{ route('admin.clientes.index') }}" class="nav-item">
  <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
  Clientes
</a>
@endpush

@push('estilos')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('contenido')

{{-- TARJETAS --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:16px;margin-bottom:28px;">
  <div class="card card-pad reveal">
    <div style="font-size:0.72rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Total Pedidos</div>
    <div style="font-family:var(--font-d);font-size:2rem;font-weight:800;color:var(--text-1);">{{ $total_pedidos }}</div>
    <div style="font-size:0.75rem;color:var(--text-3);margin-top:4px;">Histórico completo</div>
  </div>
  <div class="card card-pad reveal" style="transition-delay:0.05s">
    <div style="font-size:0.72rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">En Producción</div>
    <div style="font-family:var(--font-d);font-size:2rem;font-weight:800;color:var(--blue);">{{ $en_produccion }}</div>
    <div style="font-size:0.75rem;color:var(--text-3);margin-top:4px;">En proceso ahora</div>
  </div>
  <div class="card card-pad reveal" style="transition-delay:0.1s">
    <div style="font-size:0.72rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Listos</div>
    <div style="font-family:var(--font-d);font-size:2rem;font-weight:800;color:#16A34A;">{{ $listos }}</div>
    <div style="font-size:0.75rem;color:var(--text-3);margin-top:4px;">Para entregar</div>
  </div>
  <div class="card card-pad reveal" style="transition-delay:0.15s">
    <div style="font-size:0.72rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Clientes</div>
    <div style="font-family:var(--font-d);font-size:2rem;font-weight:800;color:#7C3AED;">{{ $total_clientes }}</div>
    <div style="font-size:0.75rem;color:var(--text-3);margin-top:4px;">Registrados</div>
  </div>
  <div class="card card-pad reveal" style="transition-delay:0.2s">
    <div style="font-size:0.72rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Plantillas</div>
    <div style="font-family:var(--font-d);font-size:2rem;font-weight:800;color:#D97706;">{{ $total_plantillas }}</div>
    <div style="font-size:0.75rem;color:var(--text-3);margin-top:4px;">Activas</div>
  </div>
</div>

{{-- GRÁFICOS --}}
<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:20px;margin-bottom:28px;">

  {{-- Gráfico de barras — pedidos por mes --}}
  <div class="card card-pad reveal">
    <div class="sec-title" style="margin-bottom:20px;">Pedidos por mes</div>
    <canvas id="chartBarras" height="120"></canvas>
  </div>

  {{-- Gráfico de pastel — estados --}}
  <div class="card card-pad reveal">
    <div class="sec-title" style="margin-bottom:20px;">Estados de pedidos</div>
    <canvas id="chartPastel" height="160"></canvas>
    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:16px;">
      <span style="display:flex;align-items:center;gap:5px;font-size:0.72rem;color:var(--text-2);">
        <span style="width:10px;height:10px;border-radius:50%;background:#F59E0B;display:inline-block;"></span>Recibido
      </span>
      <span style="display:flex;align-items:center;gap:5px;font-size:0.72rem;color:var(--text-2);">
        <span style="width:10px;height:10px;border-radius:50%;background:#2563EB;display:inline-block;"></span>En producción
      </span>
      <span style="display:flex;align-items:center;gap:5px;font-size:0.72rem;color:var(--text-2);">
        <span style="width:10px;height:10px;border-radius:50%;background:#16A34A;display:inline-block;"></span>Listo
      </span>
      <span style="display:flex;align-items:center;gap:5px;font-size:0.72rem;color:var(--text-2);">
        <span style="width:10px;height:10px;border-radius:50%;background:#94A3B8;display:inline-block;"></span>Entregado
      </span>
      <span style="display:flex;align-items:center;gap:5px;font-size:0.72rem;color:var(--text-2);">
        <span style="width:10px;height:10px;border-radius:50%;background:#EF4444;display:inline-block;"></span>Cancelado
      </span>
    </div>
  </div>

</div>

{{-- PEDIDOS RECIENTES --}}
<div class="sec-header reveal">
  <div class="sec-title">Pedidos recientes</div>
  <a href="{{ route('admin.pedidos.index') }}" class="sec-link">Ver todos →</a>
</div>

<div class="tabla-box reveal">
  <div class="tabla-head" style="grid-template-columns:1fr 1.5fr 1fr 1fr 1fr 0.8fr;">
    <span>Código</span>
    <span>Cliente</span>
    <span>Estado</span>
    <span>Pago</span>
    <span>Fecha</span>
    <span>Ver</span>
  </div>
  @forelse($pedidos_recientes as $pedido)
    <div class="tabla-row" style="grid-template-columns:1fr 1.5fr 1fr 1fr 1fr 0.8fr;">
      <span class="t-code">{{ $pedido->codigo }}</span>
      <div>
        <div class="t-text">{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</div>
        <div class="t-muted">{{ $pedido->cliente->email }}</div>
      </div>
      <span>
        @if($pedido->estado == 'recibido')         <div class="est est-recibido">Recibido</div>
        @elseif($pedido->estado == 'en_produccion') <div class="est est-produccion">En producción</div>
        @elseif($pedido->estado == 'listo')         <div class="est est-listo">Listo</div>
        @elseif($pedido->estado == 'entregado')     <div class="est est-entregado">Entregado</div>
        @elseif($pedido->estado == 'cancelado')     <div class="est est-pendiente">Cancelado</div>
        @endif
      </span>
      <span>
        @if($pedido->estado_pago == 'pendiente')            <div class="est est-pendiente">Pendiente</div>
        @elseif($pedido->estado_pago == 'adelanto_verificado') <div class="est est-produccion">Adelanto ✓</div>
        @elseif($pedido->estado_pago == 'pagado_completo')  <div class="est est-listo">Pagado</div>
        @else <div class="est est-recibido">{{ $pedido->estado_pago }}</div>
        @endif
      </span>
      <span class="t-muted">{{ $pedido->created_at->format('d M Y') }}</span>
      <span>
        <a href="{{ route('admin.pedidos.show', $pedido->id) }}"
           style="padding:6px 14px;border-radius:8px;background:var(--blue-soft);
           color:var(--blue);font-size:0.78rem;font-weight:600;text-decoration:none;
           border:1px solid var(--blue-border);">Ver</a>
      </span>
    </div>
  @empty
    <div class="empty-state">
      <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <p>No hay pedidos aún.</p>
    </div>
  @endforelse
</div>

@endsection

@push('scripts')
<script>
const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
const textColor = isDark ? '#94A3B8' : '#475569';
const gridColor = isDark ? '#1E2D45' : '#E2E8F0';

// ── DATOS DESDE LARAVEL ──
const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
const datosMes = @json($pedidos_por_mes);
const labelsBarras = datosMes.map(d => meses[d.mes - 1]);
const valoresBarras = datosMes.map(d => d.total);

// ── GRÁFICO DE BARRAS ──
new Chart(document.getElementById('chartBarras'), {
  type: 'bar',
  data: {
    labels: labelsBarras.length ? labelsBarras : ['Sin datos'],
    datasets: [{
      label: 'Pedidos',
      data: valoresBarras.length ? valoresBarras : [0],
      backgroundColor: 'rgba(37,99,235,0.8)',
      borderRadius: 8,
      borderSkipped: false,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { color: gridColor }, ticks: { color: textColor } },
      y: { grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1 }, beginAtZero: true }
    }
  }
});

// ── GRÁFICO DE PASTEL ──
new Chart(document.getElementById('chartPastel'), {
  type: 'doughnut',
  data: {
    labels: ['Recibido','En producción','Listo','Entregado','Cancelado'],
    datasets: [{
      data: [{{ $recibidos }}, {{ $en_produccion }}, {{ $listos }}, {{ $entregados }}, {{ $cancelados }}],
      backgroundColor: ['#F59E0B','#2563EB','#16A34A','#94A3B8','#EF4444'],
      borderWidth: 0,
      hoverOffset: 8,
    }]
  },
  options: {
    responsive: true,
    cutout: '65%',
    plugins: {
      legend: { display: false }
    }
  }
});
</script>
@endpush