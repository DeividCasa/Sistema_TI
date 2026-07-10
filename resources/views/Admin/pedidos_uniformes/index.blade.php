@extends('Admin.panel_admin')

@section('titulo', 'Pedidos de Uniformes')
@section('page-title', 'Pedidos de Uniformes Escolares')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    ✓ {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Pedidos de Uniformes Escolares</div>
</div>

<div class="card reveal" style="overflow:auto;">
  <table style="width:100%;border-collapse:collapse;font-size:0.86rem;min-width:900px;">
    <thead>
      <tr style="background:var(--bg-3);text-align:left;">
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Código</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Cliente</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Items</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Total</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Adelanto (50%)</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Estado pago</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Estado pedido</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;"></th>
      </tr>
    </thead>
    <tbody>
      @forelse($pedidos as $pedido)
        <tr style="border-top:1px solid var(--border);">
          <td style="padding:10px 16px;font-weight:700;color:var(--blue);">{{ $pedido->codigo }}</td>
          <td style="padding:10px 16px;">
            <div style="font-weight:600;color:var(--text-1);">{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</div>
            <div style="font-size:0.75rem;color:var(--text-3);">{{ $pedido->cliente->email }}</div>
          </td>
          <td style="padding:10px 16px;color:var(--text-2);">{{ $pedido->items->count() }} uniforme(s) / {{ $pedido->cantidad_total }} prenda(s)</td>
          <td style="padding:10px 16px;font-weight:700;color:var(--text-1);">${{ number_format($pedido->precio_total, 2) }}</td>
          <td style="padding:10px 16px;color:var(--text-2);">${{ number_format($pedido->precio_adelanto, 2) }}</td>
          <td style="padding:10px 16px;">
            @php
              $pagos = [
                'pendiente'             => ['#FEF9C3', '#A16207', 'Pago pendiente'],
                'adelanto_enviado'      => ['#DBEAFE', '#1D4ED8', 'Adelanto enviado'],
                'adelanto_verificado'   => ['#DCFCE7', '#15803D', 'Adelanto verificado'],
                'pago_completo_enviado' => ['#DBEAFE', '#1D4ED8', 'Pago completo enviado'],
                'saldo_enviado'         => ['#DBEAFE', '#1D4ED8', 'Saldo enviado'],
                'pagado_completo'       => ['#DCFCE7', '#15803D', 'Pagado completo'],
              ];
              [$bg, $color, $texto] = $pagos[$pedido->estado_pago] ?? ['#F1F5F9', '#475569', $pedido->estado_pago];
            @endphp
            <span style="background:{{ $bg }};color:{{ $color }};padding:4px 10px;border-radius:6px;font-size:0.73rem;font-weight:600;white-space:nowrap;">{{ $texto }}</span>
          </td>
          <td style="padding:10px 16px;color:var(--text-2);text-transform:capitalize;">{{ str_replace('_', ' ', $pedido->estado) }}</td>
          <td style="padding:10px 16px;">
            <a href="{{ route('admin.pedidos-uniformes.show', $pedido->id) }}"
               style="color:var(--blue);text-decoration:none;font-weight:600;font-size:0.82rem;">Ver detalle →</a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" style="padding:32px;text-align:center;color:var(--text-3);">Aún no hay pedidos de uniformes.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
