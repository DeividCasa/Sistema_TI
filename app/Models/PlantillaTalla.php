<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantillaTalla extends Model
{
    protected $table = 'plantilla_tallas';

    protected $fillable = [
        'plantilla_id',
        'talla',
        'precio',
        'disponible',
    ];

    public function plantilla()
    {
        return $this->belongsTo(Plantilla::class, 'plantilla_id');
    }
}
