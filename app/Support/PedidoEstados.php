<?php

namespace App\Support;

class PedidoEstados
{
    public static function label(string $estado): string
    {
        return match ($estado) {
            'recibido'       => 'Recibido',
            'en_produccion'  => 'En producción',
            'listo'          => 'Listo',
            'enviado'        => 'Enviado',
            'entregado'      => 'Entregado',
            'cancelado'      => 'Cancelado',
            default          => ucfirst(str_replace('_', ' ', $estado)),
        };
    }

    public static function pagoVerificado(string $estadoPago): bool
    {
        return in_array($estadoPago, ['adelanto_verificado', 'saldo_pendiente', 'pagado_completo'], true);
    }
}
