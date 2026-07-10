<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disenios', function (Blueprint $table) {
            $table->string('imagen_atras', 255)->nullable()->after('imagen_generada');
        });
    }

    public function down(): void
    {
        Schema::table('disenios', function (Blueprint $table) {
            $table->dropColumn('imagen_atras');
        });
    }
};
