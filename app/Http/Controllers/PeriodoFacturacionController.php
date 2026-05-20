<?php

namespace App\Http\Controllers;

use App\Enums\ContratoStatus;
use App\Enums\PeriodoStatus;
use App\Models\Cliente;
use App\Models\ConsumoMensual;
use App\Models\ContratoProducto;
use App\Models\PeriodoFacturacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodoFacturacionController extends Controller
{
    public function index(Cliente $cliente)
    {
        $periodos = $cliente->periodosFacturacion()
            ->withCount('consumos')
            ->orderByDesc('anio')
            ->orderByDesc('mes')
            ->get();

        return view('periodos.index', compact('cliente', 'periodos'));
    }

    public function create(Cliente $cliente)
    {
        // Contrato vigente con sus productos y bandas activas
        $contrato = $cliente->contratos()
            ->where('status', \App\Enums\ContratoStatus::Vigente)
            ->with('contratoProductos.producto', 'contratoProductos.bandaActiva')
            ->first();

        if (!$contrato) {
            return redirect()->route('clientes.show', $cliente)
                ->with('error', 'El cliente no tiene un contrato vigente.');
        }

        $anioActual = now()->year;
        $mesActual  = now()->month;

        return view('periodos.create', compact('cliente', 'contrato', 'anioActual', 'mesActual'));
    }

    public function store(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'anio'                              => ['required', 'integer', 'min:2020', 'max:2099'],
            'mes'                               => ['required', 'integer', 'min:1', 'max:12'],
            'notas'                             => ['nullable', 'string'],
            'consumos'                          => ['required', 'array', 'min:1'],
            'consumos.*.contrato_producto_id'   => [
                'required',
                'integer',
                'exists:contrato_productos,id',
                function ($attribute, $value, $fail) use ($cliente) {
                    $valid = ContratoProducto::where('id', $value)
                        ->whereHas('contrato', fn ($q) => $q
                            ->where('cliente_id', $cliente->id)
                            ->where('status', ContratoStatus::Vigente))
                        ->exists();
                    if (!$valid) {
                        $fail('El producto no pertenece a un contrato vigente del cliente.');
                    }
                },
            ],
            'consumos.*.cantidad_unidades'      => ['required', 'numeric', 'min:0'],
        ]);

        // Verificar que no exista ya ese período para este cliente
        $existe = PeriodoFacturacion::where('cliente_id', $cliente->id)
            ->where('anio', $data['anio'])
            ->where('mes', $data['mes'])
            ->exists();

        if ($existe) {
            return back()->withErrors(['mes' => 'Ya existe un período cargado para ese mes y año.'])->withInput();
        }

        $periodo = DB::transaction(function () use ($cliente, $data) {
            $periodo = PeriodoFacturacion::create([
                'cliente_id' => $cliente->id,
                'anio'       => $data['anio'],
                'mes'        => $data['mes'],
                'status'     => PeriodoStatus::Abierto,
                'notas'      => $data['notas'] ?? null,
            ]);

            foreach ($data['consumos'] as $consumo) {
                ConsumoMensual::create([
                    'periodo_facturacion_id' => $periodo->id,
                    'contrato_producto_id'   => $consumo['contrato_producto_id'],
                    'cantidad_unidades'      => $consumo['cantidad_unidades'],
                ]);
            }

            return $periodo;
        });

        return redirect()->route('clientes.periodos.show', [$cliente, $periodo])
            ->with('success', 'Consumo registrado correctamente.');
    }

    public function show(Cliente $cliente, PeriodoFacturacion $periodo)
    {
        $periodo->load('consumos.contratoProducto.producto', 'consumos.contratoProducto.bandaActiva');

        return view('periodos.show', compact('cliente', 'periodo'));
    }

    public function destroy(Cliente $cliente, PeriodoFacturacion $periodo)
    {
        $periodo->delete();

        return redirect()->route('clientes.periodos.index', $cliente)
            ->with('success', 'Período eliminado.');
    }
}