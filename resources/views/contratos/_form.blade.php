@php
    /** @var \App\Models\Contrato|null $contrato */
    $c = $contrato ?? null;
@endphp

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div>
        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha inicio <span class="text-red-500">*</span></label>
        <input type="date" name="fecha_inicio" id="fecha_inicio"
               value="{{ old('fecha_inicio', $c?->fecha_inicio?->format('Y-m-d') ?? '') }}"
               class="mt-1 block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                      {{ $errors->has('fecha_inicio') ? 'border-red-300' : 'border-gray-300' }}">
        @error('fecha_inicio')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha fin</label>
        <input type="date" name="fecha_fin" id="fecha_fin"
               value="{{ old('fecha_fin', $c?->fecha_fin?->format('Y-m-d') ?? '') }}"
               class="mt-1 block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                      {{ $errors->has('fecha_fin') ? 'border-red-300' : 'border-gray-300' }}">
        @error('fecha_fin')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label for="status" class="block text-sm font-medium text-gray-700">Estado <span class="text-red-500">*</span></label>
    <select name="status" id="status"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @foreach(\App\Enums\ContratoStatus::cases() as $st)
            <option value="{{ $st->value }}"
                {{ old('status', $c?->status?->value ?? \App\Enums\ContratoStatus::Borrador->value) === $st->value ? 'selected' : '' }}>
                {{ $st->label() }}
            </option>
        @endforeach
    </select>
    @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    <p class="mt-1 text-xs text-gray-500">Si marcás <strong>vigente</strong>, los demás contratos de este cliente pasan a <strong>vencido</strong>.</p>
</div>

<div>
    <label for="notas" class="block text-sm font-medium text-gray-700">Notas</label>
    <textarea name="notas" id="notas" rows="2"
              class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notas', $c?->notas ?? '') }}</textarea>
    @error('notas')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
