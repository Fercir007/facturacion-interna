<?php

namespace App\Http\Controllers;

use App\Enums\TipoCliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('razon_social')->paginate(20);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $tiposCliente = TipoCliente::cases();
        return view('clientes.create', compact('tiposCliente'));
    }

    public function store(StoreClienteRequest $request)
    {
        Cliente::create($request->validated());
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $tiposCliente = TipoCliente::cases();
        return view('clientes.edit', compact('cliente', 'tiposCliente'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());
        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete(); // soft delete gracias al trait
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado.');
    }
}
