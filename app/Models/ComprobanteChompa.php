<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprobanteChompa extends Model
{
    protected $table = 'comprobantes_chompa';

    protected $fillable = [
        'pedido_chompa_id',
        'tipo',
        'archivo',
        'referencia',
        'monto',
        'estado',
        'nota_admin',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidoChompa::class, 'pedido_chompa_id');
    }
}
