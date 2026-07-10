@extends('Plantilla/Plantilla')

@section('titulo', 'Mis pedidos de uniformes')
@section('page-title', 'Mis pedidos de uniformes')

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    ✓ {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;margin-bottom:20px;">

  <div class="sec-title">Mis pedidos de uniformes escolares</div>

  <div style="display:flex;gap:10px;align-items:center;">

    <!-- BOTÓN VOLVER (AHORA REGRESA AL INDEX DE UNIFORMES) -->
    <a href="{{ route('cliente.uniformes.index') }}"
       style="background:#6B7280;color:#fff;padding:10px 18px;border-radius:8px;text-decoration:none;font-weight:600;">
      ← Volver
    </a>

    <!-- BOTÓN NUEVO PEDIDO -->
    <a href="{{ route('cliente.uniformes.index') }}"
       class="btn-primary"
       style="text-decoration:none;">
      + Nuevo pedido
    </a>

  </div>

</div>

@forelse($pedidos as $pedido)

  <div class="card reveal" style="margin-bottom:18px;overflow:hidden;">

    <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 20px;background:var(--bg-3);border-bottom:1px solid var(--border);flex-wrap:wrap;gap:10px;">

      <div>
        <span style="font-weight:800;color:var(--blue);font-size:0.95rem;">
          {{ $pedido->codigo }}
        </span>
        <span style="font-size:0.78rem;color:var(--text-3);margin-left:10px;">
          {{ $pedido->created_at->format('d/m/Y H:i') }}
        </span>
      </div>

      <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">

        <span style="background:var(--bg-2);border:1px solid var(--border);color:var(--text-2);padding:4px 12px;border-radius:6px;font-size:0.75rem;font-weight:600;text-transform:capitalize;">
          {{ str_replace('_', ' ', $pedido->estado) }}
        </span>

        @php
          $pagos = [
            'pendiente'             => ['#FEF9C3', '#A16207', 'Pago pendiente'],
            'adelanto_enviado'      => ['#DBEAFE', '#1D4ED8', 'Adelanto en revisión'],
            'adelanto_verificado'   => ['#DCFCE7', '#15803D', 'Adelanto verificado'],
            'pago_completo_enviado' => ['#DBEAFE', '#1D4ED8', 'Pago en revisión'],
            'saldo_enviado'         => ['#DBEAFE', '#1D4ED8', 'Saldo en revisión'],
            'pagado_completo'       => ['#DCFCE7', '#15803D', 'Pagado completo'],
          ];
          [$bg, $color, $texto] = $pagos[$pedido->estado_pago] ?? ['#F1F5F9', '#475569', $pedido->estado_pago];
        @endphp

        <span style="background:{{ $bg }};color:{{ $color }};padding:4px 12px;border-radius:6px;font-size:0.75rem;font-weight:600;">
          {{ $texto }}
        </span>

      </div>

    </div>

    <div style="padding:14px 20px;">

      @foreach($pedido->items as $item)
        <div style="display:flex;align-items:center;gap:14px;padding:8px 0;border-bottom:1px dashed var(--border);">

          <img src="{{ asset('storage/' . $item->uniforme->imagen) }}"
               style="width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">

          <div style="flex:1;font-size:0.85rem;">
            <div style="font-weight:600;color:var(--text-1);">
              {{ $item->uniforme->nombre }}
            </div>
            <div style="color:var(--text-3);font-size:0.76rem;">
              Talla {{ $item->talla }} × {{ $item->cantidad }}
              — ${{ number_format($item->precio_unitario, 2) }} c/u
            </div>
          </div>

          <strong style="color:var(--text-1);font-size:0.88rem;">
            ${{ number_format($item->subtotal, 2) }}
          </strong>

        </div>
      @endforeach

      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;flex-wrap:wrap;gap:10px;">

        <div style="font-size:0.85rem;color:var(--text-2);">
          Total:
          <strong style="color:var(--text-1);font-size:1rem;">
            ${{ number_format($pedido->precio_total, 2) }}
          </strong>

          <span style="margin-left:12px;">
            Adelanto (50%):
            <strong style="color:var(--blue);">
              ${{ number_format($pedido->precio_adelanto, 2) }}
            </strong>
          </span>
        </div>

        @if($pedido->estado_pago !== 'pagado_completo')
          <a href="{{ route('cliente.uniformes.pago', $pedido->id) }}"
             class="btn-primary"
             style="text-decoration:none;padding:9px 20px;font-size:0.85rem;">
             Ir a pagos
          </a>
        @endif

      </div>

    </div>

  </div>

@empty

  <div class="card card-pad reveal" style="text-align:center;padding:48px;color:var(--text-3);">
    Aún no tienes pedidos de uniformes escolares.
  </div>

@endforelse

@endsection
