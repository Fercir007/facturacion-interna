@extends('layouts.app')

@section('title', $cliente->nombre_display)

@section('header')
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('clientes.index') }}" class="text-gray-400 hover:text-gray-600">← Clientes</a>
            <h1 class="text-xl font-semibold text-gray-900">{{ $cliente->nombre_display }}</h1>
            @if($cliente->tipo_cliente)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cliente->tipo_cliente->badgeClass() }}">
                    {{ $cliente->tipo_cliente->label() }}
                </span>
            @endif
        </div>
        <a href="{{ route('clientes.edit', $cliente) }}"
           class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700">
            Editar
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl space-y-6">

        {{-- Datos principales --}}
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Datos del cliente</h2>
            </div>
            <dl class="divide-y divide-gray-100">
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">CUIT</dt>
                    <dd class="text-sm text-gray-900 col-span-2 font-mono">{{ $cliente->cuit }}</dd>
                </div>
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Razón social</dt>
                    <dd class="text-sm text-gray-900 col-span-2">{{ $cliente->razon_social ?? '—' }}</dd>
                </div>
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Nombre comercial</dt>
                    <dd class="text-sm text-gray-900 col-span-2">{{ $cliente->nombre_comercial ?? '—' }}</dd>
                </div>
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Referente</dt>
                    <dd class="text-sm text-gray-900 col-span-2">{{ $cliente->referente ?? '—' }}</dd>
                </div>
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900 col-span-2">
                        <a href="mailto:{{ $cliente->email }}" class="text-indigo-600 hover:underline">{{ $cliente->email }}</a>
                    </dd>
                </div>
                @if($cliente->notes)
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Notas</dt>
                    <dd class="text-sm text-gray-900 col-span-2 whitespace-pre-line">{{ $cliente->notes }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Contratos --}}
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Contratos</h2>
                <div class="flex items-center gap-3">
                    <a href="{{ route('clientes.contratos.index', $cliente) }}" class="text-xs text-gray-500 hover:text-gray-700">Ver todos</a>
                    <a href="{{ route('clientes.contratos.create', $cliente) }}"
                       class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-md shadow-sm hover:bg-indigo-700">
                        + Nuevo contrato
                    </a>
                </div>
            </div>
            @if($cliente->contratos->isEmpty())
                <div class="px-6 py-8 text-center">
                    <p class="text-sm text-gray-400">Todavía no hay contratos para este cliente.</p>
                    <a href="{{ route('clientes.contratos.create', $cliente) }}" class="mt-2 inline-block text-sm text-indigo-600 hover:underline">
                        Crear el primero
                    </a>
                </div>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach($cliente->contratos as $contrato)
                        <li class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50">
                            <div>
                                <a href="{{ route('clientes.contratos.show', [$cliente, $contrato]) }}"
                                   class="text-sm font-medium text-gray-900 hover:text-indigo-700">
                                    Contrato #{{ $contrato->id }}
                                </a>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $contrato->fecha_inicio?->format('d/m/Y') }}
                                    @if($contrato->fecha_fin)
                                        — {{ $contrato->fecha_fin->format('d/m/Y') }}
                                    @endif
                                    · {{ $contrato->contrato_productos_count }} {{ $contrato->contrato_productos_count === 1 ? 'línea' : 'líneas' }}
                                </div>
                            </div>
                            @if($contrato->status)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shrink-0 {{ $contrato->status->badgeClass() }}">
                                    {{ $contrato->status->label() }}
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        {{-- Períodos de facturación --}}
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Períodos de facturación</h2>
                <div class="flex items-center gap-3">
                    <a href="{{ route('clientes.periodos.index', $cliente) }}" class="text-xs text-gray-500 hover:text-gray-700">Ver todos</a>
                    <a href="{{ route('clientes.periodos.create', $cliente) }}"
                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-md shadow-sm hover:bg-indigo-700">
                        + Nuevo período
                    </a>
                </div>
            </div>
            @if($cliente->periodos->isEmpty())
                <div class="px-6 py-8 text-center">
                    <p class="text-sm text-gray-400">Todavía no hay períodos cargados.</p>
                    <a href="{{ route('clientes.periodos.create', $cliente) }}" class="mt-2 inline-block text-sm text-indigo-600 hover:underline">
                        Registrar el primero
                    </a>
                </div>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach($cliente->periodos->take(3) as $periodo)
                        <li class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50">
                            <div>
                                <a href="{{ route('clientes.periodos.show', [$cliente, $periodo]) }}"
                                class="text-sm font-medium text-gray-900 hover:text-indigo-700">
                                    {{ $periodo->nombre_display }}
                                </a>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $periodo->consumos->count() }} producto(s)
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <a href="{{ route('clientes.periodos.factura', [$cliente, $periodo]) }}"
                                class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                    Ver factura
                                </a>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $periodo->status->badgeClass() }}">
                                    {{ $periodo->status->label() }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>
@endsection