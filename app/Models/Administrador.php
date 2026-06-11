<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrador extends Authenticatable
{
    protected $table = 'administradores';

    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}