<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->boolean('destacado')->default(false)->after('activa');
        });
        Schema::table('uniformes', function (Blueprint $table) {
            $table->boolean('destacado')->default(false)->after('activo');
        });
        Schema::table('chompas', function (Blueprint $table) {
            $table->boolean('destacado')->default(false)->after('activo');
        });
    }

    public function down(): void
    {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->dropColumn('destacado');
        });
        Schema::table('uniformes', function (Blueprint $table) {
            $table->dropColumn('destacado');
        });
        Schema::table('chompas', function (Blueprint $table) {
            $table->dropColumn('destacado');
        });
    }
};
