<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE pedidos_chompa MODIFY estado_pago ENUM('pendiente','adelanto_enviado','adelanto_verificado','pago_completo_enviado','saldo_enviado','pagado_completo') NOT NULL DEFAULT 'pendiente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE pedidos_chompa MODIFY estado_pago ENUM('pendiente','adelanto_verificado','pagado_completo') NOT NULL DEFAULT 'pendiente'");
    }
};
