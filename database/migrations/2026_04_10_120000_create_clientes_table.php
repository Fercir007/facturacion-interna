<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_cliente')->default('comercio');
            $table->string('razon_social');
            $table->string('nombre_comercial')->nullable();
            $table->string('cuit', 11);
            $table->string('referente')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono', 50)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
