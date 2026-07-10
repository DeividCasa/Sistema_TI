@extends('layouts.catalogo')

@section('titulo', 'Leo José | Catálogo Deportivo')

@section('sidebar-filtros')
  @include('partials.sidebar-filtros')
@endsection

@section('contenido')

  <!-- Tarjeta "Crear diseño" -->
  <div class="create-card">
    <div class="create-info">
      <h3><i class="fas fa-palette" style="color: var(--blue); margin-right: 8px;"></i> Crea tu propio diseño</h3>
      <p>Personaliza camisetas, shorts y conjuntos únicos. Totalmente a tu gusto.</p>
    </div>
    <a href="{{ route('disenios.create') }}" class="create-btn">
      Empezar ahora <i class="fas fa-arrow-right"></i>
    </a>
  </div>

  <!-- ENCABEZADO CATÁLOGO -->
  <div class="catalog-header">
    <div>
      <h2>Ropas disponibles</h2>
      <p style="color:var(--text-2);" id="contador-resultados">{{ $plantillas->count() }} modelos disponibles</p>
    </div>
  </div>

  <!-- GRID DE PRODUCTOS -->
  <div class="products-grid" id="grid-productos">
    @forelse($plantillas as $plantilla)
      <a href="{{ route('producto.ver', $plantilla->id) }}"
         class="product-card producto-item"
         data-tipo="{{ $plantilla->tipo_prenda }}"
         data-nombre="{{ strtolower($plantilla->nombre) }}"
         data-tallas="{{ implode(',', array_map('strtolower', $plantilla->tallas ?? [])) }}"
         data-colores="{{ implode(',', array_map('strtolower', $plantilla->colores ?? [])) }}">
        <div class="product-image">
          @if($plantilla->imagen_preview)
            <img src="{{ asset('storage/'.$plantilla->imagen_preview) }}"
                 alt="{{ $plantilla->nombre }}"
                 loading="lazy">
          @else
            <div class="no-img">
              <i class="fas fa-tshirt"></i>
            </div>
          @endif
          <span class="badge-new">{{ $plantilla->tipo_prenda }}</span>
        </div>
        <div class="product-body">
          <div class="product-name">{{ $plantilla->nombre }}</div>
          <div class="product-price">${{ number_format($plantilla->precio, 2) }}</div>
          @if(!empty($plantilla->colores))
            <div class="product-colors">
              @foreach(array_slice($plantilla->colores, 0, 5) as $color)
                <span class="color-dot" style="background:{{ $color }};"></span>
              @endforeach
            </div>
          @endif
          <div class="buy-btn">
            <i class="fas fa-shopping-cart"></i> Comprar
          </div>
        </div>
      </a>
    @empty
      <div id="sin-resultados" style="grid-column:1/-1;text-align:center;padding:48px;background:var(--bg-2);
        border:1px solid var(--border);">
        <i class="fas fa-tshirt" style="font-size:40px;color:var(--text-3);display:block;margin-bottom:12px;"></i>
        <p style="color:var(--text-3);font-size:0.88rem;">No hay plantillas disponibles aún.</p>
      </div>
    @endforelse
  </div>

  <div id="sin-resultados-filtro" style="display:none; grid-column:1/-1; text-align:center; padding:48px; background:var(--bg-2); border:1px solid var(--border); margin-top:16px;">
    <i class="fas fa-search" style="font-size:40px;color:var(--text-3);display:block;margin-bottom:12px;"></i>
    <p style="color:var(--text-3);font-size:0.88rem;">No se encontraron productos con esos filtros.</p>
  </div>

@endsection
