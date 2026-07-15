@php
  $categoriasGenerales = [
    'camiseta' => ['label' => 'Camisetas', 'icon' => 'fa-tshirt'],
    'short'    => ['label' => 'Shorts', 'icon' => 'fa-socks'],
    'conjunto' => ['label' => 'Conjuntos', 'icon' => 'fa-vest'],
    'uniforme' => ['label' => 'Uniformes Escolares', 'icon' => 'fa-graduation-cap'],
    'chompa'   => ['label' => 'Chompas', 'icon' => 'fa-mitten'],
    'otro'     => ['label' => 'Otros', 'icon' => 'fa-ellipsis-h'],
  ];
@endphp

<div class="filter-group">
  <h6><i class="fas fa-layer-group"></i> Categorías</h6>
  <ul id="filtros-categoria-gen">
    <li data-tipo="todos" class="{{ $categoriaActiva === 'todos' ? 'filtro-activo' : '' }}" onclick="filtrarCategoriaGeneral('todos', this)">
      <i class="fas fa-th-large"></i> Todas
    </li>
    @foreach($categoriasGenerales as $tipo => $info)
      <li data-tipo="{{ $tipo }}" class="{{ $categoriaActiva === $tipo ? 'filtro-activo' : '' }}" onclick="filtrarCategoriaGeneral('{{ $tipo }}', this)">
        <i class="fas {{ $info['icon'] }}"></i> {{ $info['label'] }}
      </li>
    @endforeach
  </ul>
</div>

<div class="filter-group">
  <h6><i class="fas fa-chart-simple"></i> Tallas</h6>
  <ul id="filtros-talla-gen">
    <li data-talla="todos" class="{{ $tallaActiva === 'todos' ? 'filtro-activo' : '' }}" onclick="filtrarTallaGeneral('todos', this)">
      <i class="fas fa-ruler"></i> Todas
    </li>
    @foreach($tallasDisponibles as $talla)
      <li data-talla="{{ $talla }}" class="{{ $tallaActiva === $talla ? 'filtro-activo' : '' }}" onclick="filtrarTallaGeneral('{{ $talla }}', this)">
        <i class="fas fa-tag"></i> {{ strtoupper($talla) }}
      </li>
    @endforeach
  </ul>
</div>

<div class="filter-group">
  <h6><i class="fas fa-dollar-sign"></i> Precio</h6>
  <div class="price-range-label">
    <span id="precio-label-min-gen">${{ (int) $precioMinActivo }}</span>
    <span id="precio-label-max-gen">${{ (int) $precioMaxActivo }}</span>
  </div>
  <div class="price-slider">
    <div class="price-track"></div>
    <div class="price-track-fill" id="price-track-fill-gen"></div>
    <input type="range" id="precio-min-gen" min="{{ (int) $precioGlobalMin }}" max="{{ (int) $precioGlobalMax }}" value="{{ (int) $precioMinActivo }}" step="1" oninput="onPrecioGeneralInput()">
    <input type="range" id="precio-max-gen" min="{{ (int) $precioGlobalMin }}" max="{{ (int) $precioGlobalMax }}" value="{{ (int) $precioMaxActivo }}" step="1" oninput="onPrecioGeneralInput()">
  </div>
</div>

@push('scripts')
<script>
  const CATALOGO_GENERAL_URL = @json(route('cliente.catalogo.index'));
  let catGenActual = @json($categoriaActiva);
  let tallaGenActual = @json($tallaActiva);
  let precioGenMin = {{ (float) $precioMinActivo }};
  let precioGenMax = {{ (float) $precioMaxActivo }};
  const precioGenGlobalMin = {{ (int) $precioGlobalMin }};
  const precioGenGlobalMax = {{ (int) $precioGlobalMax }};
  let offsetGen = {{ (int) $mostrados }};
  let debounceGenTimer = null;

  (function inicializarBarraPrecioGeneral() {
    const fill = document.getElementById('price-track-fill-gen');
    if (fill && precioGenGlobalMax > precioGenGlobalMin) {
      const pctMin = ((precioGenMin - precioGenGlobalMin) / (precioGenGlobalMax - precioGenGlobalMin)) * 100;
      const pctMax = ((precioGenMax - precioGenGlobalMin) / (precioGenGlobalMax - precioGenGlobalMin)) * 100;
      fill.style.left = pctMin + '%';
      fill.style.right = (100 - pctMax) + '%';
    }
  })();

  function filtrarCategoriaGeneral(tipo, el) {
    catGenActual = tipo;
    document.querySelectorAll('#filtros-categoria-gen li').forEach(li => li.classList.remove('filtro-activo'));
    el.classList.add('filtro-activo');
    recargarCatalogoGeneral();
  }

  function filtrarTallaGeneral(talla, el) {
    tallaGenActual = talla;
    document.querySelectorAll('#filtros-talla-gen li').forEach(li => li.classList.remove('filtro-activo'));
    el.classList.add('filtro-activo');
    recargarCatalogoGeneral();
  }

  function onPrecioGeneralInput() {
    const inputMin = document.getElementById('precio-min-gen');
    const inputMax = document.getElementById('precio-max-gen');
    if (parseFloat(inputMin.value) > parseFloat(inputMax.value)) inputMin.value = inputMax.value;
    if (parseFloat(inputMax.value) < parseFloat(inputMin.value)) inputMax.value = inputMin.value;
    precioGenMin = parseFloat(inputMin.value);
    precioGenMax = parseFloat(inputMax.value);

    document.getElementById('precio-label-min-gen').textContent = '$' + precioGenMin.toFixed(0);
    document.getElementById('precio-label-max-gen').textContent = '$' + precioGenMax.toFixed(0);

    const fill = document.getElementById('price-track-fill-gen');
    if (fill && precioGenGlobalMax > precioGenGlobalMin) {
      const pctMin = ((precioGenMin - precioGenGlobalMin) / (precioGenGlobalMax - precioGenGlobalMin)) * 100;
      const pctMax = ((precioGenMax - precioGenGlobalMin) / (precioGenGlobalMax - precioGenGlobalMin)) * 100;
      fill.style.left = pctMin + '%';
      fill.style.right = (100 - pctMax) + '%';
    }

    clearTimeout(debounceGenTimer);
    debounceGenTimer = setTimeout(recargarCatalogoGeneral, 300);
  }

  // Llamada por el buscador del topbar (oninput="filtrarProductos()")
  function filtrarProductos() {
    clearTimeout(debounceGenTimer);
    debounceGenTimer = setTimeout(recargarCatalogoGeneral, 300);
  }

  function construirParametrosGeneral(offset) {
    const buscador = document.getElementById('buscador');
    const params = new URLSearchParams();
    params.set('categoria', catGenActual);
    params.set('talla', tallaGenActual);
    params.set('precio_min', precioGenMin);
    params.set('precio_max', precioGenMax);
    if (buscador && buscador.value.trim() !== '') params.set('q', buscador.value.trim());
    params.set('offset', offset);
    params.set('fragmento', 1);
    return params;
  }

  function recargarCatalogoGeneral() {
    const grid = document.getElementById('grid-productos');
    if (!grid) return;
    grid.style.opacity = '0.5';

    fetch(CATALOGO_GENERAL_URL + '?' + construirParametrosGeneral(0).toString(), {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
      .then(r => r.json())
      .then(data => {
        grid.innerHTML = data.html;
        grid.style.opacity = '1';
        offsetGen = data.mostrados;
        actualizarContadorYBotonGeneral(data.total, data.mostrados);
      })
      .catch(() => { grid.style.opacity = '1'; });
  }

  function cargarMasGeneral() {
    const boton = document.getElementById('btn-cargar-mas');
    const textoOriginal = boton ? boton.textContent : '';
    if (boton) { boton.disabled = true; boton.textContent = 'Cargando…'; }

    fetch(CATALOGO_GENERAL_URL + '?' + construirParametrosGeneral(offsetGen).toString(), {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
      .then(r => r.json())
      .then(data => {
        document.getElementById('grid-productos').insertAdjacentHTML('beforeend', data.html);
        offsetGen = data.mostrados;
        actualizarContadorYBotonGeneral(data.total, data.mostrados);
      })
      .finally(() => {
        if (boton) { boton.disabled = false; boton.textContent = textoOriginal; }
      });
  }

  function actualizarContadorYBotonGeneral(total, mostrados) {
    const totalEl = document.getElementById('cantidad-total');
    const mostradoEl = document.getElementById('cantidad-mostrada');
    if (totalEl) totalEl.textContent = total;
    if (mostradoEl) mostradoEl.textContent = mostrados;

    const wrap = document.getElementById('cargar-mas-wrap');
    const boton = document.getElementById('btn-cargar-mas');
    const sinResultados = document.getElementById('sin-resultados-filtro');

    if (sinResultados) sinResultados.style.display = total === 0 ? 'block' : 'none';

    if (mostrados >= total) {
      if (wrap) wrap.style.display = 'none';
    } else {
      if (wrap) wrap.style.display = '';
      if (boton) boton.textContent = 'Ver más (quedan ' + (total - mostrados) + ')';
    }
  }
</script>
@endpush
