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

        {{-- Placeholder para configuración comercial (próximo paso) --}}
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Configuración comercial</h2>
            </div>
            <div class="px-6 py-8 text-center">
                <p class="text-sm text-gray-400">Próximamente: contratos y términos comerciales.</p>
            </div>
        </div>

    </div>
@endsection