<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->after('tipo_prenda');
            $table->decimal('precio', 10, 2)->default(0)->after('descripcion');
            $table->json('colores')->nullable()->after('precio');
            $table->json('tallas')->nullable()->after('colores');
        });
    }

    public function down(): void
    {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->dropColumn(['descripcion', 'precio', 'colores', 'tallas']);
        });
    }
};