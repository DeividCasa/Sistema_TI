@extends('Admin.panel_admin')

@section('titulo', 'Nuevo pedido de tienda')
@section('page-title', 'Nuevo pedido de tienda')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

@php
    $clientesData = $clientes->map(fn ($c) => [
        'id'       => $c->id,
        'label'    => trim($c->nombre.' '.$c->apellido).' — '.($c->cedula ?: 'sin cédula').' — '.($c->telefono ?: 'sin teléfono'),
        'buscable' => mb_strtolower(trim($c->nombre.' '.$c->apellido.' '.$c->cedula.' '.$c->telefono)),
    ])->values();

    // @json() de Blade separa su contenido por comas de forma literal, así que
    // los datos para el JS se arman como variables simples aquí (sin comas propias
    // en la llamada a @json) en vez de construir el array/map dentro de la directiva.
    $mapaProducto = fn ($p, $campoImagen) => [
        'id'     => $p->id,
        'nombre' => $p->nombre,
        'imagen' => $p->$campoImagen ? asset('storage/'.$p->$campoImagen) : null,
        'tallas' => $p->tallas->map(fn ($t) => ['id' => $t->id, 'talla' => $t->talla, 'precio' => $t->precio])->values(),
    ];
    $catalogoUniformes = $uniformes->map(fn ($p) => $mapaProducto($p, 'imagen'))->values();
    $catalogoChompas = $chompas->map(fn ($p) => $mapaProducto($p, 'imagen'))->values();
    $catalogoPlantillas = $plantillas->map(fn ($p) => $mapaProducto($p, 'imagen_preview'))->values();
@endphp

<div class="sec-header reveal">
    <div class="sec-title">Nuevo pedido de tienda</div>
    <a href="{{ route('admin.pedidos-tienda.index') }}" class="btn-secondary">← Volver</a>
</div>

<div class="card card-pad reveal" style="max-width:900px;">
    @if($errors->any())
        <div style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;padding:14px 16px;border-radius:10px;margin-bottom:20px;font-size:0.86rem;">
            <strong style="display:block;margin-bottom:6px;">Revisa lo siguiente antes de continuar:</strong>
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $mensaje)
                    <li>{{ $mensaje }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pedidos-tienda.store') }}" method="POST" id="form-pedido-admin" novalidate data-confirm="¿Crear este pedido con los datos ingresados?">
        @csrf

        {{-- ── CLIENTE ── --}}
        <div id="seccion-cliente">
        <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:14px;">Cliente</div>

        <div style="display:flex;gap:18px;margin-bottom:16px;">
            <label style="display:flex;align-items:center;gap:6px;font-size:0.88rem;color:var(--text-2);cursor:pointer;">
                <input type="radio" name="cliente_nuevo" value="0" checked onchange="toggleClienteNuevo()">
                Cliente existente
            </label>
            <label style="display:flex;align-items:center;gap:6px;font-size:0.88rem;color:var(--text-2);cursor:pointer;">
                <input type="radio" name="cliente_nuevo" value="1" onchange="toggleClienteNuevo()">
                Cliente nuevo (sin cuenta / no quiere usar la app)
            </label>
        </div>

        <div id="bloque-cliente-existente" style="margin-bottom:22px;">
            <div style="position:relative;">
                <input type="text" id="cliente-busqueda" autocomplete="off"
                    oninput="filtrarClientes()" onfocus="filtrarClientes()"
                    placeholder="Escribe nombre, cédula o teléfono para buscar..."
                    style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-family:var(--font-b);font-size:0.9rem;color:var(--text-1);background:var(--bg-2);outline:none;">
                <div id="dropdown-clientes" style="display:none;position:absolute;left:0;right:0;top:calc(100% + 4px);z-index:20;max-height:260px;overflow-y:auto;background:var(--bg-2);border:1.5px solid var(--border);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,0.12);"></div>
            </div>
            <input type="hidden" name="cliente_id" id="cliente_id">
            <div id="cliente-seleccionado-info" style="font-size:0.82rem;color:var(--text-3);margin-top:8px;"></div>
            <div id="error-cliente-existente" style="display:none;color:#EF4444;font-size:0.78rem;margin-top:6px;"></div>
        </div>

        <div id="bloque-cliente-nuevo" style="display:none;margin-bottom:22px;">
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.76rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:6px;">Cédula *</label>
                <div style="position:relative;">
                    <input type="text" name="cedula" id="campo-cedula" value="{{ old('cedula') }}" maxlength="10" inputmode="numeric" placeholder="1710034065" style="width:100%;padding:10px 40px 10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);outline:none;">
                    <div id="spinner-cedula-admin" style="display:none;position:absolute;right:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;border-radius:50%;border:2px solid var(--border-2);border-top-color:var(--blue);animation:girar-admin 0.7s linear infinite;"></div>
                </div>
                <div class="campo-error" data-error-de="campo-cedula" style="display:none;color:#EF4444;font-size:0.76rem;margin-top:5px;"></div>
                <div id="hint-cedula-admin" style="display:none;font-size:0.76rem;color:var(--text-3);margin-top:5px;"></div>
                @error('cedula')<div style="color:#EF4444;font-size:0.76rem;margin-top:5px;">{{ $message }}</div>@enderror
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-size:0.76rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:6px;">Nombre *</label>
                    <input type="text" name="nombre" id="campo-nombre" value="{{ old('nombre') }}" style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);outline:none;">
                    <div class="campo-error" data-error-de="campo-nombre" style="display:none;color:#EF4444;font-size:0.76rem;margin-top:5px;"></div>
                    @error('nombre')<div style="color:#EF4444;font-size:0.76rem;margin-top:5px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block;font-size:0.76rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:6px;">Apellido *</label>
                    <input type="text" name="apellido" id="campo-apellido" value="{{ old('apellido') }}" style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);outline:none;">
                    <div class="campo-error" data-error-de="campo-apellido" style="display:none;color:#EF4444;font-size:0.76rem;margin-top:5px;"></div>
                    @error('apellido')<div style="color:#EF4444;font-size:0.76rem;margin-top:5px;">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.76rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:6px;">Correo *</label>
                <input type="email" name="email" id="campo-email" value="{{ old('email') }}" style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);outline:none;">
                <div class="campo-error" data-error-de="campo-email" style="display:none;color:#EF4444;font-size:0.76rem;margin-top:5px;"></div>
                @error('email')<div style="color:#EF4444;font-size:0.76rem;margin-top:5px;">{{ $message }}</div>@enderror
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div>
                    <label style="display:block;font-size:0.76rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:6px;">Teléfono *</label>
                    <input type="text" name="telefono" id="campo-telefono" value="{{ old('telefono') }}" maxlength="10" inputmode="numeric" placeholder="0991234567" style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);outline:none;">
                    <div class="campo-error" data-error-de="campo-telefono" style="display:none;color:#EF4444;font-size:0.76rem;margin-top:5px;"></div>
                    @error('telefono')<div style="color:#EF4444;font-size:0.76rem;margin-top:5px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block;font-size:0.76rem;font-weight:600;color:var(--text-2);text-transform:uppercase;margin-bottom:6px;">Ciudad (opcional)</label>
                    @php $ciudadActualAdmin = old('ciudad'); $cantonesCotopaxiAdmin = ['Latacunga','La Maná','Pujilí','Salcedo','Saquisilí','Sigchos','Pangua']; @endphp
                    <select id="campo-ciudad-select" style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);outline:none;"
                        onchange="document.getElementById('campo-ciudad-otra').style.display = this.value === 'otra' ? 'block' : 'none'; document.getElementById('campo-ciudad').value = this.value === 'otra' ? document.getElementById('campo-ciudad-otra').value : this.value;">
                        <option value="">Selecciona la ciudad</option>
                        @foreach($cantonesCotopaxiAdmin as $canton)
                            <option value="{{ $canton }}" {{ $ciudadActualAdmin === $canton ? 'selected' : '' }}>{{ $canton }}</option>
                        @endforeach
                        <option value="otra" {{ ($ciudadActualAdmin && !in_array($ciudadActualAdmin, $cantonesCotopaxiAdmin)) ? 'selected' : '' }}>Otra ciudad</option>
                    </select>
                    <input type="text" id="campo-ciudad-otra" placeholder="Escribe la ciudad"
                        value="{{ ($ciudadActualAdmin && !in_array($ciudadActualAdmin, $cantonesCotopaxiAdmin)) ? $ciudadActualAdmin : '' }}"
                        oninput="document.getElementById('campo-ciudad').value = this.value;"
                        style="margin-top:8px;width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);outline:none;{{ ($ciudadActualAdmin && !in_array($ciudadActualAdmin, $cantonesCotopaxiAdmin)) ? 'display:block;' : 'display:none;' }}">
                    <input type="hidden" name="ciudad" id="campo-ciudad" value="{{ $ciudadActualAdmin }}">
                </div>
            </div>
        </div>
        </div>

        <style>
            @keyframes girar-admin { to { transform: translateY(-50%) rotate(360deg); } }
        </style>

        <hr style="border:none;border-top:1px solid var(--border);margin:22px 0;">

        {{-- ── PRODUCTOS ── --}}
        <div id="seccion-productos">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <div style="font-size:1rem;font-weight:700;color:var(--text-1);">Productos</div>
            <button type="button" onclick="agregarFilaItem()" style="background:var(--blue-soft);border:1px solid var(--blue-border);color:var(--blue);padding:8px 16px;border-radius:8px;font-size:0.82rem;font-weight:600;cursor:pointer;">
                + Agregar producto
            </button>
        </div>

        <div style="display:flex;gap:16px;margin-bottom:6px;">
            <div style="width:200px;flex-shrink:0;"></div>
            <div style="flex:1;display:grid;grid-template-columns:1fr 2fr 1.3fr 0.8fr 1fr 40px;gap:10px;font-size:0.72rem;font-weight:600;color:var(--text-3);text-transform:uppercase;">
                <div>Tipo</div><div>Producto</div><div>Talla</div><div>Cant.</div><div style="text-align:right;">Subtotal</div><div></div>
            </div>
        </div>

        <div id="contenedor-items"></div>
        <div id="error-productos" style="display:none;color:#EF4444;font-size:0.78rem;margin-top:8px;"></div>

        <div style="display:flex;justify-content:flex-end;gap:32px;margin-top:16px;padding-top:14px;border-top:1px solid var(--border);">
            <div style="font-size:0.85rem;color:var(--text-2);">Total: <strong id="total-pedido" style="color:var(--text-1);">$0.00</strong></div>
            <div style="font-size:0.85rem;color:var(--text-2);">Adelanto (50%): <strong id="adelanto-pedido" style="color:var(--text-1);">$0.00</strong></div>
        </div>
        </div>

        <hr style="border:none;border-top:1px solid var(--border);margin:22px 0;">

        <div id="seccion-pago" style="margin-bottom:24px;">
            <div style="font-size:1rem;font-weight:700;color:var(--text-1);margin-bottom:10px;">¿Cómo se registra el pago de este pedido?</div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <label style="display:flex;align-items:center;gap:8px;font-size:0.88rem;color:var(--text-2);cursor:pointer;">
                    <input type="radio" name="estado_pago_registro" value="adelanto" onchange="limpiarError('error-pago')">
                    Pagó el adelanto (50%) en el momento
                </label>
                <label style="display:flex;align-items:center;gap:8px;font-size:0.88rem;color:var(--text-2);cursor:pointer;">
                    <input type="radio" name="estado_pago_registro" value="completo" onchange="limpiarError('error-pago')">
                    Pagó el pedido completo (100%) en el momento
                </label>
            </div>
            <div id="error-pago" style="display:none;color:#EF4444;font-size:0.78rem;margin-top:8px;"></div>
        </div>

        <button type="submit" class="btn-primary" style="width:100%;padding:13px;font-size:0.95rem;">
            Crear pedido
        </button>
    </form>
</div>

<script>
const catalogo = {
    uniforme: @json($catalogoUniformes),
    chompa: @json($catalogoChompas),
    plantilla: @json($catalogoPlantillas),
};
const clientesData = @json($clientesData);
const estiloInput = 'width:100%;padding:9px 10px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.86rem;background:var(--bg-2);color:var(--text-1);outline:none;';

function toggleClienteNuevo() {
    const esNuevo = document.querySelector('input[name="cliente_nuevo"]:checked').value === '1';
    document.getElementById('bloque-cliente-existente').style.display = esNuevo ? 'none' : 'block';
    document.getElementById('bloque-cliente-nuevo').style.display = esNuevo ? 'block' : 'none';
    limpiarError('error-cliente-existente');
    document.querySelectorAll('#bloque-cliente-nuevo .campo-error').forEach(el => { el.style.display = 'none'; });
}

// ── CÉDULA (cliente nuevo): solo dígitos, y al salir del campo se consulta
// el nombre real (mismo servicio que usa el registro de clientes) para
// autocompletar nombre/apellido — igual que en el formulario de registro.
const campoCedulaAdmin = document.getElementById('campo-cedula');
const campoNombreAdmin = document.getElementById('campo-nombre');
const campoApellidoAdmin = document.getElementById('campo-apellido');
let autocompletadoAdmin = { nombre: false, apellido: false };
let ultimaCedulaConsultadaAdmin = null;

campoCedulaAdmin.addEventListener('input', () => {
    campoCedulaAdmin.value = campoCedulaAdmin.value.replace(/\D/g, '').slice(0, 10);
});
campoNombreAdmin.addEventListener('input', () => { autocompletadoAdmin.nombre = false; });
campoApellidoAdmin.addEventListener('input', () => { autocompletadoAdmin.apellido = false; });

function liberarNombreApellidoAdmin() {
    campoNombreAdmin.removeAttribute('readonly');
    campoApellidoAdmin.removeAttribute('readonly');
    autocompletadoAdmin.nombre = false;
    autocompletadoAdmin.apellido = false;
}

campoCedulaAdmin.addEventListener('blur', function () {
    const hint = document.getElementById('hint-cedula-admin');
    const errorCedula = document.querySelector('[data-error-de="campo-cedula"]');
    hint.style.display = 'none';

    const valor = campoCedulaAdmin.value.trim();
    if (valor === '') { limpiarError('error-cliente-existente'); return; }

    if (!/^\d{10}$/.test(valor)) {
        errorCedula.textContent = 'La cédula debe tener 10 dígitos.';
        errorCedula.style.display = 'block';
        liberarNombreApellidoAdmin();
        return;
    }
    errorCedula.style.display = 'none';

    if (valor === ultimaCedulaConsultadaAdmin) return;

    const spinner = document.getElementById('spinner-cedula-admin');
    spinner.style.display = 'block';

    fetch(@json(route('registro.consultar-cedula')), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': @json(csrf_token()),
            'Accept': 'application/json',
        },
        body: JSON.stringify({ cedula: valor }),
    })
        .then(res => res.ok ? res.json() : Promise.reject())
        .then(data => {
            ultimaCedulaConsultadaAdmin = valor;

            if (data.duplicado) {
                errorCedula.textContent = data.mensaje || 'Ya existe un cliente registrado con esta cédula.';
                errorCedula.style.display = 'block';
                liberarNombreApellidoAdmin();
                return;
            }

            if (data.encontrado) {
                // A diferencia del registro público, aquí SIEMPRE se sobreescribe con el
                // nombre oficial de la cédula: es el admin llenando el dato de otra
                // persona, así que el nombre del Registro Civil manda sobre cualquier
                // texto que se haya escrito antes (borrador, prueba, etc.).
                campoNombreAdmin.value = data.nombres;
                campoNombreAdmin.setAttribute('readonly', true);
                autocompletadoAdmin.nombre = true;

                campoApellidoAdmin.value = data.apellidos;
                campoApellidoAdmin.setAttribute('readonly', true);
                autocompletadoAdmin.apellido = true;
                hint.textContent = 'Nombre autocompletado desde la cédula.';
                hint.style.display = 'block';
            } else {
                liberarNombreApellidoAdmin();
            }
        })
        .catch(() => { /* silencioso: el admin completa el nombre a mano */ })
        .finally(() => { spinner.style.display = 'none'; });
});

document.getElementById('campo-telefono').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 10);
});

// ── COMBOBOX DE CLIENTE (buscador con lista desplegable) ──
function filtrarClientes() {
    const texto = document.getElementById('cliente-busqueda').value.trim().toLowerCase();
    const dropdown = document.getElementById('dropdown-clientes');

    // Si el texto ya no coincide con el cliente seleccionado, se pierde la selección.
    const idActual = document.getElementById('cliente_id').value;
    if (idActual) {
        const seleccionado = clientesData.find(c => String(c.id) === String(idActual));
        if (!seleccionado || seleccionado.label.toLowerCase() !== texto) {
            document.getElementById('cliente_id').value = '';
            document.getElementById('cliente-seleccionado-info').textContent = '';
        }
    }

    const coincidencias = texto
        ? clientesData.filter(c => c.buscable.includes(texto)).slice(0, 30)
        : clientesData.slice(0, 30);

    if (!coincidencias.length) {
        dropdown.innerHTML = '<div style="padding:12px 14px;font-size:0.85rem;color:var(--text-3);">Ningún cliente coincide con la búsqueda.</div>';
    } else {
        dropdown.innerHTML = coincidencias.map(c => `
            <div class="opcion-cliente" data-id="${c.id}" style="padding:10px 14px;font-size:0.87rem;color:var(--text-1);cursor:pointer;border-bottom:1px solid var(--border);">
                ${c.label}
            </div>
        `).join('');
        dropdown.querySelectorAll('.opcion-cliente').forEach(el => {
            el.addEventListener('mousedown', (e) => {
                e.preventDefault();
                seleccionarCliente(el.dataset.id);
            });
            el.addEventListener('mouseenter', () => { el.style.background = 'var(--bg-3)'; });
            el.addEventListener('mouseleave', () => { el.style.background = 'transparent'; });
        });
    }
    dropdown.style.display = 'block';
}

function seleccionarCliente(id) {
    const cliente = clientesData.find(c => String(c.id) === String(id));
    if (!cliente) return;
    document.getElementById('cliente_id').value = cliente.id;
    document.getElementById('cliente-busqueda').value = cliente.label;
    document.getElementById('cliente-seleccionado-info').textContent = 'Cliente seleccionado ✓';
    document.getElementById('dropdown-clientes').style.display = 'none';
    limpiarError('error-cliente-existente');
}

document.addEventListener('click', (e) => {
    const dentro = e.target.closest('#bloque-cliente-existente');
    if (!dentro) {
        document.getElementById('dropdown-clientes').style.display = 'none';
    }
});

function limpiarError(id) {
    const el = document.getElementById(id);
    if (el) { el.style.display = 'none'; el.textContent = ''; }
}
function mostrarError(id, mensaje) {
    const el = document.getElementById(id);
    if (el) { el.style.display = 'block'; el.textContent = mensaje; }
}

let contadorItems = 0;

function agregarFilaItem() {
    const idx = contadorItems++;
    const fila = document.createElement('div');
    fila.className = 'fila-item';
    fila.dataset.idx = idx;
    fila.style.cssText = 'display:flex;gap:16px;align-items:flex-start;margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--border);';
    fila.innerHTML = `
        <div style="width:200px;height:200px;flex-shrink:0;border-radius:10px;border:1px solid var(--border);background:var(--bg-3);display:flex;align-items:center;justify-content:center;overflow:hidden;cursor:zoom-in;" onclick="const s=document.getElementById('miniatura-${idx}').src; if(s) window.open(s, '_blank')">
            <img id="miniatura-${idx}" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;">
        </div>
        <div style="flex:1;min-width:0;display:grid;grid-template-columns:1fr 2fr 1.3fr 0.8fr 1fr 40px;gap:10px;align-items:center;">
            <select name="items[${idx}][tipo]" onchange="cargarProductos(${idx})" style="${estiloInput}">
                <option value="">Tipo...</option>
                <option value="uniforme">Uniforme</option>
                <option value="chompa">Chompa</option>
                <option value="plantilla">Ropa</option>
            </select>
            <select name="items[${idx}][producto_id]" id="producto-${idx}" onchange="cargarTallas(${idx})" style="${estiloInput}" disabled>
                <option value="">Selecciona el tipo primero</option>
            </select>
            <select name="items[${idx}][talla_id]" id="talla-${idx}" onchange="actualizarSubtotal(${idx})" style="${estiloInput}" disabled>
                <option value="">Talla...</option>
            </select>
            <input type="number" name="items[${idx}][cantidad]" id="cantidad-${idx}" value="1" min="1" max="100" oninput="limitarCantidad(this)" onchange="actualizarSubtotal(${idx})" style="${estiloInput}">
            <div id="subtotal-${idx}" style="font-weight:700;color:var(--text-1);text-align:right;font-size:0.88rem;">$0.00</div>
            <button type="button" onclick="quitarFila(${idx})" style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;width:36px;height:36px;border-radius:8px;font-weight:700;cursor:pointer;">✕</button>
        </div>
    `;
    document.getElementById('contenedor-items').appendChild(fila);
}

function actualizarMiniatura(idx, urlImagen) {
    const img = document.getElementById(`miniatura-${idx}`);
    if (urlImagen) {
        img.src = urlImagen;
        img.style.display = 'block';
    } else {
        img.src = '';
        img.style.display = 'none';
    }
}

function cargarProductos(idx) {
    const fila = document.querySelector(`.fila-item[data-idx="${idx}"]`);
    const tipo = fila.querySelector(`[name="items[${idx}][tipo]"]`).value;
    const selectProducto = document.getElementById(`producto-${idx}`);
    const selectTalla = document.getElementById(`talla-${idx}`);

    selectTalla.innerHTML = '<option value="">Talla...</option>';
    selectTalla.disabled = true;
    actualizarMiniatura(idx, null);

    const productos = catalogo[tipo] || [];
    if (!productos.length) {
        selectProducto.innerHTML = '<option value="">Sin productos disponibles</option>';
        selectProducto.disabled = true;
        actualizarSubtotal(idx);
        return;
    }

    selectProducto.innerHTML = '<option value="">Selecciona un producto</option>' +
        productos.map(p => `<option value="${p.id}">${p.nombre}</option>`).join('');
    selectProducto.disabled = false;
    actualizarSubtotal(idx);
}

function cargarTallas(idx) {
    const fila = document.querySelector(`.fila-item[data-idx="${idx}"]`);
    const tipo = fila.querySelector(`[name="items[${idx}][tipo]"]`).value;
    const productoId = document.getElementById(`producto-${idx}`).value;
    const selectTalla = document.getElementById(`talla-${idx}`);

    const producto = (catalogo[tipo] || []).find(p => String(p.id) === String(productoId));
    const tallas = producto ? producto.tallas : [];
    actualizarMiniatura(idx, producto ? producto.imagen : null);

    if (!tallas.length) {
        selectTalla.innerHTML = '<option value="">Sin tallas disponibles</option>';
        selectTalla.disabled = true;
        actualizarSubtotal(idx);
        return;
    }

    selectTalla.innerHTML = '<option value="">Selecciona talla</option>' +
        tallas.map(t => `<option value="${t.id}" data-precio="${t.precio}">${t.talla} — $${Number(t.precio).toFixed(2)}</option>`).join('');
    selectTalla.disabled = false;
    actualizarSubtotal(idx);
}

// El formulario usa novalidate (para no chocar con la validación propia),
// así que min/max del <input type="number"> no se aplican solos — hay que
// forzar el límite a mano mientras se escribe.
function limitarCantidad(input) {
    if (input.value.length > 3) input.value = input.value.slice(0, 3);
    if (parseInt(input.value) > 100) input.value = 100;
}

function actualizarSubtotal(idx) {
    const selectTalla = document.getElementById(`talla-${idx}`);
    const cantidad = parseInt(document.getElementById(`cantidad-${idx}`).value) || 0;
    const opcion = selectTalla.options[selectTalla.selectedIndex];
    const precio = opcion ? parseFloat(opcion.dataset.precio || 0) : 0;
    document.getElementById(`subtotal-${idx}`).textContent = '$' + (precio * cantidad).toFixed(2);
    actualizarTotal();
}

function actualizarTotal() {
    let total = 0;
    document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
        total += parseFloat(el.textContent.replace('$', '')) || 0;
    });
    document.getElementById('total-pedido').textContent = '$' + total.toFixed(2);
    document.getElementById('adelanto-pedido').textContent = '$' + (total / 2).toFixed(2);
}

function quitarFila(idx) {
    document.querySelector(`.fila-item[data-idx="${idx}"]`).remove();
    actualizarTotal();
}

// Detecta números "de relleno" que sí cumplen el formato 09XXXXXXXX pero
// evidentemente no son un teléfono real (todos los dígitos iguales, o una
// secuencia consecutiva ascendente/descendente como 0987654321).
function esTelefonoFalso(valor) {
    const resto = valor.slice(2);
    if (/^(\d)\1+$/.test(resto)) return true;
    let ascendente = true, descendente = true;
    for (let i = 1; i < resto.length; i++) {
        const prev = parseInt(resto[i - 1], 10);
        const curr = parseInt(resto[i], 10);
        if (curr !== prev + 1) ascendente = false;
        if (curr !== prev - 1) descendente = false;
    }
    return ascendente || descendente;
}

function marcarFilaInvalida(fila, invalida) {
    fila.style.outline = invalida ? '1.5px solid #EF4444' : 'none';
    fila.style.outlineOffset = invalida ? '2px' : '0';
}

// ── VALIDACIÓN POR SECCIÓN ANTES DE ENVIAR ──
function validarFormulario(e) {
    let valido = true;
    let primerError = null;

    limpiarError('error-cliente-existente');
    limpiarError('error-productos');
    document.querySelectorAll('.campo-error').forEach(el => { el.style.display = 'none'; });

    // — Sección cliente —
    const esNuevo = document.querySelector('input[name="cliente_nuevo"]:checked').value === '1';
    if (esNuevo) {
        const nombre = document.getElementById('campo-nombre');
        const apellido = document.getElementById('campo-apellido');
        const email = document.getElementById('campo-email');
        const cedula = document.getElementById('campo-cedula');
        const telefono = document.getElementById('campo-telefono');

        if (!nombre.value.trim()) {
            document.querySelector('[data-error-de="campo-nombre"]').textContent = 'El nombre es obligatorio.';
            document.querySelector('[data-error-de="campo-nombre"]').style.display = 'block';
            valido = false; primerError = primerError || nombre;
        }
        if (!apellido.value.trim()) {
            document.querySelector('[data-error-de="campo-apellido"]').textContent = 'El apellido es obligatorio.';
            document.querySelector('[data-error-de="campo-apellido"]').style.display = 'block';
            valido = false; primerError = primerError || apellido;
        }
        if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
            document.querySelector('[data-error-de="campo-email"]').textContent = 'Ingresa un correo válido.';
            document.querySelector('[data-error-de="campo-email"]').style.display = 'block';
            valido = false; primerError = primerError || email;
        }
        if (!/^\d{10}$/.test(cedula.value.trim())) {
            document.querySelector('[data-error-de="campo-cedula"]').textContent = cedula.value.trim() ? 'La cédula debe tener 10 dígitos.' : 'La cédula es obligatoria.';
            document.querySelector('[data-error-de="campo-cedula"]').style.display = 'block';
            valido = false; primerError = primerError || cedula;
        }
        const telefonoValor = telefono.value.trim();
        if (!/^09\d{8}$/.test(telefonoValor)) {
            document.querySelector('[data-error-de="campo-telefono"]').textContent = telefonoValor ? 'El teléfono debe empezar con 09 y tener 10 dígitos.' : 'El teléfono es obligatorio.';
            document.querySelector('[data-error-de="campo-telefono"]').style.display = 'block';
            valido = false; primerError = primerError || telefono;
        } else if (esTelefonoFalso(telefonoValor)) {
            document.querySelector('[data-error-de="campo-telefono"]').textContent = 'Ese número parece inventado (dígitos repetidos o en secuencia). Ingresa el teléfono real del cliente.';
            document.querySelector('[data-error-de="campo-telefono"]').style.display = 'block';
            valido = false; primerError = primerError || telefono;
        }
    } else {
        if (!document.getElementById('cliente_id').value) {
            mostrarError('error-cliente-existente', 'Selecciona un cliente de la lista (haz clic en una de las opciones que aparecen al escribir).');
            valido = false; primerError = primerError || document.getElementById('cliente-busqueda');
        }
    }

    // — Sección productos —
    const filas = document.querySelectorAll('#contenedor-items .fila-item');
    if (!filas.length) {
        mostrarError('error-productos', 'Agrega al menos un producto al pedido.');
        valido = false; primerError = primerError || document.getElementById('contenedor-items');
    } else {
        let filaInvalida = false;
        filas.forEach(fila => {
            const idx = fila.dataset.idx;
            const tipo = fila.querySelector(`[name="items[${idx}][tipo]"]`).value;
            const producto = document.getElementById(`producto-${idx}`).value;
            const talla = document.getElementById(`talla-${idx}`).value;
            const cantidad = parseInt(document.getElementById(`cantidad-${idx}`).value) || 0;
            const incompleta = !tipo || !producto || !talla || cantidad < 1 || cantidad > 100;
            marcarFilaInvalida(fila, incompleta);
            if (incompleta) {
                filaInvalida = true;
                valido = false;
                primerError = primerError || fila;
            }
        });
        if (filaInvalida) {
            mostrarError('error-productos', 'Completa el tipo, producto y talla de cada producto, con una cantidad entre 1 y 100.');
        }
    }

    // — Sección pago —
    if (!document.querySelector('input[name="estado_pago_registro"]:checked')) {
        mostrarError('error-pago', 'Selecciona cómo se registra el pago de este pedido.');
        valido = false; primerError = primerError || document.getElementById('seccion-pago');
    }

    if (!valido) {
        e.preventDefault();
        if (primerError) primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    return valido;
}

window.addEventListener('DOMContentLoaded', () => {
    toggleClienteNuevo();
    agregarFilaItem();
    const formPedido = document.getElementById('form-pedido-admin');
    formPedido.addEventListener('submit', validarFormulario);

    // Enter dentro de un campo de texto/select no debe enviar el formulario
    // (p. ej. presionarlo mientras la búsqueda de cédula sigue en curso dejaba
    // la pantalla en un estado a medio validar). Solo el botón "Crear pedido" envía.
    formPedido.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && e.target.type !== 'submit') {
            e.preventDefault();
        }
    });
});
</script>

@endsection
