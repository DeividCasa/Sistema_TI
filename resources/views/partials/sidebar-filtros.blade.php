@php
  $plantillasFiltro = $plantillas ?? \App\Models\Plantilla::where('activa', 1)->get();
  $tallasDisponibles = $plantillasFiltro->flatMap(fn($p) => $p->tallas ?? [])->unique()->values();
  $coloresDisponibles = $plantillasFiltro->flatMap(fn($p) => $p->colores ?? [])->unique()->values();
  $nombreColores = [
    '#2563EB' => 'Azul',
    '#DC2626' => 'Rojo',
    '#16A34A' => 'Verde',
    '#0F172A' => 'Negro',
    '#FFFFFF' => 'Blanco',
    '#D97706' => 'Dorado',
  ];
  $enCatalogo = request()->routeIs('cliente.inicio');
@endphp

<div class="filter-group">
  <h6><i class="fas fa-layer-group"></i> Categorías</h6>
  <ul id="filtros-categoria">
    @if($enCatalogo)
      <li data-tipo="camiseta" class="filtro-activo" onclick="filtrarCategoria('camiseta', this)">
        <i class="fas fa-tshirt"></i> Camisetas
      </li>
      <li data-tipo="conjunto" onclick="filtrarCategoria('conjunto', this)">
        <i class="fas fa-vest"></i> Conjuntos
      </li>
    @else
      <li onclick="window.location.href='{{ route('cliente.inicio') }}'">
        <i class="fas fa-tshirt"></i> Camisetas
      </li>
      <li onclick="window.location.href='{{ route('cliente.inicio') }}'">
        <i class="fas fa-vest"></i> Conjuntos
      </li>
    @endif
    <li onclick="window.location.href='{{ route('cliente.uniformes.index') }}'">
      <i class="fas fa-vest"></i> Uniformes Escolares
    </li>
    <li onclick="window.location.href='{{ route('cliente.chompas.index') }}'">
      <i class="fas fa-mitten"></i> Chompas
    </li>
    @if($enCatalogo)
      <li data-tipo="otro" onclick="filtrarCategoria('otro', this)">
        <i class="fas fa-ellipsis-h"></i> Otros
      </li>
    @else
      <li onclick="window.location.href='{{ route('cliente.inicio') }}'">
        <i class="fas fa-ellipsis-h"></i> Otros
      </li>
    @endif
  </ul>
</div>
<div class="filter-group">
  <h6><i class="fas fa-chart-simple"></i> Tallas</h6>
  <ul id="filtros-talla">
    <li data-talla="todos" class="filtro-activo" onclick="filtrarTalla('todos', this)">
      <i class="fas fa-ruler"></i> Todas
    </li>
    @foreach($tallasDisponibles as $talla)
      <li data-talla="{{ strtolower($talla) }}" onclick="filtrarTalla('{{ strtolower($talla) }}', this)">
        <i class="fas fa-tag"></i> {{ $talla }}
      </li>
    @endforeach
  </ul>
</div>
<div class="filter-group">
  <h6><i class="fas fa-palette"></i> Colores</h6>
  <ul id="filtros-color">
    <li data-color="todos" class="filtro-activo" onclick="filtrarColor('todos', this)">
      <i class="fas fa-circle-half-stroke"></i> Todos
    </li>
    @foreach($coloresDisponibles as $color)
      @php $nombreColor = $nombreColores[strtoupper($color)] ?? $nombreColores[$color] ?? $color; @endphp
      <li data-color="{{ strtolower($color) }}" onclick="filtrarColor('{{ strtolower($color) }}', this)">
        <span class="color-filter-dot" style="background:{{ $color }};"></span> {{ $nombreColor }}
      </li>
    @endforeach
  </ul>
</div>

@once
@push('scripts')
<script>
  // Filtros
  let categoriaActual = 'todos';
  let tallaActual = 'todos';
  let colorActual = 'todos';

  function filtrarCategoria(tipo, el) {
    categoriaActual = tipo;
    document.querySelectorAll('#filtros-categoria li').forEach(li => li.classList.remove('filtro-activo'));
    el.classList.add('filtro-activo');
    aplicarFiltros();
  }

  function filtrarTalla(talla, el) {
    tallaActual = talla;
    document.querySelectorAll('#filtros-talla li').forEach(li => li.classList.remove('filtro-activo'));
    el.classList.add('filtro-activo');
    aplicarFiltros();
  }

  function filtrarColor(color, el) {
    colorActual = color;
    document.querySelectorAll('#filtros-color li').forEach(li => li.classList.remove('filtro-activo'));
    el.classList.add('filtro-activo');
    aplicarFiltros();
  }

  function filtrarProductos() {
    aplicarFiltros();
  }

  function aplicarFiltros() {
    const buscador = document.getElementById('buscador');
    const texto = buscador ? buscador.value.toLowerCase() : '';
    const items = document.querySelectorAll('.producto-item');
    let visibles = 0;

    items.forEach(item => {
      const tipo = item.dataset.tipo;
      const nombre = item.dataset.nombre;
      const tallas = (item.dataset.tallas || '').split(',').filter(Boolean);
      const colores = (item.dataset.colores || '').split(',').filter(Boolean);

      const coincideTexto = nombre.includes(texto);
      const coincideCategoria = (categoriaActual === 'todos' || tipo === categoriaActual);
      const coincideTalla = (tallaActual === 'todos' || tallas.includes(tallaActual));
      const coincideColor = (colorActual === 'todos' || colores.includes(colorActual));

      if (coincideTexto && coincideCategoria && coincideTalla && coincideColor) {
        item.style.display = '';
        visibles++;
      } else {
        item.style.display = 'none';
      }
    });

    const contador = document.getElementById('contador-resultados');
    if (contador) contador.textContent = visibles + ' modelos disponibles';

    const sinResultados = document.getElementById('sin-resultados-filtro');
    if (sinResultados) {
      sinResultados.style.display = visibles === 0 ? 'block' : 'none';
    }
  }
</script>
@endpush
@endonce
