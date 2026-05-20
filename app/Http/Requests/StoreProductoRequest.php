<?php

namespace App\Http\Requests;

use App\Enums\TipoPricing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'activo' => $this->has('activo') ? $this->boolean('activo') : true,
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'max:255'],
            'descripcion'  => ['nullable', 'string'],
            'tipo_pricing' => ['required', Rule::enum(TipoPricing::class)],
            'activo'       => ['boolean'],
        ];
    }
}
