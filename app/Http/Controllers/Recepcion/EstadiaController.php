<?php

namespace App\Http\Controllers\Recepcion;

use App\Http\Controllers\Controller;
use App\Models\Consumo;
use App\Models\Habitacion;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\ServicioAdicional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class EstadiaController extends Controller
{
    // Panel de recepción (B02): llegadas pendientes y huéspedes en el hotel
    public function panel()
    {
        try {
            $llegadas = Reserva::with(['user', 'tipo'])
                ->where('estado', 'Confirmada')
                ->orderBy('fecha_entrada')
                ->get();

            $enCasa = Reserva::with(['user', 'tipo', 'habitacion'])
                ->where('estado', 'En curso')
                ->orderBy('fecha_salida')
                ->get();
        } catch (Throwable $e) {
            return redirect()->route('inicio')->with('error', 'No se pudo cargar el panel de recepción');
        }

        return view('recepcion.panel', compact('llegadas', 'enCasa'));
    }

    // CUS08 Buscar reserva: por DNI, pasaporte (número de documento) o código de reserva
    public function buscar(Request $request)
    {
        $datos = $request->validate([
            'q' => ['required', 'string', 'max:30'],
        ], [], ['q' => 'búsqueda']);

        $texto = trim($datos['q']);

        try {
            $reservas = Reserva::with(['user', 'tipo', 'habitacion'])
                ->where(function ($consulta) use ($texto) {
                    $consulta->where('codigo', 'like', '%' . $texto . '%')
                        ->orWhereHas('user', fn ($usuario) => $usuario->where('numero_documento', $texto));
                })
                ->orderByDesc('fecha_entrada')
                ->limit(20)
                ->get();
        } catch (Throwable $e) {
            return redirect()->route('recepcion.panel')->with('error', 'No se pudo realizar la búsqueda');
        }

        return view('recepcion.buscar', compact('reservas', 'texto'));
    }

    // Registrar check-in (B06): se elige la habitación física
    public function checkIn(Reserva $reserva)
    {
        if ($reserva->estado !== 'Confirmada') {
            return redirect()->route('recepcion.panel')->with('error', 'Esa reserva no está lista para check-in');
        }

        $reserva->load(['user', 'tipo']);

        $habitaciones = Habitacion::activas()
            ->where('tipo_habitacion_id', $reserva->tipo_habitacion_id)
            ->where('estado', 'Disponible')
            ->orderBy('numero')
            ->pluck('numero', 'id');

        return view('recepcion.check-in', compact('reserva', 'habitaciones'));
    }

    public function guardarCheckIn(Request $request, Reserva $reserva)
    {
        $datos = $request->validate([
            'habitacion_id' => ['required', 'exists:habitaciones,id'],
        ], [], ['habitacion_id' => 'habitación']);

        if ($reserva->estado !== 'Confirmada') {
            return redirect()->route('recepcion.panel')->with('error', 'Esa reserva no está lista para check-in');
        }

        if ($reserva->fecha_entrada->isFuture()) {
            return back()->with('error', 'Todavía no es la fecha de entrada de esta reserva');
        }

        try {
            DB::transaction(function () use ($datos, $reserva) {
                $habitacion = Habitacion::lockForUpdate()->findOrFail($datos['habitacion_id']);

                // La habitación debe ser del tipo reservado y estar disponible
                if ($habitacion->tipo_habitacion_id !== $reserva->tipo_habitacion_id
                    || $habitacion->estado !== 'Disponible'
                    || ! $habitacion->activo) {
                    abort(422, 'Esa habitación no se puede asignar');
                }

                $habitacion->update(['estado' => 'Ocupada']);
                $reserva->update(['habitacion_id' => $habitacion->id, 'estado' => 'En curso']);
            });
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo registrar el check-in, revisa la habitación elegida');
        }

        return redirect()->route('recepcion.estadia', $reserva)
            ->with('exito', 'Check-in exitoso, la habitación quedó ocupada');
    }

    // Servicios de la estadía (B04)
    public function estadia(Reserva $reserva)
    {
        if ($reserva->estado !== 'En curso') {
            return redirect()->route('recepcion.panel')->with('error', 'Esa estadía no está en curso');
        }

        $reserva->load(['user', 'tipo', 'habitacion', 'consumos.servicio']);
        $servicios = ServicioAdicional::activos()->orderBy('nombre')->get();

        return view('recepcion.estadia', compact('reserva', 'servicios'));
    }

    public function agregarConsumo(Request $request, Reserva $reserva)
    {
        $datos = $request->validate([
            'servicio_adicional_id' => ['required', 'exists:servicios_adicionales,id'],
            'cantidad'              => ['required', 'integer', 'min:1', 'max:20'],
        ], [], ['servicio_adicional_id' => 'servicio']);

        if ($reserva->estado !== 'En curso') {
            return redirect()->route('recepcion.panel')->with('error', 'Esa estadía no está en curso');
        }

        try {
            $servicio = ServicioAdicional::findOrFail($datos['servicio_adicional_id']);

            Consumo::create([
                'reserva_id'            => $reserva->id,
                'servicio_adicional_id' => $servicio->id,
                'cantidad'              => $datos['cantidad'],
                'precio_unitario'       => $servicio->precio,
            ]);
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo registrar el consumo');
        }

        return back()->with('exito', 'Consumo registrado');
    }

    // Check-out y cuenta (B07): solo se cobran los consumos + IGV (D2)
    public function cuenta(Reserva $reserva)
    {
        if ($reserva->estado !== 'En curso') {
            return redirect()->route('recepcion.panel')->with('error', 'Esa estadía no está en curso');
        }

        $reserva->load(['user', 'tipo', 'habitacion', 'consumos.servicio']);

        $subtotal = $reserva->subtotalConsumos();
        $igv = round($subtotal * Reserva::IGV, 2);
        $total = $subtotal + $igv;

        return view('recepcion.cuenta', compact('reserva', 'subtotal', 'igv', 'total'));
    }

    public function checkOut(Request $request, Reserva $reserva)
    {
        $datos = $request->validate([
            'metodo' => ['required', 'in:Efectivo,Tarjeta,Yape'],
        ], [], ['metodo' => 'método de pago']);

        if ($reserva->estado !== 'En curso') {
            return redirect()->route('recepcion.panel')->with('error', 'Esa estadía no está en curso');
        }

        try {
            $total = DB::transaction(function () use ($datos, $reserva) {
                $reserva->load('consumos');
                $subtotal = $reserva->subtotalConsumos();
                $total = $subtotal + round($subtotal * Reserva::IGV, 2);

                if ($total > 0) {
                    Pago::create([
                        'reserva_id' => $reserva->id,
                        'concepto'   => 'Consumos',
                        'monto'      => $total,
                        'metodo'     => $datos['metodo'],
                        'referencia' => 'PAG-' . strtoupper(Str::random(8)),
                        'estado'     => 'Aprobado',
                    ]);
                }

                // La habitación pasa a limpieza antes de volver a estar disponible
                $reserva->habitacion()->update(['estado' => 'Limpieza']);
                $reserva->update(['estado' => 'Finalizada']);

                return $total;
            });
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo completar el check-out');
        }

        // Al terminar se muestra el comprobante para imprimirlo
        return redirect()->route('recepcion.comprobante', $reserva)
            ->with('exito', 'Check-out completado, total cobrado: S/ ' . number_format($total, 2));
    }

    // Comprobante de pago: vista imprimible armada con la reserva, sus consumos y sus pagos
    public function comprobante(Reserva $reserva)
    {
        if ($reserva->estado !== 'Finalizada') {
            return redirect()->route('recepcion.panel')
                ->with('error', 'El comprobante se emite cuando termina la estadía');
        }

        $reserva->load(['user', 'tipo', 'habitacion', 'consumos.servicio', 'pagos']);

        $subtotalConsumos = $reserva->subtotalConsumos();
        $igvConsumos = round($subtotalConsumos * Reserva::IGV, 2);

        // El número del comprobante es el código de operación del último pago aprobado
        $numero = $reserva->pagos->where('estado', 'Aprobado')->sortBy('id')->last()?->referencia;

        return view('recepcion.comprobante', compact('reserva', 'subtotalConsumos', 'igvConsumos', 'numero'));
    }
}
