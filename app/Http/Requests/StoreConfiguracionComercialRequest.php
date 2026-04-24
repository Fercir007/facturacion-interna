<?php

namespace App\Http\Requests;

use App\Enums\ConfiguracionComercialStatus;
use App\Enums\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConfiguracionComercialRequest extends FormRequest
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
            'effective_from' => ['required', 'date'],
            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'currency' => ['required', Rule::enum(Currency::class)],
            'terms' => ['required', 'array'],
            'status' => ['sometimes', Rule::enum(ConfiguracionComercialStatus::class)],
            'version' => ['prohibited'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('status')) {
            $this->merge([
                'status' => ConfiguracionComercialStatus::Borrador->value,
            ]);
        }
    }
}
