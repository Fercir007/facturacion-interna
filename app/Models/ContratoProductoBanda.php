<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContratoProductoBanda extends Model
{
    protected $table = 'contrato_producto_bandas';

    protected $fillable = [
        'contrato_producto_id',
        'numero_banda',
        'unidades_desde',
        'unidades_hasta',
        'costo_fijo',
        'precio_excedente_por_unidad',
        'es_banda_activa',
    ];

    protected function casts(): array
    {
        return [
            'numero_banda'                => 'integer',
            'unidades_desde'              => 'integer',
            'unidades_hasta'              => 'integer',
            'costo_fijo'                  => 'decimal:2',
            'precio_excedente_por_unidad' => 'decimal:4',
            'es_banda_activa'             => 'boolean',
        ];
    }

    public function contratoProducto(): BelongsTo
    {
        return $this->belongsTo(ContratoProducto::class);
    }

    public function scopeActiva(Builder $query): Builder
    {
        return $query->where('es_banda_activa', true);
    }

    protected function topePorUnidad(): Attribute
    {
        return Attribute::get(function (): ?float {
            if ($this->unidades_hasta === null || $this->unidades_hasta <= 0) {
                return null;
            }

            return round((float) $this->costo_fijo / $this->unidades_hasta, 2);
        });
    }
}
