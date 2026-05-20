<?php

namespace App\Models;

use App\Enums\TipoCliente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tipo_cliente',
        'razon_social',
        'nombre_comercial',
        'cuit',
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

    public function getReferenteAttribute(): ?string
    {
        return $this->referentePrincipal?->nombre;
    }

    public function referentes(): HasMany
    {
        return $this->hasMany(Referente::class)->orderByDesc('es_principal');
    }

    public function referentePrincipal(): HasOne
    {
        return $this->hasOne(Referente::class)->where('es_principal', true);
    }

    public function configuracionesComerciales(): HasMany
    {
        return $this->hasMany(ConfiguracionComercial::class);
    }

    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class)->orderByDesc('fecha_inicio');
    }

    public function contratoProductos(): HasManyThrough
    {
        return $this->hasManyThrough(ContratoProducto::class, Contrato::class);
    }

    public function periodosFacturacion(): HasMany
    {
        return $this->hasMany(PeriodoFacturacion::class);
    }

    public function periodos(): HasMany
    {
        return $this->hasMany(PeriodoFacturacion::class);
    }
}