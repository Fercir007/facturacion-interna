@extends('layouts.app')

@section('title', $producto->nombre)

@section('header')
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('productos.index') }}" class="text-gray-400 hover:text-gray-600">← Productos</a>
            <h1 class="text-xl font-semibold text-gray-900">{{ $producto->nombre }}</h1>
            @if($producto->tipo_pricing)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $producto->tipo_pricing->badgeClass() }}">
                    {{ $producto->tipo_pricing->label() }}
                </span>
            @endif
        </div>
        <a href="{{ route('productos.edit', $producto) }}"
           class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700">
            Editar
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl space-y-6">
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Detalle</h2>
            </div>
            <dl class="divide-y divide-gray-100">
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                    <dd class="text-sm text-gray-900 col-span-2">
                        {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                    </dd>
                </div>
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                    <dd class="text-sm text-gray-900 col-span-2 whitespace-pre-line">{{ $producto->descripcion ?: '—' }}</dd>
                </div>
            </dl>
        </div>
    </div>
@endsection
