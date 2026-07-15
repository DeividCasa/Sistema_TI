<?php

namespace App\Services;

use App\Models\PedidoChompa;
use App\Models\PedidoChompaItem;
use App\Models\PedidoMaestro;
use App\Models\PedidoPlantilla;
use App\Models\PedidoPlantillaItem;
use App\Models\PedidoUniforme;
use App\Models\PedidoUniformeItem;

class CheckoutService
{
    /**
     * Confirma los carritos de sesión (ropa/uniformes/chompas) del cliente.
     * Si hay 2 o más tipos con items, los agrupa bajo un PedidoMaestro
     * con un solo código/total/adelanto/saldo combinados.
     */
    public function confirmar(int $clienteId): array
    {
        $carritoPlantillas = session('carrito_plantillas', []);
        $carritoUniformes  = session('carrito_uniformes', []);
        $carritoChompas    = session('carrito_chompas', []);

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

        if ($pedidoPlantilla) {
            session()->forget('carrito_plantillas');
        }
        if ($pedidoUniforme) {
            session()->forget('carrito_uniformes');
        }
        if ($pedidoChompa) {
            session()->forget('carrito_chompas');
        }

        return compact('maestro', 'pedidoPlantilla', 'pedidoUniforme', 'pedidoChompa');
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

        $codigo = 'ROP-' . date('Y') . '-' . str_pad(PedidoPlantilla::count() + 1, 3, '0', STR_PAD_LEFT);

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
                'talla'               => $item['talla'],
                'color'               => $item['color'],
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

        $codigo = 'UE-' . date('Y') . '-' . str_pad(PedidoUniforme::count() + 1, 3, '0', STR_PAD_LEFT);

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

    private function generarCodigoMaestro(): string
    {
        do {
            $codigo = 'PED-' . date('Y') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (PedidoMaestro::where('codigo', $codigo)->exists());

        return $codigo;
    }
}
