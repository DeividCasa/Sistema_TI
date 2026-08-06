<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_plantilla_items', function (Blueprint $table) {
            $table->foreignId('plantilla_talla_id')->nullable()->after('plantilla_id')
                  ->constrained('plantilla_tallas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pedido_plantilla_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plantilla_talla_id');
        });
    }
};
