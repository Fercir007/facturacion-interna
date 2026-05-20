<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeriodoFacturacionController;
use App\Http\Controllers\FacturaController;

// Redirige la raíz al dashboard o al login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('clientes.index')
        : redirect()->route('login');
});

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('clientes.index'));
        }

        return back()->withErrors(['email' => 'Las credenciales no son correctas.'])->onlyInput('email');
    });
    
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

// App — protegido por auth
Route::middleware('auth')->group(function () {
    Route::resource('productos', ProductoController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('clientes.contratos', ContratoController::class)->scoped();
    Route::resource('clientes.periodos', PeriodoFacturacionController::class)
        ->scoped()
        ->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::get('clientes/{cliente}/periodos/{periodo}/factura', [FacturaController::class, 'show'])
        ->name('clientes.periodos.factura');
});
