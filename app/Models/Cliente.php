<?php

namespace App\Models;

use App\Enums\TipoCliente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tipo_cliente',
        'razon_social',
        'nombre_comercial',
        'cuit',
        'referente',
        'email',
        'telefono',
        'notes',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'tipo_cliente' => TipoCliente::class,
            'activo'       => 'boolean',
        ];
    }

    public function getNombreDisplayAttribute(): string
    {
        return $this->nombre_comercial ?? $this->razon_social ?? $this->cuit;
    }

    public function configuracionesComerciales(): HasMany
    {
        return $this->hasMany(ConfiguracionComercial::class);
    }
}