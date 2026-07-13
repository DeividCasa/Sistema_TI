@extends('Admin.panel_admin')

@section('titulo', 'Pedidos de Chompas')
@section('page-title', 'Pedidos de Chompas')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

@if(session('success'))
  <div class="badge-success" style="display:block;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Pedidos de Chompas</div>
</div>

<div class="card reveal" style="overflow:auto;">
  <table class="admin-table" style="min-width:900px;">
    <thead>
      <tr>
        <th>Código</th>
        <th>Cliente</th>
        <th>Items</th>
        <th>Total</th>
        <th>Adelanto (50%)</th>
        <th>Estado pago</th>
        <th>Estado pedido</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($pedidos as $pedido)
        <tr>
          <td class="cell-strong" style="color:var(--blue);">{{ $pedido->codigo }}</td>
          <td>
            <div class="cell-strong">{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</div>
            <div class="cell-muted">{{ $pedido->cliente->email }}</div>
          </td>
          <td>{{ $pedido->items->count() }} chompa(s) / {{ $pedido->cantidad_total }} prenda(s)</td>
          <td class="cell-strong">${{ number_format($pedido->precio_total, 2) }}</td>
          <td>${{ number_format($pedido->precio_adelanto, 2) }}</td>
          <td>
            @php
              $pagos = [
                'pendiente'           => ['badge-warning', 'Pago pendiente'],
                'adelanto_verificado' => ['badge-success', 'Adelanto verificado'],
                'pagado_completo'     => ['badge-success', 'Pagado completo'],
              ];
              [$claseBadge, $texto] = $pagos[$pedido->estado_pago] ?? ['badge-neutral', $pedido->estado_pago];
            @endphp
            <span class="badge {{ $claseBadge }}">{{ $texto }}</span>
            @if($pedido->estado_pago !== 'pagado_completo')
              <form action="{{ route('admin.pedidos-chompas.pago-completo', $pedido->id) }}" method="POST" style="display:inline-block;margin-left:6px;" onsubmit="return confirm('¿Marcar este pedido como pagado por completo?');">
                @csrf
                <button type="submit" class="btn-marcar-pagado">Marcar pagado</button>
              </form>
            @endif
          </td>
          <td style="text-transform:capitalize;">{{ str_replace('_', ' ', $pedido->estado) }}</td>
          <td class="cell-actions">
            <a href="{{ route('admin.pedidos-chompas.show', $pedido->id) }}">Ver detalle</a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="cell-empty">Aún no hay pedidos de chompas.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
