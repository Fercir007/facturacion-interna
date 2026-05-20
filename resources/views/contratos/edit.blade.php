@extends('layouts.app')

@section('title', 'Editar contrato')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('clientes.contratos.show', [$cliente, $contrato]) }}" class="text-gray-400 hover:text-gray-600">← Contrato #{{ $contrato->id }}</a>
        <h1 class="text-xl font-semibold text-gray-900">Editar contrato</h1>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl space-y-4">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-6 py-5 space-y-8">
                <form method="POST" action="{{ route('clientes.contratos.update', [$cliente, $contrato]) }}" class="space-y-8">
                    @csrf
                    @method('PUT')

                    @include('contratos._form', ['contrato' => $contrato])

                    @include('contratos._lineas')

                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('clientes.contratos.show', [$cliente, $contrato]) }}"
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

        <div class="bg-white shadow sm:rounded-lg px-6 py-4 flex justify-end">
            <form method="POST" action="{{ route('clientes.contratos.destroy', [$cliente, $contrato]) }}"
                  onsubmit="return confirm('¿Eliminar este contrato?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-600 hover:text-red-800 cursor-pointer">
                    Eliminar contrato
                </button>
            </form>
        </div>
    </div>
@endsection
