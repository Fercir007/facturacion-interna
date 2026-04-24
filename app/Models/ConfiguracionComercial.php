<?php

namespace App\Models;

use App\Enums\ConfiguracionComercialStatus;
use App\Enums\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfiguracionComercial extends Model
{
    protected $table = 'configuraciones_comerciales';

    protected $fillable = [
        'status',
        'effective_from',
        'effective_to',
        'currency',
        'terms',
    ];

    protected function casts(): array
    {
        return [
            'status' => ConfiguracionComercialStatus::class,
            'effective_from' => 'date',
            'effective_to' => 'date',
            'currency' => Currency::class,
            'terms' => 'array',
            'version' => 'integer',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
