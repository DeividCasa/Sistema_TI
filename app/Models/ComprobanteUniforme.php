<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprobanteUniforme extends Model
{
    protected $table = 'comprobantes_uniforme';

    protected $fillable = [
        'pedido_uniforme_id',
        'tipo',
        'archivo',
        'referencia',
        'monto',
        'estado',
        'nota_admin',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidoUniforme::class, 'pedido_uniforme_id');
    }
}
