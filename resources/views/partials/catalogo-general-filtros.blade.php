@php
  $categoriasGenerales = [
    'camiseta' => 'Camisetas',
    'short'    => 'Shorts',
    'conjunto' => 'Conjuntos',
    'uniforme' => 'Uniformes Escolares',
    'chompa'   => 'Chompas',
    'otro'     => 'Otros',
  ];
@endphp

<div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
  <div class="filter-col1 p-r-15 p-b-27">
    <div class="mtext-102 cl2 p-b-15">Categoría</div>
    <ul id="filtros-categoria-gen">
      <li class="p-b-6">
        <a href="javascript:void(0)" class="filter-link stext-106 trans-04 {{ $categoriaActiva === 'todos' ? 'filter-link-active' : '' }}" data-tipo="todos" onclick="filtrarCategoriaGeneral('todos', this)">Todas</a>
      </li>
      @foreach($categoriasGenerales as $tipo => $label)
        <li class="p-b-6">
          <a href="javascript:void(0)" class="filter-link stext-106 trans-04 {{ $categoriaActiva === $tipo ? 'filter-link-active' : '' }}" data-tipo="{{ $tipo }}" onclick="filtrarCategoriaGeneral('{{ $tipo }}', this)">{{ $label }}</a>
        </li>
      @endforeach
    </ul>
  </div>

  <div class="filter-col2 p-r-15 p-b-27">
    <div class="mtext-102 cl2 p-b-15">Género</div>
    <ul id="filtros-genero-gen">
      <li class="p-b-6"><a href="javascript:void(0)" class="filter-link stext-106 trans-04 {{ $generoActivo === 'todos' ? 'filter-link-active' : '' }}" data-genero="todos" onclick="filtrarGeneroGeneral('todos', this)">Todos</a></li>
      <li class="p-b-6"><a href="javascript:void(0)" class="filter-link stext-106 trans-04 {{ $generoActivo === 'hombre' ? 'filter-link-active' : '' }}" data-genero="hombre" onclick="filtrarGeneroGeneral('hombre', this)">Para Hombre</a></li>
      <li class="p-b-6"><a href="javascript:void(0)" class="filter-link stext-106 trans-04 {{ $generoActivo === 'mujer' ? 'filter-link-active' : '' }}" data-genero="mujer" onclick="filtrarGeneroGeneral('mujer', this)">Para Mujer</a></li>
      <li class="p-b-6"><a href="javascript:void(0)" class="filter-link stext-106 trans-04 {{ $generoActivo === 'unisex' ? 'filter-link-active' : '' }}" data-genero="unisex" onclick="filtrarGeneroGeneral('unisex', this)">Unisex</a></li>
    </ul>
  </div>

  <div class="filter-col3 p-r-15 p-b-27">
    <div class="mtext-102 cl2 p-b-15">Talla</div>
    <ul id="filtros-talla-gen">
      <li class="p-b-6"><a href="javascript:void(0)" class="filter-link stext-106 trans-04 {{ $tallaActiva === 'todos' ? 'filter-link-active' : '' }}" data-talla="todos" onclick="filtrarTallaGeneral('todos', this)">Todas</a></li>
      @foreach($tallasDisponibles as $talla)
        <li class="p-b-6"><a href="javascript:void(0)" class="filter-link stext-106 trans-04 {{ $tallaActiva === $talla ? 'filter-link-active' : '' }}" data-talla="{{ $talla }}" onclick="filtrarTallaGeneral('{{ $talla }}', this)">{{ strtoupper($talla) }}</a></li>
      @endforeach
    </ul>
  </div>

  <div class="filter-col4 p-b-27">
    <div class="mtext-102 cl2 p-b-15">
      Precio: <span id="precio-label-min-gen">${{ (int) $precioMinActivo }}</span> — <span id="precio-label-max-gen">${{ (int) $precioMaxActivo }}</span>
    </div>
    <div class="price-slider" style="position:relative;height:20px;margin:14px 2px 4px;">
      <div class="price-track" style="position:absolute;top:8px;left:0;right:0;height:4px;background:#ebebeb;border-radius:4px;"></div>
      <div class="price-track-fill" id="price-track-fill-gen" style="position:absolute;top:8px;height:4px;background:var(--blue);border-radius:4px;"></div>
      <input type="range" id="precio-min-gen" min="{{ (int) $precioGlobalMin }}" max="{{ (int) $precioGlobalMax }}" value="{{ (int) $precioMinActivo }}" step="1" oninput="onPrecioGeneralInput()" style="position:absolute;top:0;left:0;width:100%;height:20px;margin:0;background:transparent;pointer-events:none;-webkit-appearance:none;appearance:none;">
      <input type="range" id="precio-max-gen" min="{{ (int) $precioGlobalMin }}" max="{{ (int) $precioGlobalMax }}" value="{{ (int) $precioMaxActivo }}" step="1" oninput="onPrecioGeneralInput()" style="position:absolute;top:0;left:0;width:100%;height:20px;margin:0;background:transparent;pointer-events:none;-webkit-appearance:none;appearance:none;">
    </div>
  </div>
</div>

<style>
  .price-slider input[type="range"]::-webkit-slider-thumb { -webkit-appearance:none; pointer-events:auto; width:16px; height:16px; border-radius:50%; background:var(--blue); border:2px solid #fff; box-shadow:0 1px 4px rgba(0,0,0,0.3); cursor:pointer; margin-top:2px; }
  .price-slider input[type="range"]::-moz-range-thumb { pointer-events:auto; width:14px; height:14px; border-radius:50%; background:var(--blue); border:2px solid #fff; cursor:pointer; }
</style>

@push('scripts')
<script>
  const CATALOGO_GENERAL_URL = @json(route('cliente.catalogo.index'));
  let catGenActual = @json($categoriaActiva);
  let tallaGenActual = @json($tallaActiva);
  let generoGenActual = @json($generoActivo);
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
    document.querySelectorAll('#filtros-categoria-gen a, .filter-tope-group button').forEach(n => n.classList.remove('filter-link-active', 'how-active1'));
    if (el.tagName === 'BUTTON') el.classList.add('how-active1'); else el.classList.add('filter-link-active');
    // Sincroniza el otro selector de categoría (tabs arriba <-> panel de filtros)
    document.querySelectorAll('.filter-tope-group button[data-tipo="' + tipo + '"]').forEach(b => b.classList.add('how-active1'));
    const linkPanel = document.querySelector('#filtros-categoria-gen a[data-tipo="' + tipo + '"]');
    if (linkPanel) linkPanel.classList.add('filter-link-active');
    recargarCatalogoGeneral();
  }

  function filtrarTallaGeneral(talla, el) {
    tallaGenActual = talla;
    document.querySelectorAll('#filtros-talla-gen a').forEach(a => a.classList.remove('filter-link-active'));
    el.classList.add('filter-link-active');
    recargarCatalogoGeneral();
  }

  function filtrarGeneroGeneral(genero, el) {
    generoGenActual = genero;
    document.querySelectorAll('#filtros-genero-gen a').forEach(a => a.classList.remove('filter-link-active'));
    el.classList.add('filter-link-active');
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

  // Buscador (panel "Search" de la plantilla)
  function filtrarProductos() {
    clearTimeout(debounceGenTimer);
    debounceGenTimer = setTimeout(recargarCatalogoGeneral, 300);
  }

  function construirParametrosGeneral(offset) {
    const buscador = document.getElementById('buscador');
    const params = new URLSearchParams();
    params.set('categoria', catGenActual);
    params.set('talla', tallaGenActual);
    params.set('genero', generoGenActual);
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
