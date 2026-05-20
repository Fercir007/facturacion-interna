@extends('layouts.app')

@section('title', 'Contratos — ' . $cliente->nombre_display)

@section('header')
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('clientes.show', $cliente) }}" class="text-gray-400 hover:text-gray-600">← {{ $cliente->nombre_display }}</a>
            <h1 class="text-xl font-semibold text-gray-900">Contratos</h1>
        </div>
        <a href="{{ route('clientes.contratos.create', $cliente) }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700">
            + Nuevo contrato
        </a>
    </div>
@endsection

@section('content')
    @if($contratos->isEmpty())
        <div class="text-center py-16 bg-white shadow sm:rounded-lg">
            <p class="text-gray-400 text-sm">Este cliente no tiene contratos cargados.</p>
            <a href="{{ route('clientes.contratos.create', $cliente) }}" class="mt-3 inline-block text-sm text-indigo-600 hover:underline">
                Crear contrato
            </a>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Líneas</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($contratos as $contrato)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-mono text-gray-600">#{{ $contrato->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $contrato->fecha_inicio?->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $contrato->fecha_fin?->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if($contrato->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contrato->status->badgeClass() }}">
                                        {{ $contrato->status->label() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $contrato->contrato_productos_count }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                                <a href="{{ route('clientes.contratos.show', [$cliente, $contrato]) }}" class="text-gray-500 hover:text-gray-700">Ver</a>
                                <a href="{{ route('clientes.contratos.edit', [$cliente, $contrato]) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($contratos->hasPages())
            <div class="mt-4">{{ $contratos->links() }}</div>
        @endif
    @endif
@endsection
