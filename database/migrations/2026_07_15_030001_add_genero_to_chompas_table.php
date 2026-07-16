<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chompas', function (Blueprint $table) {
            $table->string('genero', 10)->default('unisex')->after('tipo_tela');
        });
    }

    public function down(): void
    {
        Schema::table('chompas', function (Blueprint $table) {
            $table->dropColumn('genero');
        });
    }
};
