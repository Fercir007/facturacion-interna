@extends('layouts.app')

@section('title', 'Editar producto')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('productos.show', $producto) }}" class="text-gray-400 hover:text-gray-600">← {{ $producto->nombre }}</a>
        <h1 class="text-xl font-semibold text-gray-900">Editar producto</h1>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-6 py-5">
                <form method="POST" action="{{ route('productos.update', $producto) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    @include('productos._form')

                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('productos.show', $producto) }}"
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
    </div>
@endsection
