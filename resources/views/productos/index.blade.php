@extends('layouts.app')

@section('title', 'Productos')

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-900">Productos</h1>
        <a href="{{ route('productos.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-sm font-medium
                  rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            + Nuevo producto
        </a>
    </div>
@endsection

@section('content')
    @if($productos->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-400 text-sm">No hay productos en el catálogo.</p>
            <a href="{{ route('productos.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:underline">
                Crear el primero
            </a>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pricing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($productos as $producto)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $producto->nombre }}</div>
                                @if($producto->descripcion)
                                    <div class="text-xs text-gray-400 line-clamp-1">{{ $producto->descripcion }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($producto->tipo_pricing)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $producto->tipo_pricing->badgeClass() }}">
                                        {{ $producto->tipo_pricing->label() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($producto->activo)
                                    <span class="text-green-700">Activo</span>
                                @else
                                    <span class="text-gray-400">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                                <a href="{{ route('productos.show', $producto) }}" class="text-gray-500 hover:text-gray-700">Ver</a>
                                <a href="{{ route('productos.edit', $producto) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                <form method="POST" action="{{ route('productos.destroy', $producto) }}"
                                      class="inline"
                                      onsubmit="return confirm('¿Eliminar este producto del catálogo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 cursor-pointer">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($productos->hasPages())
            <div class="mt-4">{{ $productos->links() }}</div>
        @endif
    @endif
@endsection
