<?php

namespace App\Enums;

enum TipoCliente: string
{
    case Comercio = 'comercio';

    public function label(): string
    {
        return match($this) {
            TipoCliente::Comercio => 'Comercio',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            TipoCliente::Comercio => 'bg-blue-100 text-blue-800',
        };
    }
}