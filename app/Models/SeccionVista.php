<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeccionVista extends Model
{
    protected $table = 'secciones_vistas';

    protected $fillable = [
        'admin_id',
        'seccion',
        'visto_at',
    ];

    protected $casts = [
        'visto_at' => 'datetime',
    ];
}
