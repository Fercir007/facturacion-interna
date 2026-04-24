<?php

use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\ConfiguracionComercialController;
use Illuminate\Support\Facades\Route;

Route::apiResource('clientes', ClienteController::class)
    ->names([
        'index'   => 'api.clientes.index',
        'store'   => 'api.clientes.store',
        'show'    => 'api.clientes.show',
        'update'  => 'api.clientes.update',
        'destroy' => 'api.clientes.destroy',
    ]);

Route::apiResource('clientes.configuraciones-comerciales', ConfiguracionComercialController::class)
    ->scoped()
    ->parameters(['configuraciones-comerciales' => 'configuracion_comercial'])
    ->names([
        'index'   => 'api.configuraciones-comerciales.index',
        'store'   => 'api.configuraciones-comerciales.store',
        'show'    => 'api.configuraciones-comerciales.show',
        'update'  => 'api.configuraciones-comerciales.update',
        'destroy' => 'api.configuraciones-comerciales.destroy',
    ]);
