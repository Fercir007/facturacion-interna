@extends('layouts.app')

@section('title', 'Nuevo período — ' . $cliente->nombre_display)

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('clientes.periodos.index', $cliente) }}" class="text-gray-400 hover:text-gray-600">
            ← Períodos
        </a>
        <h1 class="text-xl font-semibold text-gray-900">Registrar consumo</h1>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-6 py-5">
                <form method="POST" action="{{ route('clientes.periodos.store', $cliente) }}" class="space-y-6">
                    @csrf

                    {{-- Mes y año --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mes <span class="text-red-500">*</span></label>
                            <select name="mes"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @foreach(range(1, 12) as $m)
                                    @php
                                        $nombres = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
                                                   7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
                                    @endphp
                                    <option value="{{ $m }}" {{ old('mes', $mesActual) == $m ? 'selected' : '' }}>
                                        {{ $nombres[$m] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mes')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Año <span class="text-red-500">*</span></label>
                            <input type="number" name="anio"
                                   value="{{ old('anio', $anioActual) }}"
                                   min="2020" max="2099"
                                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('anio')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Consumo por producto --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Consumo por producto</label>
                        <div class="space-y-3">
                            @foreach($contrato->contratoProductos as $cp)
                                <div class="border border-gray-200 rounded-md p-4 bg-gray-50">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $cp->producto->nombre }}
                                        </span>
                                        @if($cp->bandaActiva)
                                            <span class="text-xs text-gray-500">
                                                Banda {{ $cp->bandaActiva->numero_banda }} activa
                                                (hasta {{ $cp->bandaActiva->unidades_hasta ?? '∞' }} unidades)
                                            </span>
                                        @endif
                                    </div>
                                    <input type="hidden"
                                           name="consumos[{{ $loop->index }}][contrato_producto_id]"
                                           value="{{ $cp->id }}">
                                    <div class="flex items-center gap-3">
                                        <input type="number"
                                               name="consumos[{{ $loop->index }}][cantidad_unidades]"
                                               value="{{ old('consumos.' . $loop->index . '.cantidad_unidades', 0) }}"
                                               min="0" step="0.01"
                                               class="block w-48 rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-500">unidades</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Notas --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea name="notas" rows="2"
                                  class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notas') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('clientes.periodos.index', $cliente) }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 cursor-pointer">
                            Guardar consumo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection