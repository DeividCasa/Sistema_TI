<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EstadoPedidoMail;
use App\Models\Chompa;
use App\Models\ChompaTalla;
use App\Models\Cliente;
use App\Models\ComprobanteMaestro;
use App\Models\Pedido;
use App\Models\PedidoChompa;
use App\Models\PedidoMaestro;
use App\Models\PedidoPlantilla;
use App\Models\PedidoUniforme;
use App\Models\Plantilla;
use App\Models\PlantillaTalla;
use App\Models\Uniforme;
use App\Models\UniformeTalla;
use App\Services\CheckoutService;
use App\Support\PedidoEstados;
use App\Support\WhatsappHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PedidoTiendaController extends Controller
{
    // ── LISTA UNIFICADA: maestros + pedidos sueltos de ropa/uniforme/chompa/camiseta personalizada (legado)
    public function index()
    {
        $pedidos = self::pedidosUnificados();

        return view('Admin.pedidos_tienda.index', compact('pedidos'));
    }

    // ── Colección unificada de TODOS los tipos de pedido (usada también por el dashboard
    //    y por la ficha del cliente en admin, pasando $clienteId para filtrar solo los suyos)
    public static function pedidosUnificados(?int $clienteId = null)
    {
        $maestros = PedidoMaestro::with([
                'cliente',
                'pedidoUniforme.items.uniforme',
                'pedidoChompa.items.chompa',
                'pedidoPlantilla.items.plantilla',
                'comprobantes',
            ])
            ->when($clienteId, fn ($q) => $q->where('cliente_id', $clienteId))
            ->get()
            ->map(function ($p) {
                $nuevo = collect([$p->pedidoUniforme, $p->pedidoChompa, $p->pedidoPlantilla])
                    ->filter()
                    ->contains(fn ($hijo) => is_null($hijo->visto_admin_at));
                return ['tipo' => 'Combinado', 'pedido' => $p, 'fecha' => $p->created_at, 'nuevo' => $nuevo];
            });

        $soloUniformes = PedidoUniforme::with(['cliente', 'items.uniforme', 'comprobantes'])
            ->whereNull('pedido_maestro_id')
            ->when($clienteId, fn ($q) => $q->where('cliente_id', $clienteId))
            ->get()
            ->map(fn ($p) => ['tipo' => 'Uniforme', 'pedido' => $p, 'fecha' => $p->created_at, 'nuevo' => is_null($p->visto_admin_at)]);

        $soloChompas = PedidoChompa::with(['cliente', 'items.chompa', 'comprobantes'])
            ->whereNull('pedido_maestro_id')
            ->when($clienteId, fn ($q) => $q->where('cliente_id', $clienteId))
            ->get()
            ->map(fn ($p) => ['tipo' => 'Chompa', 'pedido' => $p, 'fecha' => $p->created_at, 'nuevo' => is_null($p->visto_admin_at)]);

        $soloPlantillas = PedidoPlantilla::with(['cliente', 'items.plantilla', 'comprobantes'])
            ->whereNull('pedido_maestro_id')
            ->when($clienteId, fn ($q) => $q->where('cliente_id', $clienteId))
            ->get()
            ->map(fn ($p) => ['tipo' => 'Ropa', 'pedido' => $p, 'fecha' => $p->created_at, 'nuevo' => is_null($p->visto_admin_at)]);

        $camisetas = Pedido::with(['cliente', 'disenio'])
            ->when($clienteId, fn ($q) => $q->where('cliente_id', $clienteId))
            ->get()
            ->map(fn ($p) => ['tipo' => 'Camiseta', 'pedido' => $p, 'fecha' => $p->created_at, 'nuevo' => is_null($p->visto_admin_at)]);

        return $maestros->concat($soloUniformes)->concat($soloChompas)->concat($soloPlantillas)->concat($camisetas)
            ->sortByDesc('fecha')
            ->values();
    }

    // ── DETALLE DE UN PEDIDO COMBINADO
    public function show($id)
    {
        $pedido = PedidoMaestro::with([
                'cliente',
                'pedidoUniforme.items.uniforme',
                'pedidoChompa.items.chompa',
                'pedidoPlantilla.items.plantilla',
                'comprobantes',
            ])
            ->findOrFail($id);

        foreach ([$pedido->pedidoUniforme, $pedido->pedidoChompa, $pedido->pedidoPlantilla] as $hijo) {
            if ($hijo && is_null($hijo->visto_admin_at)) {
                $hijo->visto_admin_at = now();
                $hijo->save();
            }
        }

        return view('Admin.pedidos_tienda.show', compact('pedido'));
    }

    // ── CAMBIAR ESTADO DE PRODUCCIÓN GENERAL (un solo control para todo el
    //    pedido combinado: aplica el mismo estado a ropa/uniformes/chompas a
    //    la vez y envía UNA sola notificación, en vez de repetir el cambio
    //    y el aviso por cada sub-pedido por separado).
    public function actualizarEstado(Request $request, $id)
    {
        $pedido = PedidoMaestro::with([
                'cliente',
                'pedidoUniforme.items.uniforme',
                'pedidoChompa.items.chompa',
                'pedidoPlantilla.items.plantilla',
            ])
            ->findOrFail($id);

        $hijos = collect([$pedido->pedidoPlantilla, $pedido->pedidoUniforme, $pedido->pedidoChompa])->filter();

        foreach ($hijos as $hijo) {
            if (!PedidoEstados::pagoVerificado($hijo->estado_pago)) {
                return back()->withErrors(['estado' => 'Debes verificar todos los comprobantes de pago antes de cambiar el estado del pedido.']);
            }
        }

        $request->validate([
            'estado'          => 'required|in:' . implode(',', PedidoEstados::estadosDisponibles($pedido->tipo_entrega)),
            'tiempo_estimado' => 'nullable|string|max:255',
        ]);

        foreach ($hijos as $hijo) {
            $hijo->estado = $request->estado;
            if ($request->filled('tiempo_estimado')) {
                $hijo->tiempo_estimado = $request->tiempo_estimado;
            }
            $hijo->save();
        }

        if ($request->filled('tiempo_estimado')) {
            $pedido->tiempo_estimado = $request->tiempo_estimado;
            $pedido->save();
        }

        $estadoLabel = PedidoEstados::label($request->estado);

        if ($request->boolean('notificar_email') && $pedido->cliente?->email) {
            $tipos = $hijos->map(fn ($hijo) => match (true) {
                $hijo instanceof PedidoPlantilla => 'Ropa',
                $hijo instanceof PedidoUniforme  => 'Uniforme',
                $hijo instanceof PedidoChompa    => 'Chompa',
                default => null,
            })->filter()->implode(' + ');

            $lineas = [];
            if ($pedido->pedidoPlantilla) {
                foreach ($pedido->pedidoPlantilla->items as $item) {
                    $lineas[] = [
                        'nombre' => $item->plantilla->nombre ?? 'Producto',
                        'detalle' => ($item->talla ? 'Talla '.$item->talla.' × ' : '').$item->cantidad,
                        'imagenPath' => $item->plantilla?->imagen_preview ? Storage::disk('public')->path($item->plantilla->imagen_preview) : null,
                    ];
                }
            }
            if ($pedido->pedidoUniforme) {
                foreach ($pedido->pedidoUniforme->items as $item) {
                    $lineas[] = [
                        'nombre' => $item->uniforme->nombre,
                        'detalle' => 'Talla '.$item->talla.' × '.$item->cantidad,
                        'imagenPath' => $item->uniforme?->imagen ? Storage::disk('public')->path($item->uniforme->imagen) : null,
                    ];
                }
            }
            if ($pedido->pedidoChompa) {
                foreach ($pedido->pedidoChompa->items as $item) {
                    $lineas[] = [
                        'nombre' => $item->chompa->nombre,
                        'detalle' => 'Talla '.$item->talla.' × '.$item->cantidad,
                        'imagenPath' => $item->chompa?->imagen ? Storage::disk('public')->path($item->chompa->imagen) : null,
                    ];
                }
            }

            Mail::to($pedido->cliente->email)->send(new EstadoPedidoMail(
                $pedido->cliente->nombre,
                $pedido->codigo,
                $tipos ?: 'Pedido',
                $estadoLabel,
                $pedido->tiempo_estimado,
                $lineas,
                PedidoEstados::mensajeParaCliente($request->estado, $pedido->tipo_entrega),
            ));
        }

        $whatsappUrl = null;
        if ($request->boolean('notificar_whatsapp')) {
            $mensaje = "Hola {$pedido->cliente->nombre}, tu pedido {$pedido->codigo} ahora está: "
                . PedidoEstados::mensajeParaCliente($request->estado, $pedido->tipo_entrega, $pedido->tiempo_estimado);
            $whatsappUrl = WhatsappHelper::link($pedido->cliente?->telefono, $mensaje);
        }

        return back()->with([
            'success' => 'Estado del pedido actualizado.',
            'whatsapp_url' => $whatsappUrl,
        ]);
    }

    // ── MARCAR PAGO COMO COMPLETADO (override manual del admin), cascada a hijos
    public function marcarPagoCompleto($id)
    {
        $pedido = PedidoMaestro::findOrFail($id);
        $pedido->estado_pago = 'pagado_completo';
        $pedido->save();

        $pedido->pedidoUniforme?->update(['estado_pago' => 'pagado_completo']);
        $pedido->pedidoChompa?->update(['estado_pago' => 'pagado_completo']);
        $pedido->pedidoPlantilla?->update(['estado_pago' => 'pagado_completo']);

        return back()->with('success', 'Pago marcado como completado.');
    }

    // ── VERIFICAR COMPROBANTE COMBINADO
    public function verificarComprobante($id)
    {
        $comprobante = ComprobanteMaestro::with('pedido')->findOrFail($id);
        $comprobante->estado = 'verificado';
        $comprobante->save();

        $pedido = $comprobante->pedido;

        $nuevoEstadoPago = $comprobante->tipo === 'adelanto' ? 'adelanto_verificado' : 'pagado_completo';
        $pedido->estado_pago = $nuevoEstadoPago;
        $pedido->save();

        foreach ([$pedido->pedidoUniforme, $pedido->pedidoChompa, $pedido->pedidoPlantilla] as $hijo) {
            if (!$hijo) {
                continue;
            }
            $hijo->estado_pago = $nuevoEstadoPago;
            if ($hijo->estado === 'recibido') {
                $hijo->estado = 'en_produccion';
            }
            $hijo->save();
        }

        return back()->with('success', 'Comprobante verificado correctamente.');
    }

    // ── RECHAZAR COMPROBANTE COMBINADO
    public function rechazarComprobante(Request $request, $id)
    {
        $request->validate(['nota_admin' => 'nullable|string|max:1000']);

        $comprobante = ComprobanteMaestro::with('pedido')->findOrFail($id);
        $comprobante->estado = 'rechazado';
        $comprobante->nota_admin = $request->nota_admin ?? 'Comprobante no válido.';
        $comprobante->save();

        $pedido = $comprobante->pedido;
        $nuevoEstadoPago = $comprobante->tipo === 'saldo_final' ? 'adelanto_verificado' : 'pendiente';
        $pedido->estado_pago = $nuevoEstadoPago;
        $pedido->save();

        foreach ([$pedido->pedidoUniforme, $pedido->pedidoChompa, $pedido->pedidoPlantilla] as $hijo) {
            if (!$hijo) {
                continue;
            }
            $hijo->estado_pago = $nuevoEstadoPago;
            $hijo->save();
        }

        return back()->with('success', 'Comprobante rechazado.');
    }

    // ── FORMULARIO: pedido de tienda tomado por el admin (cliente presencial
    //    que no quiere usar el aplicativo). Cliente existente o walk-in.
    public function create()
    {
        $clientes = Cliente::select('id', 'nombre', 'apellido', 'cedula', 'telefono')
            ->orderBy('nombre')
            ->get();

        $uniformes = Uniforme::with(['tallas' => fn ($q) => $q->where('disponible', 1)])
            ->where('activo', 1)->orderBy('nombre')->get();
        $chompas = Chompa::with(['tallas' => fn ($q) => $q->where('disponible', 1)])
            ->where('activo', 1)->orderBy('nombre')->get();
        $plantillas = Plantilla::with(['tallas' => fn ($q) => $q->where('disponible', 1)])
            ->where('activa', 1)->orderBy('nombre')->get();

        return view('Admin.pedidos_tienda.create', compact('clientes', 'uniformes', 'chompas', 'plantillas'));
    }

    // ── GUARDAR: crea (o reutiliza) el cliente y arma el pedido reusando
    //    CheckoutService, el mismo servicio que usa el checkout del cliente.
    public function store(Request $request, CheckoutService $checkoutService)
    {
        $esNuevo = $request->boolean('cliente_nuevo');

        $reglas = [
            'items'                  => 'required|array|min:1',
            'items.*.tipo'           => 'required|in:uniforme,chompa,plantilla',
            'items.*.producto_id'    => 'required|integer',
            'items.*.talla_id'       => 'required|integer',
            'items.*.cantidad'       => 'required|integer|min:1|max:100',
            'estado_pago_registro'   => 'required|in:adelanto,completo',
        ];

        if ($esNuevo) {
            $reglas += [
                'nombre'   => ['required', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
                'apellido' => ['required', 'string', 'max:100', 'regex:/^[\pL\s]+$/u'],
                'email'    => 'required|email:rfc,filter|max:150|unique:clientes,email',
                'cedula'   => 'required|digits:10|unique:clientes,cedula',
                'telefono' => ['required', 'regex:/^09\d{8}$/', $this->reglaTelefonoReal()],
                'ciudad'   => 'nullable|string|max:100',
            ];
        } else {
            $reglas['cliente_id'] = 'required|exists:clientes,id';
        }

        $validado = $request->validate($reglas, [
            'items.required'      => 'Agrega al menos un producto al pedido.',
            'items.*.talla_id.required' => 'Selecciona una talla para cada producto.',
            'estado_pago_registro.required' => 'Selecciona cómo se registra el pago de este pedido.',
            'nombre.regex'        => 'El nombre solo puede contener letras.',
            'apellido.regex'      => 'El apellido solo puede contener letras.',
            'email.required'      => 'El correo es obligatorio (la cuenta del cliente lo necesita).',
            'email.unique'        => 'Ya existe un cliente con ese correo.',
            'cedula.required'     => 'La cédula es obligatoria.',
            'cedula.digits'       => 'La cédula debe tener 10 dígitos.',
            'cedula.unique'       => 'Ya existe un cliente con esa cédula.',
            'telefono.required'   => 'El teléfono es obligatorio.',
            'telefono.regex'      => 'El teléfono debe empezar con 09 y tener 10 dígitos (ej: 0991234567).',
            'cliente_id.required' => 'Selecciona un cliente.',
        ]);

        $tablaTalla = [
            'uniforme'  => UniformeTalla::class,
            'chompa'    => ChompaTalla::class,
            'plantilla' => PlantillaTalla::class,
        ];
        $columnaProducto = ['uniforme' => 'uniforme_id', 'chompa' => 'chompa_id', 'plantilla' => 'plantilla_id'];
        $claveCarrito = ['uniforme' => 'uniformes', 'chompa' => 'chompas', 'plantilla' => 'plantillas'];

        $resultado = DB::transaction(function () use ($validado, $esNuevo, $checkoutService, $tablaTalla, $columnaProducto, $claveCarrito, $request) {
            if ($esNuevo) {
                $cliente = Cliente::create([
                    'nombre'   => $validado['nombre'],
                    'apellido' => $validado['apellido'],
                    'email'    => $validado['email'],
                    'cedula'   => $validado['cedula'] ?? null,
                    'telefono' => $validado['telefono'] ?? null,
                    'ciudad'   => $validado['ciudad'] ?? null,
                    'password' => Hash::make(Str::random(24)),
                    'activo'   => 1,
                ]);
                $clienteId = $cliente->id;
            } else {
                $clienteId = (int) $validado['cliente_id'];
            }

            $carritos = ['plantillas' => [], 'uniformes' => [], 'chompas' => []];

            foreach ($validado['items'] as $item) {
                $tipo = $item['tipo'];

                // El precio SIEMPRE se toma de la BD, nunca del formulario.
                $talla = $tablaTalla[$tipo]::where('id', $item['talla_id'])
                    ->where($columnaProducto[$tipo], $item['producto_id'])
                    ->where('disponible', 1)
                    ->firstOrFail();

                $bucket = $claveCarrito[$tipo];
                $clave = $item['producto_id'] . '-' . $talla->id;

                if (isset($carritos[$bucket][$clave])) {
                    $carritos[$bucket][$clave]['cantidad'] += (int) $item['cantidad'];
                } else {
                    $carritos[$bucket][$clave] = [
                        $columnaProducto[$tipo] => (int) $item['producto_id'],
                        'talla_id'              => $talla->id,
                        'talla'                 => $talla->talla,
                        'precio'                => $talla->precio,
                        'cantidad'              => (int) $item['cantidad'],
                    ];
                }
            }

            $creado = $checkoutService->confirmar($clienteId, $carritos);

            $nuevoEstadoPago = match ($validado['estado_pago_registro']) {
                'completo' => 'pagado_completo',
                'adelanto' => 'adelanto_verificado',
                default    => null,
            };

            if ($nuevoEstadoPago) {
                foreach (array_filter($creado) as $pedido) {
                    $pedido->estado_pago = $nuevoEstadoPago;
                    $pedido->save();
                }
            }

            return $creado;
        });

        if ($resultado['maestro']) {
            return redirect()->route('admin.pedidos-tienda.show', $resultado['maestro']->id)
                ->with('success', 'Pedido creado correctamente.');
        }

        $rutaPorTipo = [
            'pedidoPlantilla' => 'admin.pedidos-plantillas.show',
            'pedidoUniforme'  => 'admin.pedidos-uniformes.show',
            'pedidoChompa'    => 'admin.pedidos-chompas.show',
        ];

        foreach ($rutaPorTipo as $clave => $ruta) {
            if ($resultado[$clave]) {
                return redirect()->route($ruta, $resultado[$clave]->id)
                    ->with('success', 'Pedido creado correctamente.');
            }
        }

        return redirect()->route('admin.pedidos-tienda.index')
            ->with('success', 'Pedido creado correctamente.');
    }

    // ── Rechaza teléfonos "de relleno" que sí cumplen el formato 09XXXXXXXX
    //    pero son evidentemente falsos: todos los dígitos iguales o una
    //    secuencia consecutiva ascendente/descendente (ej: 0987654321).
    private function reglaTelefonoReal(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail) {
            if (!preg_match('/^09\d{8}$/', (string) $value)) {
                return;
            }

            $resto = substr($value, 2);

            if (preg_match('/^(\d)\1+$/', $resto)) {
                $fail('Ese número de teléfono no parece real (dígitos repetidos).');
                return;
            }

            $ascendente = true;
            $descendente = true;
            for ($i = 1; $i < strlen($resto); $i++) {
                $prev = (int) $resto[$i - 1];
                $curr = (int) $resto[$i];
                if ($curr !== $prev + 1) {
                    $ascendente = false;
                }
                if ($curr !== $prev - 1) {
                    $descendente = false;
                }
            }

            if ($ascendente || $descendente) {
                $fail('Ese número de teléfono no parece real (dígitos en secuencia).');
            }
        };
    }
}
