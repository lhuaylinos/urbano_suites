<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\TipoHabitacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

class ReporteController extends Controller
{
    // --- estados que cuenta el reporte ---
    private const ESTADOS_RESERVA = ['Pendiente', 'Confirmada', 'En curso', 'Finalizada', 'Cancelada'];

    public function index(Request $request)
    {
        // --- rango de fechas ---
        $datos = $request->validate([
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ], [], [
            'desde' => 'fecha inicial',
            'hasta' => 'fecha final',
        ]);

        // sin rango se muestra lo que va del mes
        $desde = $datos['desde'] ?? now()->startOfMonth()->toDateString();
        $hasta = $datos['hasta'] ?? now()->toDateString();

        try {
            // --- ocupacion por tipo ---
            $habitaciones = Habitacion::activas()->get()->groupBy('tipo_habitacion_id');

            $porTipo = TipoHabitacion::orderBy('nombre')->get()->map(function ($tipo) use ($habitaciones) {
                $lista = $habitaciones->get($tipo->id, collect());

                return [
                    'nombre'        => $tipo->nombre,
                    'total'         => $lista->count(),
                    'ocupadas'      => $lista->where('estado', 'Ocupada')->count(),
                    'limpieza'      => $lista->where('estado', 'Limpieza')->count(),
                    'mantenimiento' => $lista->where('estado', 'Mantenimiento')->count(),
                ];
            })->filter(fn ($fila) => $fila['total'] > 0);

            $totalHabitaciones = $porTipo->sum('total');
            $ocupadas = $porTipo->sum('ocupadas');
            $porcentaje = $totalHabitaciones > 0 ? round($ocupadas / $totalHabitaciones * 100) : 0;

            // --- reservas por estado ---
            $conteo = Reserva::whereBetween('fecha_entrada', [$desde, $hasta])
                ->selectRaw('estado, count(*) as cantidad')
                ->groupBy('estado')
                ->pluck('cantidad', 'estado');

            $reservasPorEstado = collect(self::ESTADOS_RESERVA)
                ->mapWithKeys(fn ($estado) => [$estado => (int) ($conteo[$estado] ?? 0)]);

            // --- ingresos ---
            // solo pagos aprobados, los reembolsados no suman
            $ingresos = Pago::where('estado', 'Aprobado')
                ->whereBetween('created_at', [Carbon::parse($desde)->startOfDay(), Carbon::parse($hasta)->endOfDay()])
                ->selectRaw('concepto, sum(monto) as total')
                ->groupBy('concepto')
                ->pluck('total', 'concepto');
        } catch (Throwable $e) {
            return redirect()->route('admin.panel')
                ->with('error', 'No se pudo generar el reporte');
        }

        // --- datos que recibe la vista ---
        return view('admin.reporte', [
            'desde'             => $desde,
            'hasta'             => $hasta,
            'porTipo'           => $porTipo,
            'totalHabitaciones' => $totalHabitaciones,
            'ocupadas'          => $ocupadas,
            'porcentaje'        => $porcentaje,
            'reservasPorEstado' => $reservasPorEstado,
            'ingresosEstadia'   => (float) ($ingresos['Estadía'] ?? 0),
            'ingresosConsumos'  => (float) ($ingresos['Consumos'] ?? 0),
        ]);
    }
}
