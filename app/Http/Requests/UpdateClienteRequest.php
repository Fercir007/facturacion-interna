<?php

namespace App\Http\Requests;

use App\Enums\TipoCliente;
use App\Models\Cliente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Cliente $cliente */
        $cliente = $this->route('cliente');

        return [
            'tipo_cliente'     => ['sometimes', Rule::enum(TipoCliente::class)],
            'razon_social'     => ['sometimes', 'required', 'string', 'max:255'],
            'nombre_comercial' => ['sometimes', 'nullable', 'string', 'max:255'],
            'cuit'             => [
                'sometimes', 'required', 'string', 'size:11', 'regex:/^\d{11}$/',
                Rule::unique('clientes', 'cuit')->ignore($cliente->id)->whereNull('deleted_at'),
            ],
            'referente'        => ['sometimes', 'nullable', 'string', 'max:255'],
            'email'            => ['sometimes', 'nullable', 'email', 'max:255'],
            'telefono'         => ['sometimes', 'nullable', 'string', 'max:50'],
            'notes'            => ['sometimes', 'nullable', 'string'],
            'activo'           => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'cuit.unique' => 'Ya existe otro cliente con ese CUIT.',
            'cuit.size'   => 'El CUIT debe tener exactamente 11 dígitos sin guiones.',
            'cuit.regex'  => 'El CUIT solo puede contener números.',
        ];
    }
}
