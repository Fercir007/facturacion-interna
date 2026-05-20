<?php

namespace App\Enums;

enum Currency: string
{
    case ARS = 'ARS';
    case USD = 'USD';

    public function label(): string
    {
        return match ($this) {
            self::ARS => 'ARS',
            self::USD => 'USD',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ARS => 'bg-blue-50 text-blue-800',
            self::USD => 'bg-indigo-50 text-indigo-800',
        };
    }
}
