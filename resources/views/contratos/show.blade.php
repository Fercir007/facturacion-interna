@extends('layouts.app')

@section('title', 'Contrato #' . $contrato->id)

@section('header')
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('clientes.show', $cliente) }}" class="text-gray-400 hover:text-gray-600">← {{ $cliente->nombre_display }}</a>
            <h1 class="text-xl font-semibold text-gray-900">Contrato #{{ $contrato->id }}</h1>
            @if($contrato->status)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contrato->status->badgeClass() }}">
                    {{ $contrato->status->label() }}
                </span>
            @endif
        </div>
        <a href="{{ route('clientes.contratos.edit', [$cliente, $contrato]) }}"
           class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700">
            Editar
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-5xl space-y-6">

        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Condiciones generales</h2>
            </div>
            <dl class="divide-y divide-gray-100">
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Inicio</dt>
                    <dd class="text-sm text-gray-900 col-span-2">{{ $contrato->fecha_inicio?->format('d/m/Y') }}</dd>
                </div>
                <div class="px-6 py-4 grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Fin</dt>
                    <dd class="text-sm text-gray-900 col-span-2">{{ $contrato->fecha_fin?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                @if($contrato->notas)
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Notas</dt>
                        <dd class="text-sm text-gray-900 col-span-2 whitespace-pre-line">{{ $contrato->notas }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Productos y condiciones</h2>
            </div>

            @if($contrato->contratoProductos->isEmpty())
                <div class="px-6 py-10 text-center text-sm text-gray-400">
                    No hay productos cargados. Editá el contrato para agregar líneas.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($contrato->contratoProductos as $cp)
                        <div class="px-6 py-5 space-y-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $cp->producto?->nombre ?? '—' }}</div>
                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-600">
                                        @if($cp->currency)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $cp->currency->badgeClass() }}">
                                                {{ $cp->currency->label() }}
                                            </span>
                                        @endif
                                        <span>Setup: <span class="font-mono">{{ $cp->setup_monto }}</span> en {{ $cp->setup_cuotas }} cuota(s)</span>
                                        <span>· MRR: <span class="font-mono">{{ $cp->mrr }}</span></span>
                                    </div>
                                    @if($cp->notas)
                                        <p class="mt-2 text-xs text-gray-500">{{ $cp->notas }}</p>
                                    @endif
                                </div>
                                @if($cp->producto?->tipo_pricing)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cp->producto->tipo_pricing->badgeClass() }}">
                                        {{ $cp->producto->tipo_pricing->label() }}
                                    </span>
                                @endif
                            </div>

                            @if($cp->bandas->isEmpty())
                                <p class="text-xs text-amber-700">Sin bandas definidas.</p>
                            @else
                                <div class="overflow-x-auto rounded-md border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200 text-xs">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left font-medium text-gray-500">Banda</th>
                                                <th class="px-3 py-2 text-left font-medium text-gray-500">Desde</th>
                                                <th class="px-3 py-2 text-left font-medium text-gray-500">Hasta</th>
                                                <th class="px-3 py-2 text-right font-medium text-gray-500">Costo fijo</th>
                                                <th class="px-3 py-2 text-right font-medium text-gray-500">Excedente/u</th>
                                                <th class="px-3 py-2 text-right font-medium text-gray-500">Tope/u</th>
                                                <th class="px-3 py-2 text-center font-medium text-gray-500">Activa</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 bg-white">
                                            @foreach($cp->bandas as $b)
                                                <tr class="{{ $b->es_banda_activa ? 'bg-indigo-50/60' : '' }}">
                                                    <td class="px-3 py-2 font-mono text-gray-900">{{ $b->numero_banda }}</td>
                                                    <td class="px-3 py-2 font-mono text-gray-700">{{ $b->unidades_desde }}</td>
                                                    <td class="px-3 py-2 font-mono text-gray-700">{{ $b->unidades_hasta ?? '—' }}</td>
                                                    <td class="px-3 py-2 text-right font-mono text-gray-700">{{ $b->costo_fijo }}</td>
                                                    <td class="px-3 py-2 text-right font-mono text-gray-700">{{ $b->precio_excedente_por_unidad }}</td>
                                                    <td class="px-3 py-2 text-right font-mono text-gray-600">
                                                        @if($b->tope_por_unidad !== null)
                                                            {{ number_format($b->tope_por_unidad, 2, ',', '.') }}
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                    <td class="px-3 py-2 text-center">
                                                        @if($b->es_banda_activa)
                                                            <span class="text-indigo-700 font-medium">Sí</span>
                                                        @else
                                                            <span class="text-gray-400">No</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
