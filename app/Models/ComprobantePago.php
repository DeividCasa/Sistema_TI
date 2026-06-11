<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprobantePago extends Model
{
    protected $table = 'comprobantes_pago';

    protected $fillable = [
        'pedido_id',
        'tipo',
        'archivo',
        'referencia',
        'monto',
        'estado',
        'nota_admin',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}