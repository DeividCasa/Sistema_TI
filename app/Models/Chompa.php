<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chompa extends Model
{
    protected $table = 'chompas';

    protected $fillable = [
        'nombre',
        'tipo_tela',
        'descripcion',
        'imagen',
        'activo',
    ];

    public function tallas()
    {
        // Ordenar: primero tallas numéricas (32,34...) luego alfas (XS,S,M,L,XL,XXL)
        return $this->hasMany(ChompaTalla::class, 'chompa_id')
                    ->orderByRaw("CASE WHEN talla REGEXP '^[0-9]+$' THEN CAST(talla AS UNSIGNED) ELSE 999 END, talla");
    }
}
