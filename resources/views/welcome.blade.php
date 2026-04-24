<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Facturación interna</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 text-slate-900">
        <div class="min-h-screen flex items-center justify-center px-4 py-10">
            <div class="max-w-3xl w-full card p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 mb-2">Facturación interna</h1>
                        <p class="text-slate-600">Sistema simple para administración de clientes y facturación interna.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('clientes.index') }}" class="btn-primary">Ver clientes</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary">Ingresar</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-secondary">Registrarse</a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="p-6 border border-slate-200 rounded-2xl bg-white">
                        <h2 class="text-xl font-semibold text-slate-900 mb-3">Módulo de Clientes</h2>
                        <p class="text-slate-600">Agrega y edita clientes fácilmente, manteniendo datos clave como CUIT, nombre, email y notas.</p>
                    </div>
                    <div class="p-6 border border-slate-200 rounded-2xl bg-white">
                        <h2 class="text-xl font-semibold text-slate-900 mb-3">Diseño claro</h2>
                        <p class="text-slate-600">Interfaz simple basada en Blade y Tailwind, ideal para un MVP funcional sin sobreingeniería.</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
