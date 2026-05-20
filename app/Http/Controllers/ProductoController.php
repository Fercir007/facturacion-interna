<?php

namespace App\Http\Controllers;

use App\Enums\TipoPricing;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use Illuminate\Database\QueryException;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::query()->orderBy('nombre')->paginate(20);

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $tiposPricing = TipoPricing::cases();

        return view('productos.create', compact('tiposPricing'));
    }

    public function store(StoreProductoRequest $request)
    {
        Producto::create($request->validated());

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $tiposPricing = TipoPricing::cases();

        return view('productos.edit', compact('producto', 'tiposPricing'));
    }

    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return redirect()->route('productos.show', $producto)
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        try {
            $producto->delete();
        } catch (QueryException) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el producto porque está asociado a contratos.');
        }

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado.');
    }
}
