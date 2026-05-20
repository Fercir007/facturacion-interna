<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\PeriodoFacturacion;
use App\Services\FacturacionService;

class FacturaController extends Controller
{
    public function __construct(
        private readonly FacturacionService $facturacionService
    ) {}

    public function show(Cliente $cliente, PeriodoFacturacion $periodo)
    {
        $calculo = $this->facturacionService->calcular($periodo);

        return view('facturas.show', compact('cliente', 'periodo', 'calculo'));
    }
}