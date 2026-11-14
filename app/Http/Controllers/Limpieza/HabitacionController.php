<?php

namespace App\Http\Controllers\Limpieza;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use Throwable;

class HabitacionController extends Controller
{
    // --- lista del panel (solo en limpieza) ---
    public function index()
    {
        try {
            $habitaciones = Habitacion::with('tipo')
                ->activas()
                ->where('estado', 'Limpieza')
                ->orderBy('numero')
                ->get();
        } catch (Throwable $e) {
            return redirect()->route('inicio')->with('error', 'No se pudo cargar la lista de habitaciones');
        }

        return view('limpieza.panel', compact('habitaciones'));
    }

    // --- liberar habitacion ---
    // al terminar la limpieza la habitacion queda disponible
    public function liberar(Habitacion $habitacion)
    {
        if ($habitacion->estado !== 'Limpieza') {
            return back()->with('error', 'Esa habitación no está en limpieza');
        }

        try {
            $habitacion->update(['estado' => 'Disponible']);
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo actualizar la habitación');
        }

        return back()->with('exito', 'Habitación ' . $habitacion->numero . ' disponible');
    }
}
