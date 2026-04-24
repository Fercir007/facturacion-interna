<?php

namespace App\Http\Requests;

use App\Enums\ConfiguracionComercialStatus;
use App\Enums\Currency;
use App\Models\ConfiguracionComercial;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateConfiguracionComercialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'effective_from' => ['sometimes', 'date'],
            'effective_to' => ['sometimes', 'nullable', 'date'],
            'currency' => ['sometimes', Rule::enum(Currency::class)],
            'terms' => ['sometimes', 'array'],
            'status' => ['sometimes', Rule::enum(ConfiguracionComercialStatus::class)],
            'version' => ['prohibited'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var ConfiguracionComercial $config */
            $config = $this->route('configuracion_comercial');
            $from = $this->input('effective_from') ?? $config->effective_from?->toDateString();
            $to = $this->input('effective_to') ?? $config->effective_to?->toDateString();

            if ($from !== null && $to !== null && strtotime((string) $to) < strtotime((string) $from)) {
                $validator->errors()->add(
                    'effective_to',
                    __('validation.after_or_equal', ['attribute' => 'effective_to', 'date' => 'effective_from'])
                );
            }
        });
    }
}
