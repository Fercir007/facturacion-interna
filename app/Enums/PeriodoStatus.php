<?php

namespace App\Enums;

enum PeriodoStatus: string
{
    case Abierto = 'abierto';
    case Cerrado = 'cerrado';

    public function label(): string
    {
        return match($this) {
            PeriodoStatus::Abierto => 'Abierto',
            PeriodoStatus::Cerrado => 'Cerrado',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            PeriodoStatus::Abierto => 'bg-green-100 text-green-800',
            PeriodoStatus::Cerrado => 'bg-gray-100 text-gray-800',
        };
    }
}