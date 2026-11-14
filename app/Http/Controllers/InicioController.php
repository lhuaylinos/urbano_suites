<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;

class InicioController extends Controller
{
    public function index()
    {
        // las 3 suites mas caras salen en el home
        $suites = TipoHabitacion::activos()->orderByDesc('precio_base')->take(3)->get();

        return view('inicio', compact('suites'));
    }
}
