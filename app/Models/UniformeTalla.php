<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniformeTalla extends Model
{
    protected $table = 'uniforme_tallas';

    protected $fillable = [
        'uniforme_id',
        'talla',
        'precio',
        'disponible',
    ];

    public function uniforme()
    {
        return $this->belongsTo(Uniforme::class, 'uniforme_id');
    }
}
