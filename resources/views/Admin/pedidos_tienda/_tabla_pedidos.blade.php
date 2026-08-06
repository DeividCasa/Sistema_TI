{{-- Tabla reutilizable de pedidos unificados (uniforme/chompa/ropa/combinado/camiseta).
     Espera $pedidos (colección de PedidoTiendaController::pedidosUnificados()).
     Se usa tanto en el listado general "Pedidos tienda" como en la ficha de un cliente. --}}
@if($pedidos->isEmpty())
  <div class="card empty-state reveal">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="width:40px;height:40px;margin:0 auto 10px;display:block;">
      <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
    </svg>
    <p>Aún no hay pedidos.</p>
  </div>
@else
  <div class="card reveal" style="overflow:auto;">
    <table class="admin-table" id="tablaPedidos" style="min-width:1050px;">
      <colgroup>
        <col class="col-codigo">
        <col class="col-tipo">
        <col class="col-cliente">
        <col class="col-total">
        <col class="col-adelanto">
        <col class="col-estado">
        <col class="col-verdetalle">
        <col class="col-pagocompleto">
      </colgroup>
      <thead>
        <tr>
          <th>Código</th>
          <th>Tipo</th>
          <th>Cliente</th>
          <th>Total</th>
          <th>Adelanto (50%)</th>
          <th>Estado pago</th>
          <th>Informacion</th>
          <th>Pago completo</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pedidos as $entrada)
          @php
            $pedido = $entrada['pedido'];
            $pagos = [
              'pendiente'             => ['badge-warning', 'Pago pendiente'],
              'adelanto_enviado'      => ['badge-info', 'Adelanto enviado'],
              'adelanto_verificado'   => ['badge-success', 'Adelanto verificado'],
              'pago_completo_enviado' => ['badge-info', 'Pago completo enviado'],
              'saldo_enviado'         => ['badge-info', 'Saldo enviado'],
              'pagado_completo'       => ['badge-success', 'Pagado completo'],
            ];
            [$claseBadge, $texto] = $pagos[$pedido->estado_pago] ?? ['badge-neutral', $pedido->estado_pago];

            $rutaPagoCompleto = match($entrada['tipo']) {
              'Combinado' => 'admin.pedidos-tienda.pago-completo',
              'Uniforme'  => 'admin.pedidos-uniformes.pago-completo',
              'Chompa'    => 'admin.pedidos-chompas.pago-completo',
              'Ropa'      => 'admin.pedidos-plantillas.pago-completo',
              'Camiseta'  => 'admin.pedidos.pago-completo',
            };
            $rutaDetalle = match($entrada['tipo']) {
              'Combinado' => 'admin.pedidos-tienda.show',
              'Uniforme'  => 'admin.pedidos-uniformes.show',
              'Chompa'    => 'admin.pedidos-chompas.show',
              'Ropa'      => 'admin.pedidos-plantillas.show',
              'Camiseta'  => 'admin.pedidos.show',
            };
            // El pedido combinado (PedidoMaestro) no tiene un "estado" de
            // producción propio: cada hijo (ropa/uniforme/chompa) lleva el suyo.
            // Se marca como "combinado" para el filtro de estado en vez de
            // fallar o mostrar un valor incorrecto.
            $estadoFila = $pedido->estado ?? 'combinado';
          @endphp
          <tr class="pedido-fila"
              data-tipo="{{ strtolower($entrada['tipo']) }}"
              data-estado="{{ $estadoFila }}"
              data-pago="{{ $pedido->estado_pago }}"
              data-codigo="{{ strtolower($pedido->codigo) }}"
              data-cliente="{{ strtolower($pedido->cliente->nombre . ' ' . $pedido->cliente->apellido . ' ' . $pedido->cliente->email) }}">
            <td class="cell-strong celda-codigo" style="color:var(--blue);">
              {{ $pedido->codigo }}
              @if($entrada['nuevo'])
                <span class="badge-nuevo">Nuevo</span>
              @endif
            </td>
            <td>
              <span style="background:var(--bg-3);border:1px solid var(--border);padding:3px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;">
                {{ $entrada['tipo'] }}
              </span>
            </td>
            <td>
              <div class="cell-strong cliente-nombre" title="{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}">{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</div>
              <div class="cell-muted cliente-email" title="{{ $pedido->cliente->email }}">{{ $pedido->cliente->email }}</div>
            </td>
            <td class="cell-strong">${{ number_format($pedido->precio_total, 2) }}</td>
            <td>${{ number_format($pedido->precio_adelanto, 2) }}</td>
            <td>
              <span class="badge {{ $claseBadge }}">{{ $texto }}</span>
            </td>
            <td class="cell-actions">
              <a href="{{ route($rutaDetalle, $pedido->id) }}">Ver detalle</a>
            </td>
            <td class="cell-actions">
              @if($pedido->estado_pago !== 'pagado_completo')
                <form action="{{ route($rutaPagoCompleto, $pedido->id) }}" method="POST" data-confirm="¿Marcar este pedido como pagado por completo?">
                  @csrf
                  <button type="submit" class="btn-marcar-pagado">Marcar pagado</button>
                </form>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div id="sinResultados" style="display:none;text-align:center;padding:2rem;color:var(--text-3);">
    No se encontraron pedidos con los filtros actuales.
  </div>
@endif

@push('scripts')
<script>
  (function () {
    // En el listado general hay un buscador propio (#buscarPedido) en la barra de
    // filtros, así que se oculta el buscador por defecto de DataTables ahí; en la
    // ficha del cliente (sin esa barra) se deja el buscador propio de DataTables.
    const tieneFiltrosPropios = !!document.getElementById('buscarPedido');
    const tabla = crearDataTable('#tablaPedidos', {
      layout: { topStart: 'buttons', topEnd: tieneFiltrosPropios ? null : 'search' },
      columnDefs: [{ orderable: false, targets: [6, 7] }],
      buttons: [
        { extend: 'excelHtml5', text: DT_ICONO_EXCEL + ' Excel', className: 'dt-button dt-btn-excel', exportOptions: { columns: [0, 1, 2, 3, 4, 5] } },
        { extend: 'pdfHtml5', text: DT_ICONO_PDF + ' PDF', className: 'dt-button dt-btn-pdf', orientation: 'landscape', exportOptions: { columns: [0, 1, 2, 3, 4, 5] } },
        { extend: 'csvHtml5', text: DT_ICONO_CSV + ' CSV', className: 'dt-button dt-btn-csv', exportOptions: { columns: [0, 1, 2, 3, 4, 5] } },
      ],
    });
    if (!tabla) return;

    // Los filtros de tipo/estado/pago no son columnas visibles (son atributos
    // data-* en la fila), así que se conectan como un filtro custom de DataTables
    // en vez de columna de búsqueda.
    DataTable.ext.search.push(function (settings, searchData, index) {
      if (settings.nTable.id !== 'tablaPedidos') return true;
      const fila = tabla.row(index).node();
      if (!fila) return true;

      const tipoFiltro = document.getElementById('filtroTipo')?.value ?? 'todos';
      const estadoFiltro = document.getElementById('filtroEstado')?.value ?? 'todos';
      const pagoFiltro = document.getElementById('filtroPago')?.value ?? 'todos';

      if (tipoFiltro !== 'todos' && fila.getAttribute('data-tipo') !== tipoFiltro) return false;
      if (estadoFiltro !== 'todos' && fila.getAttribute('data-estado') !== estadoFiltro) return false;
      if (pagoFiltro !== 'todos' && fila.getAttribute('data-pago') !== pagoFiltro) return false;
      return true;
    });

    ['filtroTipo', 'filtroEstado', 'filtroPago'].forEach(id => {
      document.getElementById(id)?.addEventListener('change', () => tabla.draw());
    });
    document.getElementById('buscarPedido')?.addEventListener('input', function () {
      tabla.search(this.value).draw();
    });
  })();
</script>
@endpush
