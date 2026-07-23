<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->timestamp('visto_admin_at')->nullable();
        });
        Schema::table('pedidos_uniforme', function (Blueprint $table) {
            $table->timestamp('visto_admin_at')->nullable();
        });
        Schema::table('pedidos_chompa', function (Blueprint $table) {
            $table->timestamp('visto_admin_at')->nullable();
        });
        Schema::table('pedidos_plantilla', function (Blueprint $table) {
            $table->timestamp('visto_admin_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('visto_admin_at');
        });
        Schema::table('pedidos_uniforme', function (Blueprint $table) {
            $table->dropColumn('visto_admin_at');
        });
        Schema::table('pedidos_chompa', function (Blueprint $table) {
            $table->dropColumn('visto_admin_at');
        });
        Schema::table('pedidos_plantilla', function (Blueprint $table) {
            $table->dropColumn('visto_admin_at');
        });
    }
};
