<?php

namespace App\Http\Requests;

use App\Enums\TipoCliente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_cliente'     => ['required', Rule::enum(TipoCliente::class)],
            'razon_social'     => ['required', 'string', 'max:255'],
            'nombre_comercial' => ['nullable', 'string', 'max:255'],
            'cuit' => [
                'required', 'string', 'size:11', 'regex:/^\d{11}$/',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $exists = \App\Models\Cliente::where('cuit', $value)->exists();
                    if ($exists) {
                        $fail('Ya existe un cliente con ese CUIT.');
                    }
                },
            ],
            'referente'        => ['nullable', 'string', 'max:255'],
            'email'            => ['nullable', 'email', 'max:255'],
            'telefono'         => ['nullable', 'string', 'max:50'],
            'notes'            => ['nullable', 'string'],
            'activo'           => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'cuit.unique'           => 'Ya existe un cliente con ese CUIT.',
            'cuit.size'             => 'El CUIT debe tener exactamente 11 dígitos sin guiones.',
            'cuit.regex'            => 'El CUIT solo puede contener números.',
            'razon_social.required' => 'La razón social es obligatoria.',
        ];
    }
}
