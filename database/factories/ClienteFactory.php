<?php

namespace Database\Factories;

use App\Enums\TipoCliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tipo_cliente'     => TipoCliente::Comercio,
            'razon_social'     => fake()->company() . ' SA',
            'nombre_comercial' => fake()->optional(0.6)->company(),
            'cuit'             => (string) fake()->unique()->numerify('###########'),
            'referente'        => fake()->optional(0.7)->name(),
            'email'            => fake()->optional(0.8)->companyEmail(),
            'telefono'         => fake()->optional(0.5)->phoneNumber(),
            'notes'            => fake()->optional(0.3)->sentence(),
            'activo'           => true,
        ];
    }
}