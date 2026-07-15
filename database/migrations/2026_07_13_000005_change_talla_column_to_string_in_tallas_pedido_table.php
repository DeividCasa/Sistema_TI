<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * tallas_pedido.talla era un enum('XS','S','M','L','XL','XXL','XXXL'), pero
     * las tallas nacen en tallas_solicitud como texto libre (string(10)) — el
     * cliente puede escribir tallas numéricas (ej. "34") al pedir una cotización.
     * Al aceptar la cotización, SolicitudDisenoController::aceptar() copia esas
     * tallas tal cual a tallas_pedido, y el enum rechaza cualquier valor fuera
     * de esa lista fija (error "Data truncated for column 'talla'"). Se iguala
     * el tipo de columna al de tallas_solicitud para aceptar cualquier talla.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE tallas_pedido MODIFY talla VARCHAR(10) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tallas_pedido MODIFY talla ENUM('XS','S','M','L','XL','XXL','XXXL') NOT NULL");
    }
};
