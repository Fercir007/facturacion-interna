<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrato_producto_bandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_producto_id')
                ->constrained('contrato_productos')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('numero_banda');
            $table->unsignedInteger('unidades_desde')->default(0);
            $table->unsignedInteger('unidades_hasta')->nullable();
            $table->decimal('costo_fijo', 12, 2)->default(0);
            $table->decimal('precio_excedente_por_unidad', 12, 4)->default(0);
            $table->boolean('es_banda_activa')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrato_producto_bandas');
    }
};
