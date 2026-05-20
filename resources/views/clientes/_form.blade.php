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

{{-- Email --}}
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email"
               value="{{ old('email', $cliente->email ?? '') }}"
               class="mt-1 block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                      {{ $errors->has('email') ? 'border-red-300' : 'border-gray-300' }}">
        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
        <input type="text" name="telefono" id="telefono"
               value="{{ old('telefono', $cliente->telefono ?? '') }}"
               placeholder="+54 11 1234-5678"
               class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('telefono')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Referentes --}}
<div x-data="{
    referentes: {{ json_encode(
        old('referentes',
            isset($cliente) && $cliente->referentes->isNotEmpty()
                ? $cliente->referentes->map(fn($r) => [
                    'nombre'   => $r->nombre,
                    'email'    => $r->email ?? '',
                    'telefono' => $r->telefono ?? '',
                  ])->values()->toArray()
                : [['nombre' => '', 'email' => '', 'telefono' => '']]
        )
    ) }}
}">
    <div class="flex items-center justify-between mb-2">
        <label class="block text-sm font-medium text-gray-700">Referentes</label>
        <button type="button"
                @click="referentes.push({ nombre: '', email: '', telefono: '' })"
                class="text-xs text-indigo-600 hover:text-indigo-800 font-medium cursor-pointer">
            + Agregar referente
        </button>
    </div>

    <div class="space-y-3">
        <template x-for="(ref, index) in referentes" :key="index">
            <div class="border border-gray-200 rounded-md p-4 bg-gray-50 relative">

                <span x-show="index === 0"
                      class="absolute top-3 right-3 text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium">
                    Principal
                </span>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 pr-24">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nombre *</label>
                        <input type="text"
                               :name="`referentes[${index}][nombre]`"
                               x-model="ref.nombre"
                               placeholder="Nombre y apellido"
                               class="block w-full rounded-md border border-gray-300 px-3 py-1.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                        <input type="email"
                               :name="`referentes[${index}][email]`"
                               x-model="ref.email"
                               placeholder="contacto@empresa.com"
                               class="block w-full rounded-md border border-gray-300 px-3 py-1.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Teléfono</label>
                        <input type="text"
                               :name="`referentes[${index}][telefono]`"
                               x-model="ref.telefono"
                               placeholder="+54 11 1234-5678"
                               class="block w-full rounded-md border border-gray-300 px-3 py-1.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <button type="button"
                        x-show="index !== 0"
                        @click="referentes.splice(index, 1)"
                        class="absolute bottom-3 right-3 text-xs text-red-400 hover:text-red-600 cursor-pointer">
                    Quitar
                </button>
            </div>
        </template>
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