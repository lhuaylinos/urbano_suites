<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;

class SuiteController extends Controller
{
    // --- listado de suites ---
    public function index()
    {
        $tipos = TipoHabitacion::activos()->orderBy('precio_base')->get();

        return view('suites.index', compact('tipos'));
    }

    // --- detalle de una suite ---
    public function show(TipoHabitacion $tipo)
    {
        abort_unless($tipo->activo, 404);

        return view('suites.show', compact('tipo'));
    }
}
