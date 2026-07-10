@extends('layouts.catalogo')

@section('titulo', 'Uniformes Escolares')
@section('mis-pedidos-route', route('cliente.uniformes.mis-pedidos'))

@section('sidebar-filtros')
  @include('partials.sidebar-filtros')
@endsection

@section('contenido')

@if(session('success'))
<div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    ✓ {{ session('success') }}
</div>
@endif

<div class="catalog-header">
  <div>
    <h2>Uniformes Escolares</h2>
    <p style="color:var(--text-2);" id="contador-resultados">{{ $uniformes->count() }} modelos disponibles</p>
  </div>
</div>

<div class="products-grid" id="grid-productos">
  @forelse($uniformes as $uniforme)

    @php
      $precios = $uniforme->tallas->where('disponible', 1)->pluck('precio');
      $tallasUniforme = $uniforme->tallas->where('disponible', 1)->pluck('talla')->map(fn($t) => strtolower($t))->implode(',');
    @endphp

    <a href="{{ route('cliente.uniformes.show', $uniforme->id) }}"
       class="product-card producto-item"
       data-tipo="uniforme"
       data-nombre="{{ strtolower($uniforme->nombre) }}"
       data-tallas="{{ $tallasUniforme }}">
      <div class="product-image">
        <img src="{{ asset('storage/' . $uniforme->imagen) }}"
             alt="{{ $uniforme->nombre }}"
             loading="lazy">
      </div>
      <div class="product-body">
        <div class="product-name">{{ $uniforme->nombre }}</div>
        <div style="font-size:0.8rem;color:var(--text-3);margin-bottom:8px;">Tela: {{ $uniforme->tipo_tela }}</div>

        @if($precios->count() > 0)
          <div class="product-price">
            @if($precios->min() == $precios->max())
              ${{ number_format($precios->min(), 2) }}
            @else
              Desde ${{ number_format($precios->min(), 2) }}
            @endif
          </div>

          <div class="product-sizes">
            @foreach($uniforme->tallas->where('disponible', 1) as $talla)
              <span>{{ $talla->talla }}</span>
            @endforeach
          </div>
        @endif

        <div class="buy-btn">Ver y elegir talla</div>
      </div>
    </a>

  @empty
    <div style="grid-column:1/-1;text-align:center;padding:48px;background:var(--bg-2);border:1px solid var(--border);">
      <i class="fas fa-vest" style="font-size:40px;color:var(--text-3);display:block;margin-bottom:12px;"></i>
      <p style="color:var(--text-3);font-size:0.88rem;">Aún no hay uniformes escolares disponibles. Vuelve pronto.</p>
    </div>
  @endforelse
</div>

<div id="sin-resultados-filtro" style="display:none; grid-column:1/-1; text-align:center; padding:48px; background:var(--bg-2); border:1px solid var(--border); margin-top:16px;">
  <i class="fas fa-search" style="font-size:40px;color:var(--text-3);display:block;margin-bottom:12px;"></i>
  <p style="color:var(--text-3);font-size:0.88rem;">No se encontraron uniformes con esos filtros.</p>
</div>

@endsection
