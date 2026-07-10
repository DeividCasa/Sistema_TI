<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TallaSolicitud extends Model
{
    protected $table = 'tallas_solicitud';

    protected $fillable = [
        'solicitud_id',
        'talla',
        'cantidad',
    ];

    public function solicitud()
    {
        return $this->belongsTo(SolicitudDiseno::class, 'solicitud_id');
    }
}
