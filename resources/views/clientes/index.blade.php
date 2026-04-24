@extends('layouts.app')

@section('title', 'Clientes')

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-900">Clientes</h1>
        <a href="{{ route('clientes.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-sm font-medium
                  rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            + Nuevo cliente
        </a>
    </div>
@endsection

@section('content')
    @if($clientes->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-400 text-sm">No hay clientes todavía.</p>
            <a href="{{ route('clientes.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:underline">
                Crear el primero
            </a>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CUIT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($clientes as $cliente)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $cliente->nombre_comercial ?? $cliente->razon_social ?? '—' }}
                                </div>
                                @if($cliente->razon_social && $cliente->nombre_comercial)
                                    <div class="text-xs text-gray-400">{{ $cliente->razon_social }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $cliente->cuit }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $cliente->referente ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if($cliente->tipo_cliente)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cliente->tipo_cliente->badgeClass() }}">
                                        {{ $cliente->tipo_cliente->label() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $cliente->email }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                                <a href="{{ route('clientes.show', $cliente) }}" class="text-gray-500 hover:text-gray-700">Ver</a>
                                <a href="{{ route('clientes.edit', $cliente) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                <form method="POST" action="{{ route('clientes.destroy', $cliente) }}"
                                      class="inline"
                                      onsubmit="return confirm('¿Seguro que querés eliminar este cliente?')">
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

        {{-- Paginación --}}
        @if($clientes->hasPages())
            <div class="mt-4">{{ $clientes->links() }}</div>
        @endif
    @endif
@endsection
