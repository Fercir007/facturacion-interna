@extends('layouts.app')

@section('title', 'Nuevo cliente')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('clientes.index') }}" class="text-gray-400 hover:text-gray-600">
            ← Clientes
        </a>
        <h1 class="text-xl font-semibold text-gray-900">Nuevo cliente</h1>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-6 py-5">
                <form method="POST" action="{{ route('clientes.store') }}" class="space-y-6">
                    @csrf
                    @include('clientes._form')

                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('clientes.index') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 cursor-pointer">
                            Crear cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection