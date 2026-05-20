<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumos_mensuales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periodo_facturacion_id')
                  ->constrained('periodos_facturacion')
                  ->cascadeOnDelete();
            $table->foreignId('contrato_producto_id')
                  ->constrained('contrato_productos')
                  ->cascadeOnDelete();
            $table->decimal('cantidad_unidades', 14, 2)->default(0);
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->unique(['periodo_facturacion_id', 'contrato_producto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumos_mensuales');
    }
};
