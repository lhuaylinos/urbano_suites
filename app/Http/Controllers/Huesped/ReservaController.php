<?php

namespace App\Http\Controllers\Huesped;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\TipoHabitacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class ReservaController extends Controller
{
    private function reglasFechas(): array
    {
        return [
            'fecha_ingreso' => ['required', 'date', 'after_or_equal:today'],
            'fecha_salida'  => ['required', 'date', 'after:fecha_ingreso'],
            'huespedes'     => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }

    private function nombres(): array
    {
        return [
            'fecha_ingreso'  => 'fecha de entrada',
            'fecha_salida'   => 'fecha de salida',
            'huespedes'      => 'huéspedes',
            'titular'        => 'titular',
            'numero_tarjeta' => 'número de tarjeta',
            'vence'          => 'fecha de vencimiento',
            'cvv'            => 'código de seguridad',
        ];
    }

    // Una reserva solo la ve su dueño
    private function soloDueno(Reserva $reserva): void
    {
        abort_unless($reserva->user_id === auth()->id(), 403);
    }

    // Mis reservas (A11)
    public function index()
    {
        try {
            $reservas = Reserva::with('tipo')
                ->where('user_id', auth()->id())
                ->orderByDesc('created_at')
                ->get();
        } catch (Throwable $e) {
            return redirect()->route('inicio')->with('error', 'No se pudieron cargar tus reservas');
        }

        return view('cuenta.mis-reservas', compact('reservas'));
    }

    // Resumen de la reserva y formulario de pago (A16)
    public function resumen(Request $request, TipoHabitacion $tipo)
    {
        $datos = $request->validate($this->reglasFechas(), [], $this->nombres());
        abort_unless($tipo->activo, 404);

        if ($datos['huespedes'] > $tipo->capacidad) {
            return redirect()->route('inicio')->with('error', 'Ese tipo de habitación no tiene capacidad para tantos huéspedes');
        }

        try {
            $libres = $tipo->habitacionesLibres($datos['fecha_ingreso'], $datos['fecha_salida']);
        } catch (Throwable $e) {
            return redirect()->route('inicio')->with('error', 'No se pudo consultar la disponibilidad');
        }

        if ($libres < 1) {
            return redirect()->route('buscar', $datos)
                ->with('error', 'Ya no quedan habitaciones de ese tipo para esas fechas');
        }

        $noches = (int) Carbon::parse($datos['fecha_ingreso'])->diffInDays(Carbon::parse($datos['fecha_salida']));

        // D3: se usa la tarifa de temporada vigente en la fecha de ingreso; si no hay, el precio base
        $tarifa = $tipo->tarifaVigente($datos['fecha_ingreso']);
        $precioNoche = (float) ($tarifa?->precio_noche ?? $tipo->precio_base);

        return view('reservas.resumen', [
            'tipo'        => $tipo,
            'entrada'     => $datos['fecha_ingreso'],
            'salida'      => $datos['fecha_salida'],
            'huespedes'   => $datos['huespedes'],
            'noches'      => $noches,
            'tarifa'      => $tarifa,
            'precioNoche' => $precioNoche,
            'montos'      => Reserva::montosEstadia($precioNoche, $noches),
        ]);
    }

    // Confirmar la reserva y cobrar la estadía (pago simulado, D2)
    public function store(Request $request, TipoHabitacion $tipo)
    {
        // Se quitan los espacios del número de tarjeta antes de validar
        $request->merge([
            'numero_tarjeta' => preg_replace('/\s+/', '', (string) $request->input('numero_tarjeta')),
        ]);

        $datos = $request->validate($this->reglasFechas() + [
            'titular'        => ['required', 'string', 'max:80'],
            'numero_tarjeta' => ['required', 'digits_between:13,19'],
            'vence'          => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv'            => ['required', 'digits_between:3,4'],
            'terminos'       => ['accepted'],
        ], [
            'vence.regex' => 'La fecha de vencimiento debe tener el formato MM/AA',
        ], $this->nombres() + ['terminos' => 'términos y condiciones']);

        abort_unless($tipo->activo, 404);

        // Simulación: las tarjetas que terminan en 0000 son rechazadas
        if (str_ends_with($datos['numero_tarjeta'], '0000')) {
            return view('reservas.rechazado', [
                'tipo'      => $tipo,
                'entrada'   => $datos['fecha_ingreso'],
                'salida'    => $datos['fecha_salida'],
                'huespedes' => $datos['huespedes'],
            ]);
        }

        try {
            $reserva = DB::transaction(function () use ($datos, $tipo) {
                // Se vuelve a comprobar la disponibilidad justo antes de guardar
                if ($tipo->habitacionesLibres($datos['fecha_ingreso'], $datos['fecha_salida']) < 1) {
                    return null;
                }

                $noches = (int) Carbon::parse($datos['fecha_ingreso'])->diffInDays(Carbon::parse($datos['fecha_salida']));
                $precioNoche = $tipo->precioPara($datos['fecha_ingreso']);
                $montos = Reserva::montosEstadia($precioNoche, $noches);

                $reserva = Reserva::create([
                    'codigo'             => Reserva::generarCodigo(),
                    'user_id'            => auth()->id(),
                    'tipo_habitacion_id' => $tipo->id,
                    'fecha_entrada'      => $datos['fecha_ingreso'],
                    'fecha_salida'       => $datos['fecha_salida'],
                    'huespedes'          => $datos['huespedes'],
                    'noches'             => $noches,
                    'precio_noche'       => $precioNoche,
                    'total_estadia'      => $montos['total'],
                    'estado'             => 'Confirmada',
                ]);

                Pago::create([
                    'reserva_id' => $reserva->id,
                    'concepto'   => 'Estadía',
                    'monto'      => $montos['total'],
                    'metodo'     => 'Tarjeta terminada en ' . substr($datos['numero_tarjeta'], -4),
                    'referencia' => 'PAG-' . strtoupper(Str::random(8)),
                    'estado'     => 'Aprobado',
                ]);

                return $reserva;
            });
        } catch (Throwable $e) {
            return back()->withInput()
                ->with('error', 'No se pudo completar la reserva, intenta de nuevo');
        }

        if (! $reserva) {
            return redirect()->route('buscar', [
                'fecha_ingreso' => $datos['fecha_ingreso'],
                'fecha_salida'  => $datos['fecha_salida'],
                'huespedes'     => $datos['huespedes'],
            ])->with('error', 'Ya no quedan habitaciones de ese tipo para esas fechas');
        }

        return redirect()->route('reservas.confirmada', $reserva);
    }

    // Reserva confirmada (A17)
    public function confirmada(Reserva $reserva)
    {
        $this->soloDueno($reserva);
        $reserva->load('tipo');

        return view('reservas.confirmada', compact('reserva'));
    }

    // Detalle de una reserva (A12)
    public function show(Reserva $reserva)
    {
        $this->soloDueno($reserva);
        $reserva->load(['tipo', 'habitacion', 'pagos', 'consumos.servicio']);

        return view('cuenta.reserva', compact('reserva'));
    }

    // Cancelar: solo si todavía está confirmada. El pago de la estadía pasa a "Reembolsado"
    public function cancelar(Reserva $reserva)
    {
        $this->soloDueno($reserva);

        if ($reserva->estado !== 'Confirmada') {
            return back()->with('error', 'Solo se pueden cancelar las reservas confirmadas');
        }

        try {
            DB::transaction(function () use ($reserva) {
                $reserva->update(['estado' => 'Cancelada']);
                $reserva->pagos()->where('estado', 'Aprobado')->update(['estado' => 'Reembolsado']);
            });
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo cancelar la reserva');
        }

        return redirect()->route('reservas.show', $reserva)
            ->with('exito', 'Reserva cancelada, el pago será reembolsado');
    }
}
