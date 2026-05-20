<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrato_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('contratos')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->string('currency');
            $table->decimal('setup_monto', 12, 2)->default(0);
            $table->unsignedTinyInteger('setup_cuotas')->default(1);
            $table->decimal('mrr', 12, 2)->default(0);
            $table->decimal('precio_por_unidad', 12, 4)->nullable();
            $table->decimal('comision_porcentaje', 6, 4)->nullable();
            $table->string('aplica_sobre')->nullable();
            $table->unsignedTinyInteger('banda')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrato_productos');
    }
};
