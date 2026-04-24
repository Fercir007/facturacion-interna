<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones_comerciales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->string('status');
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->string('currency');
            $table->json('terms');
            $table->timestamps();

            $table->unique(['cliente_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones_comerciales');
    }
};
