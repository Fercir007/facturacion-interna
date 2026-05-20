@php
    $productoModel = $producto ?? new \App\Models\Producto(['activo' => true]);
@endphp

<div>
    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre <span class="text-red-500">*</span></label>
    <input type="text" name="nombre" id="nombre"
           value="{{ old('nombre', $productoModel->nombre ?? '') }}"
           class="mt-1 block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                  {{ $errors->has('nombre') ? 'border-red-300' : 'border-gray-300' }}">
    @error('nombre')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

<div>
    <label for="tipo_pricing" class="block text-sm font-medium text-gray-700">Tipo de pricing <span class="text-red-500">*</span></label>
    <select name="tipo_pricing" id="tipo_pricing"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm bg-white
                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @foreach($tiposPricing as $tipo)
            <option value="{{ $tipo->value }}"
                {{ old('tipo_pricing', $productoModel->tipo_pricing?->value ?? '') === $tipo->value ? 'selected' : '' }}>
                {{ $tipo->label() }}
            </option>
        @endforeach
    </select>
    @error('tipo_pricing')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

<div>
    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
    <textarea name="descripcion" id="descripcion" rows="3"
              class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                     focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('descripcion', $productoModel->descripcion ?? '') }}</textarea>
    @error('descripcion')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

<div class="flex items-center gap-2">
    <input type="hidden" name="activo" value="0">
    <input type="checkbox" name="activo" id="activo" value="1"
           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
        {{ old('activo', $productoModel->activo ?? true) ? 'checked' : '' }}>
    <label for="activo" class="text-sm font-medium text-gray-700">Activo en catálogo</label>
</div>
@error('activo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
