<?php

namespace App\Support;

use App\Models\Pedido;
use App\Models\PedidoChompa;
use App\Models\PedidoPlantilla;
use App\Models\PedidoUniforme;
use App\Models\SeccionVista;
use Carbon\Carbon;

class Notificaciones
{
    public static function ultimaVisita(string $seccion): ?Carbon
    {
        return SeccionVista::where('admin_id', session('usuario_id'))
            ->where('seccion', $seccion)
            ->value('visto_at');
    }

    public static function marcarVisto(string $seccion): void
    {
        SeccionVista::updateOrCreate(
            ['admin_id' => session('usuario_id'), 'seccion' => $seccion],
            ['visto_at' => now()]
        );
    }

    public static function contarNuevos(string $seccion, string $modelClass): int
    {
        $desde = self::ultimaVisita($seccion);

        return $modelClass::when($desde, fn ($q) => $q->where('created_at', '>', $desde))->count();
    }

    public static function contarPedidosNuevos(): int
    {
        return collect([Pedido::class, PedidoUniforme::class, PedidoChompa::class, PedidoPlantilla::class])
            ->sum(fn ($modelClass) => $modelClass::whereNull('visto_admin_at')->count());
    }
}
