<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumoMensual extends Model
{
    protected $table = 'consumos_mensuales';

    protected $fillable = [
        'periodo_facturacion_id',
        'contrato_producto_id',
        'cantidad_unidades',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'cantidad_unidades' => 'decimal:2',
        ];
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(PeriodoFacturacion::class, 'periodo_facturacion_id');
    }

    public function contratoProducto(): BelongsTo
    {
        return $this->belongsTo(ContratoProducto::class);
    }
}