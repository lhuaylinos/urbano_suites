<?php

namespace App\Http\Controllers;

use App\Models\ServicioAdicional;

class ExperienciaController extends Controller
{
    public function index()
    {
        $servicios = ServicioAdicional::activos()->orderBy('id')->get();

        return view('paginas.experiencias', compact('servicios'));
    }
}
