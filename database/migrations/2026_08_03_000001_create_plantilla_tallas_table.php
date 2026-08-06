<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantilla_tallas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantilla_id')->constrained('plantillas')->onDelete('cascade');
            $table->string('talla', 10);          // XS, S, M, L, XL, XXL
            $table->decimal('precio', 10, 2);     // cada talla con su propio precio
            $table->boolean('disponible')->default(true);
            $table->timestamps();

            $table->unique(['plantilla_id', 'talla']); // no repetir talla en la misma prenda
        });

        // Migrar datos: cada prenda existente tenía un precio único + un array
        // JSON de tallas (sin precio propio). Se crea una fila en plantilla_tallas
        // por cada talla, con ese mismo precio como punto de partida (el admin
        // puede ajustar el precio por talla después desde el panel).
        $ahora = now();
        $filas = [];

        foreach (DB::table('plantillas')->select('id', 'precio', 'tallas')->get() as $plantilla) {
            $tallas = json_decode($plantilla->tallas ?? '[]', true) ?: [];
            $tallas = array_values(array_unique(array_map('strtoupper', $tallas)));

            if (empty($tallas)) {
                $tallas = ['M']; // no dejar la prenda sin ninguna talla comprable
            }

            foreach ($tallas as $talla) {
                $filas[] = [
                    'plantilla_id' => $plantilla->id,
                    'talla'        => $talla,
                    'precio'       => $plantilla->precio ?? 0,
                    'disponible'   => 1,
                    'created_at'   => $ahora,
                    'updated_at'   => $ahora,
                ];
            }
        }

        if (!empty($filas)) {
            DB::table('plantilla_tallas')->insert($filas);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plantilla_tallas');
    }
};
