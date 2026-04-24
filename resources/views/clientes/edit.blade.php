@extends('layouts.app')

@section('title', 'Editar cliente')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('clientes.show', $cliente) }}" class="text-gray-400 hover:text-gray-600">
            ← {{ $cliente->nombre_display }}
        </a>
        <h1 class="text-xl font-semibold text-gray-900">Editar cliente</h1>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl space-y-4">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-6 py-5">
                <form method="POST" action="{{ route('clientes.update', $cliente) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    @include('clientes._form')

                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('clientes.show', $cliente) }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 cursor-pointer">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete FUERA del form de update --}}
        <div class="bg-white shadow sm:rounded-lg px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-700">Eliminar cliente</p>
                    <p class="text-xs text-gray-400">Esta acción no se puede deshacer.</p>
                </div>
                <form method="POST" action="{{ route('clientes.destroy', $cliente) }}"
                      onsubmit="return confirm('¿Seguro que querés eliminar a {{ addslashes($cliente->nombre_display) }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 cursor-pointer">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection
