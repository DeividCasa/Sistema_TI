<?php

namespace App\Support;

class CedulaEcuador
{
    public static function esValida(?string $cedula): bool
    {
        if (!$cedula || !preg_match('/^\d{10}$/', $cedula)) {
            return false;
        }

        $provincia = (int) substr($cedula, 0, 2);
        if ($provincia < 1 || $provincia > 24) {
            return false;
        }

        $tercerDigito = (int) $cedula[2];
        if ($tercerDigito > 6) {
            return false;
        }

        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $valor = (int) $cedula[$i] * $coeficientes[$i];
            $suma += $valor > 9 ? $valor - 9 : $valor;
        }

        $verificador = (10 - ($suma % 10)) % 10;

        return $verificador === (int) $cedula[9];
    }
}
