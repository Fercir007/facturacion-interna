<?php

namespace App\Models;

use App\Enums\PeriodoStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodoFacturacion extends Model
{
    protected $table = 'periodos_facturacion';

    protected $fillable = [
        'cliente_id',
        'anio',
        'mes',
        'status',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'anio'   => 'integer',
            'mes'    => 'integer',
            'status' => PeriodoStatus::class,
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function consumos(): HasMany
    {
        return $this->hasMany(ConsumoMensual::class);
    }

    public function getNombreDisplayAttribute(): string
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        return $meses[$this->mes] . ' ' . $this->anio;
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return $query->where('id', $value);
    }
}