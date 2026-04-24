<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">

<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <img 
                src="{{ asset('images/cirenio_logo.png') }}" 
                alt="Logo empresa" 
                class="mx-auto h-12"
            >
            <p class="mt-1 text-sm text-gray-500">Ingresá con tu cuenta</p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email"
                               autocomplete="email" required autofocus
                               value="{{ old('email') }}"
                               class="block w-full appearance-none rounded-md border px-3 py-2 text-sm
                                      shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                                      {{ $errors->has('email') ? 'border-red-300' : 'border-gray-300' }}">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password"
                               autocomplete="current-password" required
                               class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 text-sm
                                      shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600">
                        Recordarme
                    </label>
                </div>

                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm
                               text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                    Iniciar sesión
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>