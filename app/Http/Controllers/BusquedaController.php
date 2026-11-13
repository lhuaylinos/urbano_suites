<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\TipoHabitacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

class BusquedaController extends Controller
{
    // CUS03: buscar disponibilidad por fechas y número de huéspedes
    public function index(Request $request)
    {
        $datos = $request->validate([
            'fecha_ingreso' => ['required', 'date', 'after_or_equal:today'],
            'fecha_salida'  => ['required', 'date', 'after:fecha_ingreso'],
            'huespedes'     => ['required', 'integer', 'min:1', 'max:10'],
        ], [], [
            'fecha_ingreso' => 'fecha de entrada',
            'fecha_salida'  => 'fecha de salida',
            'huespedes'     => 'huéspedes',
        ]);

        try {
            $entrada = $datos['fecha_ingreso'];
            $salida = $datos['fecha_salida'];
            $noches = (int) Carbon::parse($entrada)->diffInDays(Carbon::parse($salida));

            // Solo tipos activos donde caben los huéspedes y que tengan habitaciones libres
            $tipos = TipoHabitacion::activos()
                ->where('capacidad', '>=', $datos['huespedes'])
                ->orderBy('precio_base')
                ->get()
                ->each(function ($tipo) use ($entrada, $salida, $noches) {
                    $tarifa = $tipo->tarifaVigente($entrada);

                    $tipo->libres = $tipo->habitacionesLibres($entrada, $salida);
                    $tipo->temporada = $tarifa?->temporada;
                    $tipo->precio_noche = (float) ($tarifa?->precio_noche ?? $tipo->precio_base);
                    $tipo->total_con_igv = Reserva::montosEstadia($tipo->precio_noche, $noches)['total'];
                })
                ->filter(fn ($tipo) => $tipo->libres > 0);
        } catch (Throwable $e) {
            return redirect()->route('inicio')
                ->with('error', 'No se pudo consultar la disponibilidad, intenta de nuevo');
        }

        return view('reservas.busqueda', [
            'tipos'     => $tipos,
            'entrada'   => $entrada,
            'salida'    => $salida,
            'huespedes' => $datos['huespedes'],
            'noches'    => $noches,
        ]);
    }
}
