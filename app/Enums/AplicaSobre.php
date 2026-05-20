<?php

namespace App\Enums;

enum AplicaSobre: string
{
    case CapitalColocado = 'capital_colocado';
    case CantidadOperaciones = 'cantidad_operaciones';

    public function label(): string
    {
        return match ($this) {
            self::CapitalColocado => 'Capital colocado',
            self::CantidadOperaciones => 'Cantidad de operaciones',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::CapitalColocado => 'bg-slate-100 text-slate-800',
            self::CantidadOperaciones => 'bg-cyan-100 text-cyan-800',
        };
    }
}
