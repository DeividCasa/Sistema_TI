<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos_chompa', function (Blueprint $table) {
            $table->string('tiempo_estimado')->nullable()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos_chompa', function (Blueprint $table) {
            $table->dropColumn('tiempo_estimado');
        });
    }
};
