<?php

namespace App\Http\Requests;

use App\Enums\ContratoStatus;
use App\Http\Requests\Concerns\ValidatesContratoLineas;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateContratoRequest extends FormRequest
{
    use ValidatesContratoLineas;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareLineasForValidation();
    }

    public function rules(): array
    {
        return array_merge([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin'    => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'status'       => ['required', Rule::enum(ContratoStatus::class)],
            'notas'        => ['nullable', 'string'],
        ], $this->rulesLineas());
    }

    public function withValidator(Validator $validator): void
    {
        $this->withValidatorLineas($validator);
    }
}
