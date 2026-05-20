@extends('layouts.app')

@section('title', $periodo->nombre_display . ' — ' . $cliente->nombre_display)

@section('header')
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('clientes.periodos.index', $cliente) }}" class="text-gray-400 hover:text-gray-600">
                ← Períodos
            </a>
            <h1 class="text-xl font-semibold text-gray-900">{{ $periodo->nombre_display }}</h1>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $periodo->status->badgeClass() }}">
                {{ $periodo->status->label() }}
            </span>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl space-y-6">
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Consumo registrado</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Banda activa</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Unidades</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($periodo->consumos as $consumo)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $consumo->contratoProducto->producto->nombre }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($consumo->contratoProducto->bandaActiva)
                                    Banda {{ $consumo->contratoProducto->bandaActiva->numero_banda }}
                                    (hasta {{ $consumo->contratoProducto->bandaActiva->unidades_hasta ?? '∞' }})
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right font-mono">
                                {{ number_format($consumo->cantidad_unidades, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($periodo->notas)
            <div class="bg-white shadow sm:rounded-lg px-6 py-4">
                <p class="text-sm font-medium text-gray-500 mb-1">Notas</p>
                <p class="text-sm text-gray-900">{{ $periodo->notas }}</p>
            </div>
        @endif
    </div>
@endsection