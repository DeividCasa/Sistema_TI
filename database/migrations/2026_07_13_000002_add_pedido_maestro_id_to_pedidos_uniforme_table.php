<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos_uniforme', function (Blueprint $table) {
            $table->foreignId('pedido_maestro_id')->nullable()->after('cliente_id')
                  ->constrained('pedidos_maestro')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos_uniforme', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pedido_maestro_id');
        });
    }
};
