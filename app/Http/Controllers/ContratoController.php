<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContratoRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Producto;
use App\Services\ContratoService;
use Illuminate\Support\Facades\DB;

class ContratoController extends Controller
{
    public function __construct(
        private readonly ContratoService $contratoService
    ) {}

    public function index(Cliente $cliente)
    {
        $contratos = $cliente->contratos()
            ->withCount('contratoProductos')
            ->orderByDesc('fecha_inicio')
            ->paginate(15);

        return view('contratos.index', compact('cliente', 'contratos'));
    }

    public function create(Cliente $cliente)
    {
        $productos = Producto::query()->where('activo', true)->orderBy('nombre')->get();
        $productosJson = $this->productosJson($productos);
        $lineasInitial = old('lineas', [$this->emptyLinea()]);

        return view('contratos.create', compact('cliente', 'productos', 'productosJson', 'lineasInitial'));
    }

    public function store(StoreContratoRequest $request, Cliente $cliente)
    {
        $data   = $request->validated();
        $lineas = $data['lineas'] ?? [];
        unset($data['lineas']);

        $contrato = DB::transaction(function () use ($cliente, $data, $lineas) {
            $contrato = $cliente->contratos()->create($data);
            $this->contratoService->expirarOtrosSiVigente($contrato);
            $this->contratoService->sincronizarLineas($contrato, $this->mapLineasPayload($lineas));

            return $contrato;
        });

        return redirect()->route('clientes.contratos.show', [$cliente, $contrato])
            ->with('success', 'Contrato creado correctamente.');
    }

    public function show(Cliente $cliente, Contrato $contrato)
    {
        $contrato->load(['contratoProductos.producto', 'contratoProductos.bandas']);

        return view('contratos.show', compact('cliente', 'contrato'));
    }

    public function edit(Cliente $cliente, Contrato $contrato)
    {
        $contrato->load(['contratoProductos.bandas', 'contratoProductos.producto']);
        $idsEnContrato = $contrato->contratoProductos->pluck('producto_id');
        $productos = Producto::query()
            ->where(function ($q) use ($idsEnContrato) {
                $q->where('activo', true)->orWhereIn('id', $idsEnContrato);
            })
            ->orderBy('nombre')
            ->get();
        $productosJson = $this->productosJson($productos);

        $mapped = $contrato->contratoProductos->map(fn ($cp) => [
            'producto_id'  => (string) $cp->producto_id,
            'currency'     => $cp->currency->value,
            'setup_monto'  => (string) $cp->setup_monto,
            'setup_cuotas' => (int) $cp->setup_cuotas,
            'mrr'          => (string) $cp->mrr,
            'notas'        => $cp->notas ?? '',
            'bandas'       => $cp->bandas->map(fn ($b) => [
                'numero_banda'                => (int) $b->numero_banda,
                'unidades_desde'              => (int) $b->unidades_desde,
                'unidades_hasta'              => $b->unidades_hasta !== null ? (string) $b->unidades_hasta : '',
                'costo_fijo'                  => (string) $b->costo_fijo,
                'precio_excedente_por_unidad' => (string) $b->precio_excedente_por_unidad,
                'es_banda_activa'             => (bool) $b->es_banda_activa,
            ])->values()->all(),
        ])->values()->all();

        $lineasInitial = old('lineas', $mapped !== [] ? $mapped : [$this->emptyLinea()]);

        return view('contratos.edit', compact('cliente', 'contrato', 'productos', 'productosJson', 'lineasInitial'));
    }

    public function update(UpdateContratoRequest $request, Cliente $cliente, Contrato $contrato)
    {
        $data   = $request->validated();
        $lineas = $data['lineas'] ?? [];
        unset($data['lineas']);

        DB::transaction(function () use ($contrato, $data, $lineas) {
            $contrato->update($data);
            $this->contratoService->expirarOtrosSiVigente($contrato);
            $this->contratoService->sincronizarLineas($contrato, $this->mapLineasPayload($lineas));
        });

        return redirect()->route('clientes.contratos.show', [$cliente, $contrato])
            ->with('success', 'Contrato actualizado correctamente.');
    }

    public function destroy(Cliente $cliente, Contrato $contrato)
    {
        $contrato->delete();

        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Contrato eliminado.');
    }

    /**
     * @param  array<int, array<string, mixed>>  $lineas
     * @return array<int, array{contrato_producto: array<string, mixed>, bandas: array<int, array<string, mixed>>}>
     */
    private function mapLineasPayload(array $lineas): array
    {
        return collect($lineas)->map(function (array $row) {
            $bandas = collect($row['bandas'] ?? [])->map(function (array $b) {
                return [
                    'numero_banda'                => (int) ($b['numero_banda'] ?? 0),
                    'unidades_desde'              => (int) ($b['unidades_desde'] ?? 0),
                    'unidades_hasta'              => $this->nullableUnsignedInt($b['unidades_hasta'] ?? null),
                    'costo_fijo'                  => $b['costo_fijo'] ?? 0,
                    'precio_excedente_por_unidad' => $b['precio_excedente_por_unidad'] ?? 0,
                    'es_banda_activa'             => filter_var($b['es_banda_activa'] ?? false, FILTER_VALIDATE_BOOLEAN)
                        || ($b['es_banda_activa'] ?? '') === '1'
                        || ($b['es_banda_activa'] ?? '') === 1,
                ];
            })->all();

            return [
                'contrato_producto' => [
                    'producto_id'  => (int) $row['producto_id'],
                    'currency'     => $row['currency'],
                    'setup_monto'    => $row['setup_monto'],
                    'setup_cuotas'   => (int) $row['setup_cuotas'],
                    'mrr'            => $row['mrr'],
                    'notas'          => $this->nullableString($row['notas'] ?? null),
                ],
                'bandas' => $bandas,
            ];
        })->all();
    }

    private function nullableString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (string) $value;
    }

    private function nullableUnsignedInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Producto>  $productos
     */
    private function productosJson($productos)
    {
        return $productos->map(fn (Producto $p) => [
            'id'           => $p->id,
            'nombre'       => $p->nombre,
            'tipo_pricing' => $p->tipo_pricing->value,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyLinea(): array
    {
        return [
            'producto_id'  => '',
            'currency'     => 'USD',
            'setup_monto'  => '0',
            'setup_cuotas' => 1,
            'mrr'          => '0',
            'notas'        => '',
            'bandas'       => [
                [
                    'numero_banda'                => 1,
                    'unidades_desde'              => 0,
                    'unidades_hasta'              => '',
                    'costo_fijo'                  => '0',
                    'precio_excedente_por_unidad' => '0',
                    'es_banda_activa'             => true,
                ],
            ],
        ];
    }
}
