<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('informacion_local', function (Blueprint $table) {
            $table->decimal('mapa_lat', 10, 7)->nullable()->after('direccion');
            $table->decimal('mapa_lng', 10, 7)->nullable()->after('mapa_lat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informacion_local', function (Blueprint $table) {
            $table->dropColumn(['mapa_lat', 'mapa_lng']);
        });
    }
};
