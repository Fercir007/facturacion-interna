<?php

namespace App\Services;

use App\Enums\ContratoStatus;
use App\Models\Contrato;

class ContratoService
{
    /**
     * Si el contrato queda vigente, el resto de contratos del mismo cliente dejan de estarlo.
     */
    public function expirarOtrosSiVigente(Contrato $contrato): void
    {
        if ($contrato->status !== ContratoStatus::Vigente) {
            return;
        }

        Contrato::query()
            ->where('cliente_id', $contrato->cliente_id)
            ->whereKeyNot($contrato->getKey())
            ->where('status', ContratoStatus::Vigente)
            ->update(['status' => ContratoStatus::Vencido->value]);
    }

    /**
     * @param  array<int, array{contrato_producto: array<string, mixed>, bandas: array<int, array<string, mixed>>}>  $lineas
     */
    public function sincronizarLineas(Contrato $contrato, array $lineas): void
    {
        $contrato->contratoProductos()->delete();

        foreach ($lineas as $item) {
            $attrs  = $item['contrato_producto'];
            $bandas = $this->normalizarBandasActivas($item['bandas'] ?? []);

            $cp = $contrato->contratoProductos()->create($attrs);

            foreach ($bandas as $b) {
                $cp->bandas()->create($b);
            }
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $bandas
     * @return array<int, array<string, mixed>>
     */
    private function normalizarBandasActivas(array $bandas): array
    {
        if ($bandas === []) {
            return [];
        }

        $firstTrue = false;
        $out       = [];

        foreach ($bandas as $b) {
            $activa = filter_var($b['es_banda_activa'] ?? false, FILTER_VALIDATE_BOOLEAN)
                || ($b['es_banda_activa'] ?? '') === '1'
                || ($b['es_banda_activa'] ?? '') === 1;

            if ($activa && ! $firstTrue) {
                $b['es_banda_activa'] = true;
                $firstTrue            = true;
            } else {
                $b['es_banda_activa'] = false;
            }

            $out[] = $b;
        }

        if (! $firstTrue && $out !== []) {
            $out[0]['es_banda_activa'] = true;
        }

        return $out;
    }
}
