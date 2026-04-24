<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfiguracionComercialRequest;
use App\Http\Requests\UpdateConfiguracionComercialRequest;
use App\Http\Resources\ConfiguracionComercialResource;
use App\Models\Cliente;
use App\Models\ConfiguracionComercial;
use App\Services\ConfiguracionComercialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConfiguracionComercialController extends Controller
{
    public function __construct(
        private readonly ConfiguracionComercialService $configuracionComercialService
    ) {}

    public function index(Request $request, Cliente $cliente): AnonymousResourceCollection
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return ConfiguracionComercialResource::collection(
            $cliente->configuracionesComerciales()
                ->orderByDesc('version')
                ->paginate($perPage)
        );
    }

    public function store(StoreConfiguracionComercialRequest $request, Cliente $cliente): ConfiguracionComercialResource
    {
        $config = $this->configuracionComercialService->create($cliente, $request->validated());

        return new ConfiguracionComercialResource($config);
    }

    public function show(Cliente $cliente, ConfiguracionComercial $configuracion_comercial): ConfiguracionComercialResource
    {
        return new ConfiguracionComercialResource($configuracion_comercial);
    }

    public function update(
        UpdateConfiguracionComercialRequest $request,
        Cliente $cliente,
        ConfiguracionComercial $configuracion_comercial
    ): ConfiguracionComercialResource {
        $config = $this->configuracionComercialService->update(
            $configuracion_comercial,
            $request->validated()
        );

        return new ConfiguracionComercialResource($config);
    }

    public function destroy(Cliente $cliente, ConfiguracionComercial $configuracion_comercial): JsonResponse
    {
        $configuracion_comercial->delete();

        return response()->json(null, 204);
    }
}
