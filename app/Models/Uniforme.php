<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uniforme extends Model
{
    protected $table = 'uniformes';

    protected $fillable = [
        'nombre',
        'tipo_tela',
        'genero',
        'descripcion',
        'imagen',
        'activo',
    ];

    public function tallas()
    {
        return $this->hasMany(UniformeTalla::class, 'uniforme_id')->orderByRaw('CAST(talla AS UNSIGNED)');
    }
}
