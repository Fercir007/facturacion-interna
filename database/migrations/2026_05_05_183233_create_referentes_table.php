<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->cascadeOnDelete();
            $table->string('nombre');
            $table->string('email')->nullable();
            $table->string('telefono', 50)->nullable();
            $table->boolean('es_principal')->default(false);
            $table->timestamps();
        });

        // Migrar referente existente en clientes a la nueva tabla
        DB::statement("
            INSERT INTO referentes (cliente_id, nombre, es_principal, created_at, updated_at)
            SELECT id, referente, true, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
            FROM clientes
            WHERE referente IS NOT NULL AND referente != ''
        ");

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('referente');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('referente')->nullable();
        });

        Schema::dropIfExists('referentes');
    }
};
