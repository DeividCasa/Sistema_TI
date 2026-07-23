<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonio extends Model
{
    protected $table = 'testimonios';

    protected $fillable = [
        'cliente_id',
        'nombre_cliente',
        'texto',
        'calificacion',
        'estado',
        'imagen',
        'activo',
        'orden',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
