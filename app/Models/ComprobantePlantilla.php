<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprobantePlantilla extends Model
{
    protected $table = 'comprobantes_plantilla';

    protected $fillable = [
        'pedido_plantilla_id',
        'tipo',
        'archivo',
        'referencia',
        'monto',
        'estado',
        'nota_admin',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidoPlantilla::class, 'pedido_plantilla_id');
    }
}
