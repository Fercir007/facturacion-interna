@extends('layouts.app')

@section('title', 'Nuevo contrato')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('clientes.show', $cliente) }}" class="text-gray-400 hover:text-gray-600">← {{ $cliente->nombre_display }}</a>
        <h1 class="text-xl font-semibold text-gray-900">Nuevo contrato</h1>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-6 py-5 space-y-8">
                <form method="POST" action="{{ route('clientes.contratos.store', $cliente) }}" class="space-y-8">
                    @csrf

                    @include('contratos._form', ['contrato' => null])

                    @include('contratos._lineas')

                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('clientes.show', $cliente) }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 cursor-pointer">
                            Crear contrato
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
