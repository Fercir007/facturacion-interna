{{-- Fila: CUIT + Tipo de cliente --}}
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div>
        <label for="cuit" class="block text-sm font-medium text-gray-700">CUIT <span class="text-red-500">*</span></label>
        <input type="text" name="cuit" id="cuit"
               value="{{ old('cuit', $cliente->cuit ?? '') }}"
               placeholder="20-12345678-9"
               class="mt-1 block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                      {{ $errors->has('cuit') ? 'border-red-300' : 'border-gray-300' }}">
        @error('cuit')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="tipo_cliente" class="block text-sm font-medium text-gray-700">Tipo de cliente <span class="text-red-500">*</span></label>
        <select name="tipo_cliente" id="tipo_cliente"
                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white
                       {{ $errors->has('tipo_cliente') ? 'border-red-300' : 'border-gray-300' }}">
            @foreach($tiposCliente as $tipo)
                <option value="{{ $tipo->value }}"
                    {{ old('tipo_cliente', $cliente->tipo_cliente->value ?? '') === $tipo->value ? 'selected' : '' }}>
                    {{ $tipo->label() }}
                </option>
            @endforeach
        </select>
        @error('tipo_cliente')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Razón social + Nombre comercial --}}
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div>
        <label for="razon_social" class="block text-sm font-medium text-gray-700">Razón social</label>
        <input type="text" name="razon_social" id="razon_social"
               value="{{ old('razon_social', $cliente->razon_social ?? '') }}"
               class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('razon_social')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="nombre_comercial" class="block text-sm font-medium text-gray-700">Nombre comercial</label>
        <input type="text" name="nombre_comercial" id="nombre_comercial"
               value="{{ old('nombre_comercial', $cliente->nombre_comercial ?? '') }}"
               class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('nombre_comercial')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Referente + Email --}}
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div>
        <label for="referente" class="block text-sm font-medium text-gray-700">Referente</label>
        <input type="text" name="referente" id="referente"
               value="{{ old('referente', $cliente->referente ?? '') }}"
               placeholder="Nombre y apellido del contacto"
               class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('referente')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" id="email"
               value="{{ old('email', $cliente->email ?? '') }}"
               class="mt-1 block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                      {{ $errors->has('email') ? 'border-red-300' : 'border-gray-300' }}">
        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Notas --}}
<div>
    <label for="notes" class="block text-sm font-medium text-gray-700">Notas internas</label>
    <textarea name="notes" id="notes" rows="3"
              placeholder="Observaciones del cliente, condiciones especiales, etc."
              class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                     focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notes', $cliente->notes ?? '') }}</textarea>
    @error('notes')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
