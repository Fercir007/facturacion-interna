<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Cliente */
class ClienteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                       => $this->id,
            'tipo_cliente'             => $this->tipo_cliente->value,
            'razon_social'             => $this->razon_social,
            'nombre_comercial'         => $this->nombre_comercial,
            'cuit'                     => $this->cuit,
            'referente'                => $this->referente,
            'email'                    => $this->email,
            'telefono'                 => $this->telefono,
            'notes'                    => $this->notes,
            'activo'                   => $this->activo,
            'created_at'               => $this->created_at?->toIso8601String(),
            'updated_at'               => $this->updated_at?->toIso8601String(),
            'deleted_at'               => $this->whenNotNull($this->deleted_at?->toIso8601String()),
            'configuraciones_comerciales' => ConfiguracionComercialResource::collection(
                $this->whenLoaded('configuracionesComerciales')
            ),
        ];
    }
}