@extends('layouts.catalogo')

@section('titulo', 'Toda la ropa')

@section('sidebar-filtros')
  @include('partials.catalogo-general-filtros', [
    'tallasDisponibles' => $tallasDisponibles,
    'precioGlobalMin'   => $precioGlobalMin,
    'precioGlobalMax'   => $precioGlobalMax,
    'mostrados'         => $mostrados,
    'categoriaActiva'   => $categoriaActiva,
    'tallaActiva'       => $tallaActiva,
    'precioMinActivo'   => $precioMinActivo,
    'precioMaxActivo'   => $precioMaxActivo,
  ])
@endsection

@section('contenido')

<div class="create-card">
  <div class="create-info">
    <h3><i class="fas fa-palette" style="color: var(--blue); margin-right: 8px;"></i> Crea tu propio diseño</h3>
    <p>Diseña tu camiseta, conjunto deportivo, chompa o pantalón a tu manera. Elige colores, logos y textos, y hazlo único.</p>
  </div>
  <a href="{{ route('disenios.create') }}" class="create-btn">
    Empezar ahora <i class="fas fa-arrow-right"></i>
  </a>
</div>

<div class="catalog-header">
  <div>
    <h2>Toda la ropa</h2>
    <p style="color:var(--text-2);" id="contador-resultados">
      Mostrando <span id="cantidad-mostrada">{{ $mostrados }}</span> de <span id="cantidad-total">{{ $total }}</span> productos
    </p>
  </div>
</div>

<div class="products-grid" id="grid-productos">
  @include('cliente.catalogo_general._grid', ['productos' => $productos])
</div>

<div id="sin-resultados-filtro" style="display:{{ $total === 0 ? 'block' : 'none' }}; grid-column:1/-1; text-align:center; padding:48px; background:var(--bg-2); border:1px solid var(--border); margin-top:16px;">
  <i class="fas fa-search" style="font-size:40px;color:var(--text-3);display:block;margin-bottom:12px;"></i>
  <p style="color:var(--text-3);font-size:0.88rem;">No se encontraron productos con esos filtros.</p>
</div>

<div id="cargar-mas-wrap" style="text-align:center;margin-top:28px;{{ $mostrados >= $total ? 'display:none;' : '' }}">
  <button type="button" class="btn-secondary" id="btn-cargar-mas" onclick="cargarMasGeneral()">
    Ver más (quedan {{ $total - $mostrados }})
  </button>
</div>

@endsection
