<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    protected $table = 'plantillas';

    protected $fillable = [
        'nombre',
        'tipo_prenda',
        'archivo_3d',
        'imagen_preview',
        'activa',
    ];

    public function disenios()
    {
        return $this->hasMany(Disenio::class, 'plantilla_id');
    }
}