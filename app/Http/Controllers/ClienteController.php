<?php

namespace App\Http\Controllers;

use App\Enums\TipoCliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('referentePrincipal')
            ->orderBy('razon_social')
            ->paginate(20);

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $tiposCliente = TipoCliente::cases();
        return view('clientes.create', compact('tiposCliente'));
    }

    public function store(StoreClienteRequest $request)
    {
        $data       = $request->validated();
        $referentes = $data['referentes'] ?? [];
        unset($data['referentes']);

        $cliente = Cliente::create($data);

        foreach ($referentes as $index => $ref) {
            if (empty($ref['nombre'])) continue;
            $cliente->referentes()->create([
                'nombre'       => $ref['nombre'],
                'email'        => $ref['email'] ?? null,
                'telefono'     => $ref['telefono'] ?? null,
                'es_principal' => $index === 0,
            ]);
        }

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load([
            'referentes',
            'contratos' => function ($q) {
                $q->withCount('contratoProductos')->orderByDesc('fecha_inicio');
            },
            'periodos' => function ($q) {
                $q->withCount('consumos')->orderByDesc('anio')->orderByDesc('mes');
            },
        ]);

        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $cliente->load('referentes');
        $tiposCliente = TipoCliente::cases();
        return view('clientes.edit', compact('cliente', 'tiposCliente'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $data       = $request->validated();
        $referentes = $data['referentes'] ?? [];
        unset($data['referentes']);

        DB::transaction(function () use ($cliente, $data, $referentes) {
            $cliente->update($data);

            $cliente->referentes()->delete();

            foreach ($referentes as $index => $ref) {
                if (empty($ref['nombre'])) continue;
                $cliente->referentes()->create([
                    'nombre'       => $ref['nombre'],
                    'email'        => $ref['email'] ?? null,
                    'telefono'     => $ref['telefono'] ?? null,
                    'es_principal' => $index === 0,
                ]);
            }
        });

        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado.');
    }
}
