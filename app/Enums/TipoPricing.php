<?php

namespace App\Enums;

enum TipoPricing: string
{
    case PorcentajeCapital = 'porcentaje_capital';
    case PorUnidad = 'por_unidad';
    case FeeFijo = 'fee_fijo';

    public function label(): string
    {
        return match ($this) {
            self::PorcentajeCapital => '% sobre capital',
            self::PorUnidad => 'Por unidad',
            self::FeeFijo => 'Fee fijo mensual',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PorcentajeCapital => 'bg-violet-100 text-violet-800',
            self::PorUnidad => 'bg-amber-100 text-amber-800',
            self::FeeFijo => 'bg-emerald-100 text-emerald-800',
        };
    }
}
