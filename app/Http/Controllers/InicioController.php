<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;

class InicioController extends Controller
{
    public function index()
    {
        // Las 3 suites más caras se muestran en el Home
        $suites = TipoHabitacion::activos()->orderByDesc('precio_base')->take(3)->get();

        return view('inicio', compact('suites'));
    }
}
