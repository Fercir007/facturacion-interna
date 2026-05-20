<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contrato_productos')
            && Schema::hasTable('contrato_producto_bandas')
            && Schema::hasColumn('contrato_productos', 'precio_por_unidad')) {
            $rows = DB::table('contrato_productos')->select([
                'id',
                'banda',
                'precio_por_unidad',
            ])->get();

            $now = now();
            foreach ($rows as $row) {
                $precio = $row->precio_por_unidad !== null ? (float) $row->precio_por_unidad : 0.0;
                $numero  = $row->banda !== null ? (int) $row->banda : 1;
                $numero  = max(1, min(255, $numero));

                DB::table('contrato_producto_bandas')->insert([
                    'contrato_producto_id'         => $row->id,
                    'numero_banda'                 => $numero,
                    'unidades_desde'               => 0,
                    'unidades_hasta'               => null,
                    'costo_fijo'                   => 0,
                    'precio_excedente_por_unidad'  => $precio,
                    'es_banda_activa'              => true,
                    'created_at'                   => $now,
                    'updated_at'                   => $now,
                ]);
            }
        }

        if (Schema::hasTable('contrato_productos') && Schema::hasColumn('contrato_productos', 'precio_por_unidad')) {
            Schema::table('contrato_productos', function (Blueprint $table) {
                $table->dropColumn([
                    'banda',
                    'comision_porcentaje',
                    'aplica_sobre',
                    'precio_por_unidad',
                ]);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contrato_productos')) {
            Schema::table('contrato_productos', function (Blueprint $table) {
                if (! Schema::hasColumn('contrato_productos', 'precio_por_unidad')) {
                    $table->decimal('precio_por_unidad', 12, 4)->nullable();
                }
                if (! Schema::hasColumn('contrato_productos', 'comision_porcentaje')) {
                    $table->decimal('comision_porcentaje', 6, 4)->nullable();
                }
                if (! Schema::hasColumn('contrato_productos', 'aplica_sobre')) {
                    $table->string('aplica_sobre')->nullable();
                }
                if (! Schema::hasColumn('contrato_productos', 'banda')) {
                    $table->unsignedTinyInteger('banda')->nullable();
                }
            });
        }

        if (Schema::hasTable('contrato_producto_bandas')) {
            DB::table('contrato_producto_bandas')->delete();
        }
    }
};
