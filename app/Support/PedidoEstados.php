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

    // ── Estados válidos según el tipo de entrega: en retiro presencial no
    //    existe un paso de "Enviado" porque no se despacha nada.
    public static function estadosDisponibles(?string $tipoEntrega): array
    {
        $todos = ['recibido', 'en_produccion', 'listo', 'enviado', 'entregado', 'cancelado'];

        if ($tipoEntrega === 'retiro') {
            return array_values(array_diff($todos, ['enviado']));
        }

        return $todos;
    }

    public static function etiquetaEntrega(?string $tipoEntrega): ?string
    {
        return match ($tipoEntrega) {
            'retiro'    => 'Retiro en tienda',
            'domicilio' => 'Envío a domicilio',
            default     => null,
        };
    }

    // ── Mensaje para el cliente (WhatsApp/correo), adaptado según si el
    //    pedido es para retirar en tienda o para enviar a domicilio.
    public static function mensajeParaCliente(string $estado, ?string $tipoEntrega = null, ?string $tiempoEstimado = null): string
    {
        $mensaje = match ($estado) {
            'recibido'      => 'Tu pedido fue recibido y ya está en cola de producción.',
            'en_produccion' => 'Tu pedido está en producción.',
            'listo'         => $tipoEntrega === 'retiro'
                ? '¡Tu pedido ya está listo! Ya puedes venir a retirarlo.'
                : '¡Tu pedido ya está listo! En breve se procederá a enviarlo.',
            'enviado'       => 'Tu pedido ya fue enviado a tu domicilio.',
            'entregado'     => $tipoEntrega === 'retiro'
                ? 'Tu pedido fue retirado. ¡Gracias por tu compra!'
                : 'Tu pedido fue entregado. ¡Gracias por tu compra!',
            'cancelado'     => 'Tu pedido fue cancelado.',
            default         => self::label($estado) . '.',
        };

        if ($tiempoEstimado && in_array($estado, ['recibido', 'en_produccion', 'listo'], true)) {
            $mensaje .= " Tiempo estimado de entrega: {$tiempoEstimado}.";
        }

        return $mensaje;
    }
}
