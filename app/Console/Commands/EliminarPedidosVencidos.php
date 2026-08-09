<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use App\Models\PedidoChompa;
use App\Models\PedidoMaestro;
use App\Models\PedidoPlantilla;
use App\Models\PedidoUniforme;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class EliminarPedidosVencidos extends Command
{
    protected $signature = 'pedidos:limpiar-vencidos';

    protected $description = 'Elimina los pedidos que llevan más de 7 días sin recibir ningún adelanto';

    private const DIAS_LIMITE = 7;

    public function handle(): int
    {
        $limite = now()->subDays(self::DIAS_LIMITE);

        $modelos = [
            'Camisetas (diseño)' => Pedido::class,
            'Pedidos combinados' => PedidoMaestro::class,
            'Uniformes'          => PedidoUniforme::class,
            'Chompas'            => PedidoChompa::class,
            'Ropa (plantillas)'  => PedidoPlantilla::class,
        ];

        foreach ($modelos as $etiqueta => $modelo) {
            $vencidos = $modelo::where('estado_pago', 'pendiente')
                ->where('created_at', '<=', $limite)
                ->get();

            foreach ($vencidos as $pedido) {
                foreach ($pedido->comprobantes as $comprobante) {
                    if ($comprobante->archivo) {
                        Storage::disk('public')->delete($comprobante->archivo);
                    }
                }
                $pedido->delete();
            }

            $this->info("{$etiqueta}: {$vencidos->count()} pedido(s) eliminado(s).");
        }

        return self::SUCCESS;
    }
}
