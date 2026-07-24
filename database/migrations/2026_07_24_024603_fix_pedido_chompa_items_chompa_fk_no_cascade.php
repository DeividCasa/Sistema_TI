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
        Schema::table('pedido_chompa_items', function (Blueprint $table) {
            $table->dropForeign(['chompa_id']);
            $table->foreign('chompa_id')->references('id')->on('chompas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_chompa_items', function (Blueprint $table) {
            $table->dropForeign(['chompa_id']);
            $table->foreign('chompa_id')->references('id')->on('chompas')->onDelete('cascade');
        });
    }
};
