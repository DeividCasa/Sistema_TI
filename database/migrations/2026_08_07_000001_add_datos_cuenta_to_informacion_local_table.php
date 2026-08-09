<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('informacion_local', function (Blueprint $table) {
            $table->string('cuenta_banco', 100)->nullable();
            $table->string('cuenta_tipo', 50)->nullable();
            $table->string('cuenta_numero', 50)->nullable();
            $table->string('cuenta_titular', 150)->nullable();
            $table->string('cuenta_identificacion', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('informacion_local', function (Blueprint $table) {
            $table->dropColumn(['cuenta_banco', 'cuenta_tipo', 'cuenta_numero', 'cuenta_titular', 'cuenta_identificacion']);
        });
    }
};
