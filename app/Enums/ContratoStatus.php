<?php

namespace App\Enums;

enum ContratoStatus: string
{
    case Borrador = 'borrador';
    case Vigente = 'vigente';
    case Vencido = 'vencido';

    public function label(): string
    {
        return match ($this) {
            self::Borrador => 'Borrador',
            self::Vigente => 'Vigente',
            self::Vencido => 'Vencido',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Borrador => 'bg-gray-100 text-gray-800',
            self::Vigente => 'bg-green-100 text-green-800',
            self::Vencido => 'bg-orange-100 text-orange-800',
        };
    }
}
