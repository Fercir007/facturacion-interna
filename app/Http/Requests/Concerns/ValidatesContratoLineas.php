<?php

namespace App\Http\Requests\Concerns;

use App\Enums\Currency;
use Illuminate\Validation\Validator;

trait ValidatesContratoLineas
{
    protected function prepareLineasForValidation(): void
    {
        $lineas = collect($this->input('lineas', []))
            ->filter(fn ($l) => ! empty($l['producto_id']))
            ->map(function ($linea) {
                $linea = is_array($linea) ? $linea : [];
                $bandas = collect($linea['bandas'] ?? [])
                    ->filter(fn ($b) => is_array($b) && ($b['numero_banda'] ?? '') !== '')
                    ->map(function ($b) {
                        $activa = filter_var($b['es_banda_activa'] ?? false, FILTER_VALIDATE_BOOLEAN)
                            || ($b['es_banda_activa'] ?? '') === '1'
                            || ($b['es_banda_activa'] ?? '') === 1;

                        $hasta = $b['unidades_hasta'] ?? null;
                        if ($hasta === '' || $hasta === null) {
                            $b['unidades_hasta'] = null;
                        }

                        return array_merge($b, ['es_banda_activa' => $activa]);
                    })
                    ->values()
                    ->all();
                $linea['bandas'] = $bandas;

                return $linea;
            })
            ->values()
            ->all();

        $this->merge(['lineas' => $lineas]);
    }

    protected function rulesLineas(): array
    {
        return [
            'lineas'                                       => ['nullable', 'array'],
            'lineas.*.producto_id'                         => ['required', 'integer', 'exists:productos,id'],
            'lineas.*.currency'                            => ['required', \Illuminate\Validation\Rule::enum(Currency::class)],
            'lineas.*.setup_monto'                         => ['required', 'numeric', 'min:0'],
            'lineas.*.setup_cuotas'                        => ['required', 'integer', 'min:1', 'max:120'],
            'lineas.*.mrr'                                 => ['required', 'numeric', 'min:0'],
            'lineas.*.notas'                               => ['nullable', 'string'],
            'lineas.*.bandas'                              => ['required', 'array', 'min:1'],
            'lineas.*.bandas.*.numero_banda'               => ['required', 'integer', 'min:1', 'max:255'],
            'lineas.*.bandas.*.unidades_desde'             => ['required', 'integer', 'min:0'],
            'lineas.*.bandas.*.unidades_hasta'             => ['nullable', 'integer', 'min:0'],
            'lineas.*.bandas.*.costo_fijo'                 => ['required', 'numeric', 'min:0'],
            'lineas.*.bandas.*.precio_excedente_por_unidad' => ['required', 'numeric', 'min:0'],
            'lineas.*.bandas.*.es_banda_activa'            => ['required', 'boolean'],
        ];
    }

    protected function withValidatorLineas(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            foreach ($this->input('lineas', []) as $index => $linea) {
                $key    = "lineas.$index";
                $bandas = $linea['bandas'] ?? [];

                if ($bandas === []) {
                    $validator->errors()->add("$key.bandas", 'Cada línea debe tener al menos una banda definida.');

                    continue;
                }

                $numeros = collect($bandas)->pluck('numero_banda');
                if ($numeros->count() !== $numeros->unique()->count()) {
                    $validator->errors()->add("$key.bandas", 'El número de banda no puede repetirse en la misma línea.');
                }

                $activas = collect($bandas)->filter(function ($b) {
                    return filter_var($b['es_banda_activa'] ?? false, FILTER_VALIDATE_BOOLEAN)
                        || ($b['es_banda_activa'] ?? '') === '1'
                        || ($b['es_banda_activa'] ?? '') === 1;
                })->count();

                if ($activas !== 1) {
                    $validator->errors()->add("$key.bandas", 'Debés marcar exactamente una banda activa por línea.');
                }

                foreach ($bandas as $bi => $b) {
                    $hasta = $b['unidades_hasta'] ?? null;
                    if ($hasta === '' || $hasta === null) {
                        continue;
                    }
                    $desde = (int) ($b['unidades_desde'] ?? 0);
                    if ((int) $hasta < $desde) {
                        $validator->errors()->add("$key.bandas.$bi.unidades_hasta", '“Hasta” debe ser mayor o igual que “Desde”.');
                    }
                }
            }
        });
    }
}
