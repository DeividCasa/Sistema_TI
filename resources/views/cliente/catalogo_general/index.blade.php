@extends('layouts.catalogo')

@section('titulo', 'Toda la ropa')

@section('contenido')

<br />
<br />
<br />
<!-- breadcrumb -->
<div class="container">
  <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
    <a href="{{ route('inicio') }}" class="stext-109 cl8 hov-cl1 trans-04">
      Inicio <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
    </a>
    <span class="stext-109 cl4">Toda la ropa</span>
  </div>
</div>

<!-- Crear diseño -->
<div class="container p-t-20">
  <div class="flex-w flex-sb-m" style="background:#F8F8F8;border-radius:8px;padding:24px 28px;gap:16px;">
    <div>
      <h4 class="mtext-105 cl2" style="font-size:1.15rem;"><i class="fas fa-palette" style="color:var(--blue);margin-right:8px;"></i>Crea tu propio diseño</h4>
      <p class="stext-102 cl6 p-t-6">Diseña tu camiseta, conjunto deportivo, chompa o pantalón a tu manera.</p>
    </div>
    <a href="{{ route('disenios.create') }}" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
      Empezar ahora
    </a>
  </div>
</div>

<!-- Product -->
<section class="bg0 p-t-40 p-b-140">
  <div class="container">
    <div class="p-b-10">
      <h3 class="ltext-103 cl5">Toda la ropa</h3>
      <p class="stext-107 cl6 p-t-10">
        Mostrando <span id="cantidad-mostrada">{{ $mostrados }}</span> de <span id="cantidad-total">{{ $total }}</span> productos
      </p>
    </div>

    <div class="flex-w flex-sb-m p-b-52">
      <div class="flex-w flex-l-m filter-tope-group m-tb-10">
        <button type="button" class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 {{ $categoriaActiva === 'todos' ? 'how-active1' : '' }}" data-tipo="todos" onclick="filtrarCategoriaGeneral('todos', this)">Todas</button>
        <button type="button" class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 {{ $categoriaActiva === 'camiseta' ? 'how-active1' : '' }}" data-tipo="camiseta" onclick="filtrarCategoriaGeneral('camiseta', this)">Camisetas</button>
        <button type="button" class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 {{ $categoriaActiva === 'uniforme' ? 'how-active1' : '' }}" data-tipo="uniforme" onclick="filtrarCategoriaGeneral('uniforme', this)">Uniformes</button>
        <button type="button" class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 {{ $categoriaActiva === 'chompa' ? 'how-active1' : '' }}" data-tipo="chompa" onclick="filtrarCategoriaGeneral('chompa', this)">Chompas</button>
      </div>

      <div class="flex-w flex-c-m m-tb-10">
        <div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
          <i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
          <i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
          Filtros
        </div>

        <div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
          <i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
          <i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
          Buscar
        </div>
      </div>

      <!-- Search product -->
      <div class="dis-none panel-search w-full p-t-10 p-b-15">
        <div class="bor8 dis-flex p-l-15">
          <button type="button" class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04" onclick="filtrarProductos()">
            <i class="zmdi zmdi-search"></i>
          </button>
          <input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" id="buscador" name="q" placeholder="Buscar productos..." oninput="filtrarProductos()">
        </div>
      </div>

      <!-- Filter -->
      <div class="dis-none panel-filter w-full p-t-10">
        @include('partials.catalogo-general-filtros', [
          'tallasDisponibles' => $tallasDisponibles,
          'precioGlobalMin'   => $precioGlobalMin,
          'precioGlobalMax'   => $precioGlobalMax,
          'mostrados'         => $mostrados,
          'categoriaActiva'   => $categoriaActiva,
          'tallaActiva'       => $tallaActiva,
          'generoActivo'      => $generoActivo,
          'precioMinActivo'   => $precioMinActivo,
          'precioMaxActivo'   => $precioMaxActivo,
        ])
      </div>
    </div>

    <div class="row" id="grid-productos">
      @include('cliente.catalogo_general._grid', ['productos' => $productos])
    </div>

    <div id="sin-resultados-filtro" class="w-full txt-center p-t-40" style="display:{{ $total === 0 ? 'block' : 'none' }};">
      <i class="fa fa-search" style="font-size:40px;color:#ccc;display:block;margin-bottom:12px;"></i>
      <p class="stext-107 cl6">No se encontraron productos con esos filtros.</p>
    </div>

    <!-- Load more -->
    <div class="flex-c-m flex-w w-full p-t-45" id="cargar-mas-wrap" style="{{ $mostrados >= $total ? 'display:none;' : '' }}">
      <button type="button" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04" id="btn-cargar-mas" onclick="cargarMasGeneral()">
        Ver más (quedan {{ $total - $mostrados }})
      </button>
    </div>
  </div>
</section>

@endsection
