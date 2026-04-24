<?php

namespace App\Services;

use App\Enums\ConfiguracionComercialStatus;
use App\Models\Cliente;
use App\Models\ConfiguracionComercial;
use Illuminate\Support\Facades\DB;

class ConfiguracionComercialService
{
    public function nextVersion(Cliente $cliente): int
    {
        $max = (int) $cliente->configuracionesComerciales()->max('version');

        return $max + 1;
    }

    public function promoteToVigente(ConfiguracionComercial $config): void
    {
        DB::transaction(function () use ($config) {
            ConfiguracionComercial::query()
                ->where('cliente_id', $config->cliente_id)
                ->whereKeyNot($config->getKey())
                ->update(['status' => ConfiguracionComercialStatus::Borrador->value]);

            $config->status = ConfiguracionComercialStatus::Vigente;
            $config->save();
        });
    }

    /**
     * @param  array<string, mixed>  $data  Validated attributes (no version).
     */
    public function create(Cliente $cliente, array $data): ConfiguracionComercial
    {
        return DB::transaction(function () use ($cliente, $data) {
            $status = ConfiguracionComercialStatus::from(
                $data['status'] ?? ConfiguracionComercialStatus::Borrador->value
            );

            if ($status === ConfiguracionComercialStatus::Vigente) {
                ConfiguracionComercial::query()
                    ->where('cliente_id', $cliente->getKey())
                    ->update(['status' => ConfiguracionComercialStatus::Borrador->value]);
            }

            $config = new ConfiguracionComercial($data);
            $config->cliente()->associate($cliente);
            $config->version = $this->nextVersion($cliente);
            $config->status = $status;
            $config->save();

            return $config->fresh();
        });
    }

    /**
     * @param  array<string, mixed>  $data  Validated attributes (no version).
     */
    public function update(ConfiguracionComercial $config, array $data): ConfiguracionComercial
    {
        return DB::transaction(function () use ($config, $data) {
            $config->fill($data);

            $wantsVigente = $config->status === ConfiguracionComercialStatus::Vigente;

            if ($wantsVigente) {
                ConfiguracionComercial::query()
                    ->where('cliente_id', $config->cliente_id)
                    ->whereKeyNot($config->getKey())
                    ->update(['status' => ConfiguracionComercialStatus::Borrador->value]);

                $config->status = ConfiguracionComercialStatus::Vigente;
            }

            $config->save();

            return $config->fresh();
        });
    }
}
