<?php

namespace App\Models;

use App\Enums\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ContratoProducto extends Model
{
    protected $table = 'contrato_productos';

    protected $fillable = [
        'contrato_id',
        'producto_id',
        'currency',
        'setup_monto',
        'setup_cuotas',
        'mrr',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'currency'     => Currency::class,
            'setup_monto'  => 'decimal:2',
            'mrr'          => 'decimal:2',
            'setup_cuotas' => 'integer',
        ];
    }

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function bandas(): HasMany
    {
        return $this->hasMany(ContratoProductoBanda::class)->orderBy('numero_banda');
    }

    public function bandaActiva(): HasOne
    {
        return $this->hasOne(ContratoProductoBanda::class)->where('es_banda_activa', true);
    }
}
