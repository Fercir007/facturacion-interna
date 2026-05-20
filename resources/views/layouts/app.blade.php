<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Facturación') — OpenBanking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">

<div class="min-h-full">
    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Logo + nav links --}}
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="{{ asset('images/cirenio_logo.png') }}" alt="Logo" class="h-8 w-auto">
                    </div>
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-6">
                        <a href="{{ route('clientes.index') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2
                                  {{ request()->routeIs('clientes.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Clientes
                        </a>
                        <a href="{{ route('productos.index') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2
                                  {{ request()->routeIs('productos.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Productos
                        </a>
                    </div>
                </div>

                {{-- Usuario + logout --}}
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-sm text-gray-500 hover:text-gray-700 cursor-pointer">
                            Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Page header --}}
    @hasSection('header')
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            @yield('header')
        </div>
    </header>
    @endif

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="rounded-md bg-green-50 border border-green-200 p-4">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="rounded-md bg-red-50 border border-red-200 p-4">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Main content --}}
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>
</div>

</body>
</html>
