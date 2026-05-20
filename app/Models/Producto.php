<?php

namespace App\Models;

use App\Enums\TipoPricing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_pricing',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'tipo_pricing' => TipoPricing::class,
            'activo'       => 'boolean',
        ];
    }

    public function contratoProductos(): HasMany
    {
        return $this->hasMany(ContratoProducto::class);
    }
}
