@extends('layouts.app')

@section('title', 'Períodos — ' . $cliente->nombre_display)

@section('header')
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('clientes.show', $cliente) }}" class="text-gray-400 hover:text-gray-600">
                ← {{ $cliente->nombre_display }}
            </a>
            <h1 class="text-xl font-semibold text-gray-900">Períodos de facturación</h1>
        </div>
        <a href="{{ route('clientes.periodos.create', $cliente) }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
            + Nuevo período
        </a>
    </div>
@endsection

@section('content')
    @if($periodos->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-400 text-sm">No hay períodos cargados todavía.</p>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Productos</th>
                        <th class="relative px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($periodos as $periodo)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $periodo->nombre_display }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $periodo->status->badgeClass() }}">
                                    {{ $periodo->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $periodo->consumos_count }} producto(s)
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                                <a href="{{ route('clientes.periodos.show', [$cliente, $periodo]) }}"
                                    class="text-gray-500 hover:text-gray-700">Ver</a>
                                <a href="{{ route('clientes.periodos.factura', [$cliente, $periodo]) }}"
                                    class="text-indigo-600 hover:text-indigo-900">Ver factura</a>
                                <form method="POST"
                                        action="{{ route('clientes.periodos.destroy', [$cliente, $periodo]) }}"
                                        class="inline"
                                        onsubmit="return confirm('¿Eliminar este período?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 cursor-pointer">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection