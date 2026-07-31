<?php

namespace App\Support;

class WhatsappHelper
{
    public static function link(?string $telefono, string $mensaje): ?string
    {
        if (!$telefono) {
            return null;
        }

        $digitos = preg_replace('/\D/', '', $telefono);

        if ($digitos === '') {
            return null;
        }

        if (str_starts_with($digitos, '0')) {
            $digitos = '593' . substr($digitos, 1);
        } elseif (!str_starts_with($digitos, '593')) {
            $digitos = '593' . $digitos;
        }

        return 'https://wa.me/' . $digitos . '?text=' . rawurlencode($mensaje);
    }
}
