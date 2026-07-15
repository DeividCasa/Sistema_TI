<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprobanteMaestro extends Model
{
    protected $table = 'comprobantes_maestro';

    protected $fillable = [
        'pedido_maestro_id',
        'tipo',
        'archivo',
        'referencia',
        'monto',
        'estado',
        'nota_admin',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidoMaestro::class, 'pedido_maestro_id');
    }
}
