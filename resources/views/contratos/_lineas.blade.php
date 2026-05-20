@if($errors->has('lineas') || $errors->has('lineas.*'))
    <div class="rounded-md bg-red-50 border border-red-200 p-3 text-sm text-red-800">
        Revisá las líneas y sus bandas: completá los campos obligatorios y marcá una banda activa por línea.
    </div>
@endif

@if($productos->isEmpty())
    <div class="rounded-md bg-amber-50 border border-amber-200 p-3 text-sm text-amber-900">
        No hay productos disponibles.
        <a href="{{ route('productos.create') }}" class="font-medium text-indigo-700 underline">Creá un producto</a>
        para poder asociarlo al contrato.
    </div>
@endif

<div x-data="{
    productosCatalog: @js($productosJson->values()->all()),
    lineas: @js($lineasInitial),
    topePorUnidad(b) {
        const hasta = parseInt(String(b.unidades_hasta ?? '').trim(), 10);
        const costo = parseFloat(String(b.costo_fijo ?? '').replace(',', '.'));
        if (!Number.isFinite(hasta) || hasta <= 0 || !Number.isFinite(costo)) return '—';
        return (Math.round((costo / hasta) * 100) / 100).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },
    setBandaActiva(linea, bi) {
        linea.bandas.forEach((x, i) => { x.es_banda_activa = (i === bi); });
    },
    nuevaLinea() {
        return {
            producto_id: '',
            currency: 'USD',
            setup_monto: '0',
            setup_cuotas: 1,
            mrr: '0',
            notas: '',
            bandas: [
                {
                    numero_banda: 1,
                    unidades_desde: 0,
                    unidades_hasta: '',
                    costo_fijo: '0',
                    precio_excedente_por_unidad: '0',
                    es_banda_activa: true,
                },
            ],
        };
    },
    agregarLinea() {
        this.lineas.push(this.nuevaLinea());
    },
    agregarBanda(linea) {
        const n = linea.bandas.length ? Math.max(...linea.bandas.map(b => parseInt(b.numero_banda, 10) || 0)) + 1 : 1;
        linea.bandas.push({
            numero_banda: n,
            unidades_desde: 0,
            unidades_hasta: '',
            costo_fijo: '0',
            precio_excedente_por_unidad: '0',
            es_banda_activa: false,
        });
    },
    quitarBanda(linea, bi) {
        if (linea.bandas.length <= 1) return;
        const eraActiva = linea.bandas[bi].es_banda_activa;
        linea.bandas.splice(bi, 1);
        if (eraActiva) linea.bandas[0].es_banda_activa = true;
    },
}">
    <div class="flex items-center justify-between mb-2">
        <label class="block text-sm font-medium text-gray-700">Productos del contrato</label>
        <button type="button" @click="agregarLinea()"
                class="text-xs text-indigo-600 hover:text-indigo-800 font-medium cursor-pointer">
            + Agregar línea
        </button>
    </div>

    <div class="space-y-6">
        <template x-for="(linea, index) in lineas" :key="index">
            <div class="border border-gray-200 rounded-md p-4 bg-gray-50 space-y-4">

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">
                    <div class="lg:col-span-5">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Producto</label>
                        <select x-model="linea.producto_id"
                                :name="`lineas[${index}][producto_id]`"
                                class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">— Elegir —</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Moneda</label>
                        <select x-model="linea.currency"
                                :name="`lineas[${index}][currency]`"
                                class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @foreach(\App\Enums\Currency::cases() as $cur)
                                <option value="{{ $cur->value }}">{{ $cur->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Setup</label>
                        <input type="text" inputmode="decimal" x-model="linea.setup_monto"
                               :name="`lineas[${index}][setup_monto]`"
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="lg:col-span-1">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Cuotas</label>
                        <input type="number" min="1" max="120" x-model.number="linea.setup_cuotas"
                               :name="`lineas[${index}][setup_cuotas]`"
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">MRR</label>
                        <input type="text" inputmode="decimal" x-model="linea.mrr"
                               :name="`lineas[${index}][mrr]`"
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Notas de línea</label>
                    <input type="text" x-model="linea.notas"
                           :name="`lineas[${index}][notas]`"
                           class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                {{-- Bandas --}}
                <div class="rounded-md border border-gray-200 bg-white overflow-hidden">
                    <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Bandas de consumo</span>
                        <button type="button" @click="agregarBanda(linea)"
                                class="text-xs text-indigo-600 hover:text-indigo-800 font-medium cursor-pointer">
                            + Agregar banda
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500">Banda</th>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500">Desde</th>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500">Hasta</th>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500">Costo fijo</th>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500">Excedente/u</th>
                                    <th class="px-2 py-2 text-center font-medium text-gray-500">Tope/u</th>
                                    <th class="px-2 py-2 text-center font-medium text-gray-500">Activa</th>
                                    <th class="px-2 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="(b, bi) in linea.bandas" :key="bi">
                                    <tr>
                                        <td class="px-2 py-1.5 align-middle">
                                            <input type="number" min="1" max="255" x-model.number="b.numero_banda"
                                                   :name="`lineas[${index}][bandas][${bi}][numero_banda]`"
                                                   class="w-14 rounded border border-gray-300 px-1 py-1 text-xs font-mono">
                                        </td>
                                        <td class="px-2 py-1.5 align-middle">
                                            <input type="number" min="0" x-model.number="b.unidades_desde"
                                                   :name="`lineas[${index}][bandas][${bi}][unidades_desde]`"
                                                   class="w-20 rounded border border-gray-300 px-1 py-1 text-xs font-mono">
                                        </td>
                                        <td class="px-2 py-1.5 align-middle">
                                            <input type="text" inputmode="numeric" placeholder="∞" x-model="b.unidades_hasta"
                                                   :name="`lineas[${index}][bandas][${bi}][unidades_hasta]`"
                                                   class="w-20 rounded border border-gray-300 px-1 py-1 text-xs font-mono">
                                        </td>
                                        <td class="px-2 py-1.5 align-middle">
                                            <input type="text" inputmode="decimal" x-model="b.costo_fijo"
                                                   :name="`lineas[${index}][bandas][${bi}][costo_fijo]`"
                                                   class="w-24 rounded border border-gray-300 px-1 py-1 text-xs font-mono">
                                        </td>
                                        <td class="px-2 py-1.5 align-middle">
                                            <input type="text" inputmode="decimal" x-model="b.precio_excedente_por_unidad"
                                                   :name="`lineas[${index}][bandas][${bi}][precio_excedente_por_unidad]`"
                                                   class="w-24 rounded border border-gray-300 px-1 py-1 text-xs font-mono">
                                        </td>
                                        <td class="px-2 py-1.5 text-center align-middle font-mono text-gray-600" x-text="topePorUnidad(b)"></td>
                                        <td class="px-2 py-1.5 text-center align-middle">
                                            <input type="radio" class="text-indigo-600 focus:ring-indigo-500"
                                                   :checked="b.es_banda_activa"
                                                   @change="setBandaActiva(linea, bi)"
                                                   :name="'banda_activa_linea_' + index">
                                            <input type="hidden" :name="`lineas[${index}][bandas][${bi}][es_banda_activa]`" :value="b.es_banda_activa ? 1 : 0">
                                        </td>
                                        <td class="px-2 py-1.5 text-right align-middle">
                                            <button type="button"
                                                    x-show="linea.bandas.length > 1"
                                                    @click="quitarBanda(linea, bi)"
                                                    class="text-red-500 hover:text-red-700 cursor-pointer text-xs">
                                                Quitar
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <p class="px-3 py-2 text-xs text-gray-500 border-t border-gray-100">
                        “Hasta” vacío = sin techo. El tope por unidad es informativo (costo fijo ÷ unidades hasta).
                    </p>
                </div>

                <button type="button"
                        x-show="lineas.length > 1"
                        @click="lineas.splice(index, 1)"
                        class="text-xs text-red-500 hover:text-red-700 cursor-pointer">
                    Quitar línea
                </button>
            </div>
        </template>
    </div>
</div>
