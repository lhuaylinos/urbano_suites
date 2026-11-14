<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;

class InicioController extends Controller
{
    public function index()
    {
        // --- suites del home (cuantas y en que orden) ---
        $suites = TipoHabitacion::activos()->orderBy('precio_base')->take(3)->get();

        return view('inicio', compact('suites'));
    }
}
