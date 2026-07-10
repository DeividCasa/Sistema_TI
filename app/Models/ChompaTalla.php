<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChompaTalla extends Model
{
    protected $table = 'chompa_tallas';

    protected $fillable = [
        'chompa_id',
        'talla',
        'precio',
        'disponible',
    ];

    public function chompa()
    {
        return $this->belongsTo(Chompa::class, 'chompa_id');
    }
}
