<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Cliente extends Authenticatable
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'direccion',
        'ciudad',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function disenios()
    {
        return $this->hasMany(Disenio::class, 'cliente_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }
}