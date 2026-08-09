<?php

namespace App\Services;

use App\Models\PedidoChompa;
use App\Models\PedidoChompaItem;
use App\Models\PedidoMaestro;
use App\Models\PedidoPlantilla;
use App\Models\PedidoPlantillaItem;
use App\Models\PedidoUniforme;
use App\Models\PedidoUniformeItem;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    /**
     * Confirma los carritos (ropa/uniformes/chompas) del cliente.
     * Si hay 2 o más tipos con items, los agrupa bajo un PedidoMaestro
     * con un solo código/total/adelanto/saldo combinados.
     *
     * Por defecto lee los carritos de la sesión (flujo normal del cliente).
     * Si se pasa $carritos (claves 'plantillas'/'uniformes'/'chompas'), se usan esos
     * en su lugar y no se toca la sesión — así el admin puede armar un pedido a
     * nombre de un cliente sin depender del carrito de sesión de nadie.
     */
    public function confirmar(int $clienteId, ?array $carritos = null): array
    {
        $usaSesion = $carritos === null;

        $carritoPlantillas = $carritos['plantillas'] ?? session('carrito_plantillas', []);
        $carritoUniformes  = $carritos['uniformes']  ?? session('carrito_uniformes', []);
        $carritoChompas    = $carritos['chompas']    ?? session('carrito_chompas', []);

        // Todo el checkout corre en una sola transacción: si algo falla a
        // mitad de camino (ej. un carrito con datos corruptos truena en el
        // segundo tipo de prenda), no deben quedar pedidos huérfanos a medio
        // crear ni un PedidoMaestro apuntando a hijos que no se guardaron.
        $resultado = DB::transaction(function () use ($clienteId, $carritoPlantillas, $carritoUniformes, $carritoChompas) {
            $pedidoPlantilla = empty($carritoPlantillas) ? null : $this->crearPedidoPlantilla($clienteId, $carritoPlantillas);
            $pedidoUniforme  = empty($carritoUniformes)  ? null : $this->crearPedidoUniforme($clienteId, $carritoUniformes);
            $pedidoChompa    = empty($carritoChompas)    ? null : $this->crearPedidoChompa($clienteId, $carritoChompas);

            $hijos = array_filter([$pedidoPlantilla, $pedidoUniforme, $pedidoChompa]);
            $maestro = null;

            if (count($hijos) > 1) {
                $maestro = PedidoMaestro::create([
                    'cliente_id'      => $clienteId,
                    'codigo'          => $this->generarCodigoMaestro(),
                    'precio_total'    => array_sum(array_map(fn ($h) => $h->precio_total, $hijos)),
                    'precio_adelanto' => array_sum(array_map(fn ($h) => $h->precio_adelanto, $hijos)),
                    'precio_saldo'    => array_sum(array_map(fn ($h) => $h->precio_saldo, $hijos)),
                    'estado_pago'     => 'pendiente',
                ]);

                foreach ($hijos as $hijo) {
                    $hijo->update(['pedido_maestro_id' => $maestro->id]);
                }
            }

            return compact('maestro', 'pedidoPlantilla', 'pedidoUniforme', 'pedidoChompa');
        });

        // Solo se limpia la sesión después de que la transacción confirmó
        // sin errores; si hubiera fallado, el carrito sigue intacto para
        // que el cliente pueda reintentar sin perder lo que tenía.
        if ($usaSesion) {
            if ($resultado['pedidoPlantilla']) {
                session()->forget('carrito_plantillas');
            }
            if ($resultado['pedidoUniforme']) {
                session()->forget('carrito_uniformes');
            }
            if ($resultado['pedidoChompa']) {
                session()->forget('carrito_chompas');
            }
        }

        return $resultado;
    }

    private function crearPedidoPlantilla(int $clienteId, array $carrito): PedidoPlantilla
    {
        $total = 0;
        $cantidadTotal = 0;
        foreach ($carrito as $item) {
            $total         += $item['precio'] * $item['cantidad'];
            $cantidadTotal += $item['cantidad'];
        }

        $adelanto = round($total / 2, 2);
        $saldo    = $total - $adelanto;

        $codigo = $this->generarCodigoSecuencial('ROP-' . date('Y') . '-', PedidoPlantilla::class);

        $pedido = PedidoPlantilla::create([
            'cliente_id'      => $clienteId,
            'codigo'          => $codigo,
            'cantidad_total'  => $cantidadTotal,
            'precio_total'    => $total,
            'precio_adelanto' => $adelanto,
            'precio_saldo'    => $saldo,
            'estado'          => 'recibido',
            'estado_pago'     => 'pendiente',
        ]);

        foreach ($carrito as $item) {
            PedidoPlantillaItem::create([
                'pedido_plantilla_id' => $pedido->id,
                'plantilla_id'        => $item['plantilla_id'],
                'plantilla_talla_id'  => $item['talla_id'] ?? null,
                'talla'               => $item['talla'],
                'precio_unitario'     => $item['precio'],
                'cantidad'            => $item['cantidad'],
                'subtotal'            => $item['precio'] * $item['cantidad'],
            ]);
        }

        return $pedido;
    }

    private function crearPedidoUniforme(int $clienteId, array $carrito): PedidoUniforme
    {
        $total = 0;
        $cantidadTotal = 0;
        foreach ($carrito as $item) {
            $total         += $item['precio'] * $item['cantidad'];
            $cantidadTotal += $item['cantidad'];
        }

        $adelanto = round($total / 2, 2);
        $saldo    = $total - $adelanto;

        $codigo = $this->generarCodigoSecuencial('UE-' . date('Y') . '-', PedidoUniforme::class);

        $pedido = PedidoUniforme::create([
            'cliente_id'      => $clienteId,
            'codigo'          => $codigo,
            'cantidad_total'  => $cantidadTotal,
            'precio_total'    => $total,
            'precio_adelanto' => $adelanto,
            'precio_saldo'    => $saldo,
            'estado'          => 'recibido',
            'estado_pago'     => 'pendiente',
        ]);

        foreach ($carrito as $item) {
            PedidoUniformeItem::create([
                'pedido_uniforme_id' => $pedido->id,
                'uniforme_id'        => $item['uniforme_id'],
                'uniforme_talla_id'  => $item['talla_id'],
                'talla'              => $item['talla'],
                'precio_unitario'    => $item['precio'],
                'cantidad'           => $item['cantidad'],
                'subtotal'           => $item['precio'] * $item['cantidad'],
            ]);
        }

        return $pedido;
    }

    private function crearPedidoChompa(int $clienteId, array $carrito): PedidoChompa
    {
        $total = 0;
        $cantidadTotal = 0;
        foreach ($carrito as $item) {
            $total         += $item['precio'] * $item['cantidad'];
            $cantidadTotal += $item['cantidad'];
        }

        $adelanto = round($total / 2, 2);
        $saldo    = $total - $adelanto;

        do {
            $codigo = 'PCH-' . date('Y') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (PedidoChompa::where('codigo', $codigo)->exists());

        $pedido = PedidoChompa::create([
            'cliente_id'      => $clienteId,
            'codigo'          => $codigo,
            'cantidad_total'  => $cantidadTotal,
            'precio_total'    => $total,
            'precio_adelanto' => $adelanto,
            'precio_saldo'    => $saldo,
            'estado'          => 'recibido',
            'estado_pago'     => 'pendiente',
        ]);

        foreach ($carrito as $item) {
            PedidoChompaItem::create([
                'pedido_chompa_id' => $pedido->id,
                'chompa_id'        => $item['chompa_id'],
                'chompa_talla_id'  => $item['talla_id'],
                'talla'            => $item['talla'],
                'precio_unitario'  => $item['precio'],
                'cantidad'         => $item['cantidad'],
                'subtotal'         => $item['precio'] * $item['cantidad'],
            ]);
        }

        return $pedido;
    }

    /**
     * Genera un código correlativo tipo "PREFIJO001" verificando que no
     * exista ya, incrementando en cada colisión. Antes se usaba
     * `Modelo::count() + 1` directo, que puede repetirse si dos checkouts
     * concurrentes leen el mismo count() antes de que el primero confirme
     * su insert, o si hay huecos por pedidos borrados — eso truena con un
     * QueryException por el índice único de "codigo" y da un 500 al cliente.
     */
    private function generarCodigoSecuencial(string $prefijo, string $modelClass): string
    {
        $siguiente = $modelClass::count() + 1;

        do {
            $codigo = $prefijo . str_pad($siguiente, 3, '0', STR_PAD_LEFT);
            $existe = $modelClass::where('codigo', $codigo)->exists();
            $siguiente++;
        } while ($existe);

        return $codigo;
    }

    private function generarCodigoMaestro(): string
    {
        do {
            $codigo = 'PED-' . date('Y') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (PedidoMaestro::where('codigo', $codigo)->exists());

        return $codigo;
    }
}
