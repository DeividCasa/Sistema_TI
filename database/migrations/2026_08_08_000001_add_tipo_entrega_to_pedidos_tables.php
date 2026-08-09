<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tablas = ['pedidos', 'pedidos_maestro', 'pedidos_uniforme', 'pedidos_chompa', 'pedidos_plantilla'];

    public function up(): void
    {
        foreach ($this->tablas as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->enum('tipo_entrega', ['retiro', 'domicilio'])->nullable();
                $table->string('direccion_entrega', 255)->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tablas as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->dropColumn(['tipo_entrega', 'direccion_entrega']);
            });
        }
    }
};
