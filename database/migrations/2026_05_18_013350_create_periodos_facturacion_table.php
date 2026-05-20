<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodos_facturacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->cascadeOnDelete();
            $table->unsignedSmallInteger('anio');
            $table->unsignedTinyInteger('mes'); // 1-12
            $table->string('status')->default('abierto'); // abierto, cerrado
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->unique(['cliente_id', 'anio', 'mes']); // un solo período por cliente/mes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodos_facturacion');
    }
};