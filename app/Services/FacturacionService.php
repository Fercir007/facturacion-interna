<?php

namespace App\Services;

use App\Models\ConsumoMensual;
use App\Models\PeriodoFacturacion;

class FacturacionService
{
    public function calcular(PeriodoFacturacion $periodo): array
    {
        $periodo->load(
            'consumos.contratoProducto.producto',
            'consumos.contratoProducto.bandaActiva',
            'consumos.contratoProducto.contrato'
        );

        $lineas    = [];
        $totalGeneral = 0;

        foreach ($periodo->consumos as $consumo) {
            $cp          = $consumo->contratoProducto;
            $banda       = $cp->bandaActiva;
            $unidades    = (float) $consumo->cantidad_unidades;

            if (!$banda) {
                $lineas[] = [
                    'producto'         => $cp->producto->nombre,
                    'currency'         => $cp->currency->value,
                    'unidades'         => $unidades,
                    'banda'            => null,
                    'costo_fijo'       => 0,
                    'unidades_excedentes' => 0,
                    'costo_excedente'  => 0,
                    'subtotal'         => 0,
                    'mrr'              => (float) $cp->mrr,
                    'total'            => (float) $cp->mrr,
                    'aplica_mrr'       => true,
                    'error'            => 'Sin banda activa definida',
                ];
                continue;
            }

            $costoFijo   = (float) $banda->costo_fijo;
            $techo       = $banda->unidades_hasta;
            $preciExc    = (float) $banda->precio_excedente_por_unidad;
            $mrr         = (float) $cp->mrr;

            // Calcular excedente
            $excedente     = 0;
            $costoExcedente = 0;

            if ($techo !== null && $unidades > $techo) {
                $excedente      = $unidades - $techo;
                $costoExcedente = $excedente * $preciExc;
            }

            $subtotal = $costoFijo + $costoExcedente;

            // Si el subtotal es menor al MRR, cobra el MRR
            $aplicaMrr = $subtotal < $mrr;
            $total     = $aplicaMrr ? $mrr : $subtotal;

            $totalGeneral += $total;

            $lineas[] = [
                'producto'            => $cp->producto->nombre,
                'currency'            => $cp->currency->value,
                'unidades'            => $unidades,
                'banda'               => $banda->numero_banda,
                'techo'               => $techo,
                'costo_fijo'          => $costoFijo,
                'unidades_excedentes' => $excedente,
                'precio_excedente'    => $preciExc,
                'costo_excedente'     => $costoExcedente,
                'subtotal'            => $subtotal,
                'mrr'                 => $mrr,
                'total'               => $total,
                'aplica_mrr'          => $aplicaMrr,
                'error'               => null,
            ];
        }

        return [
            'lineas'        => $lineas,
            'total_general' => $totalGeneral,
        ];
    }
}