@extends('layouts.app')

@section('title', 'Factura — ' . $periodo->nombre_display)

@section('header')
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('clientes.show', $cliente) }}" class="text-gray-400 hover:text-gray-600">
                ← {{ $cliente->nombre_display }}
            </a>
            <h1 class="text-xl font-semibold text-gray-900">
                Factura — {{ $periodo->nombre_display }}
            </h1>
        </div>
        {{-- Botón PDF — por ahora no hace nada --}}
        <button type="button"
                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-500 text-sm font-medium rounded-md cursor-not-allowed"
                disabled>
            ↓ Descargar PDF
        </button>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl space-y-6">

        {{-- Encabezado de la factura --}}
        <div class="bg-white shadow sm:rounded-lg px-6 py-5">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Cliente</p>
                    <p class="font-medium text-gray-900">{{ $cliente->nombre_display }}</p>
                    <p class="text-gray-500">{{ $cliente->cuit }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500">Período</p>
                    <p class="font-medium text-gray-900">{{ $periodo->nombre_display }}</p>
                    <p class="text-gray-500">Generado {{ now()->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Detalle por producto --}}
        @foreach($calculo['lineas'] as $linea)
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">{{ $linea['producto'] }}</h2>
                    <span class="text-xs text-gray-500">{{ $linea['currency'] }}</span>
                </div>

                @if($linea['error'])
                    <div class="px-6 py-4">
                        <p class="text-sm text-red-500">⚠ {{ $linea['error'] }}</p>
                    </div>
                @else
                    <dl class="divide-y divide-gray-100">
                        <div class="px-6 py-3 grid grid-cols-3 gap-4">
                            <dt class="text-sm text-gray-500">Banda activa</dt>
                            <dd class="text-sm text-gray-900 col-span-2">
                                Banda {{ $linea['banda'] }}
                                @if($linea['techo'])
                                    (hasta {{ number_format($linea['techo'], 0, ',', '.') }} unidades)
                                @else
                                    (sin techo)
                                @endif
                            </dd>
                        </div>
                        <div class="px-6 py-3 grid grid-cols-3 gap-4">
                            <dt class="text-sm text-gray-500">Consumo del mes</dt>
                            <dd class="text-sm text-gray-900 col-span-2">
                                {{ number_format($linea['unidades'], 0, ',', '.') }} unidades
                            </dd>
                        </div>
                        <div class="px-6 py-3 grid grid-cols-3 gap-4">
                            <dt class="text-sm text-gray-500">Costo fijo de banda</dt>
                            <dd class="text-sm text-gray-900 col-span-2">
                                {{ $linea['currency'] }} {{ number_format($linea['costo_fijo'], 2, ',', '.') }}
                            </dd>
                        </div>

                        @if($linea['unidades_excedentes'] > 0)
                            <div class="px-6 py-3 grid grid-cols-3 gap-4">
                                <dt class="text-sm text-gray-500">Unidades excedentes</dt>
                                <dd class="text-sm text-gray-900 col-span-2">
                                    {{ number_format($linea['unidades_excedentes'], 0, ',', '.') }}
                                    × {{ $linea['currency'] }} {{ number_format($linea['precio_excedente'], 4, ',', '.') }}
                                    = {{ $linea['currency'] }} {{ number_format($linea['costo_excedente'], 2, ',', '.') }}
                                </dd>
                            </div>
                        @endif

                        <div class="px-6 py-3 grid grid-cols-3 gap-4">
                            <dt class="text-sm text-gray-500">Subtotal calculado</dt>
                            <dd class="text-sm text-gray-900 col-span-2">
                                {{ $linea['currency'] }} {{ number_format($linea['subtotal'], 2, ',', '.') }}
                            </dd>
                        </div>

                        <div class="px-6 py-3 grid grid-cols-3 gap-4">
                            <dt class="text-sm text-gray-500">MRR (mínimo mensual)</dt>
                            <dd class="text-sm text-gray-900 col-span-2">
                                {{ $linea['currency'] }} {{ number_format($linea['mrr'], 2, ',', '.') }}
                            </dd>
                        </div>

                        <div class="px-6 py-4 grid grid-cols-3 gap-4 bg-gray-50">
                            <dt class="text-sm font-semibold text-gray-700">Total del producto</dt>
                            <dd class="text-sm font-semibold text-gray-900 col-span-2">
                                {{ $linea['currency'] }} {{ number_format($linea['total'], 2, ',', '.') }}
                                @if($linea['aplica_mrr'])
                                    <span class="ml-2 text-xs font-normal text-indigo-600">(se aplica MRR mínimo)</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                @endif
            </div>
        @endforeach

        {{-- Total general --}}
        <div class="bg-indigo-600 shadow sm:rounded-lg px-6 py-5">
            <div class="flex items-center justify-between">
                <span class="text-white font-semibold">Total a facturar</span>
                <span class="text-white text-xl font-bold">
                    {{ number_format($calculo['total_general'], 2, ',', '.') }}
                </span>
            </div>
        </div>

    </div>
@endsection