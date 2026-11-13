<?php

namespace Database\Seeders;

use App\Models\Consumo;
use App\Models\Habitacion;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\ServicioAdicional;
use App\Models\TipoHabitacion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/*
 | Escenarios de prueba: una reserva lista para cada paso del proceso.
 | Todas son del huésped de prueba (huesped@urbanosuites.com).
 */
class ReservaSeeder extends Seeder
{
    public function run(): void
    {
        $huesped = User::where('email', 'huesped@urbanosuites.com')->firstOrFail();
        $hoy = now()->startOfDay();

        // 1) Confirmada que ingresa hoy: sirve para probar el check-in
        $this->crearReserva($huesped, 'Doble Standard', $hoy->copy(), $hoy->copy()->addDays(2), 'Confirmada');

        // 2) En curso con consumos: sirve para probar consumos y check-out (la 301 queda ocupada)
        $enCurso = $this->crearReserva($huesped, 'Suite Matrimonial Deluxe', $hoy->copy()->subDay(), $hoy->copy()->addDays(2), 'En curso', '301');
        $this->crearConsumo($enCurso, 'Spa', 2);
        $this->crearConsumo($enCurso, 'Restaurante', 1);

        // 3) Finalizada que salió hoy: sirve para ver el comprobante (la 102 queda en limpieza)
        $finalizada = $this->crearReserva($huesped, 'Simple Ejecutiva', $hoy->copy()->subDays(3), $hoy->copy(), 'Finalizada', '102');
        $this->crearConsumo($finalizada, 'Lavandería', 1);
        $this->cobrarConsumos($finalizada);

        // 4) Cancelada: su pago de la estadía quedó reembolsado
        $this->crearReserva($huesped, 'Suite Familiar', $hoy->copy()->addDays(10), $hoy->copy()->addDays(13), 'Cancelada');
    }

    private function crearReserva(User $huesped, string $nombreTipo, Carbon $entrada, Carbon $salida, string $estado, ?string $numeroHabitacion = null): Reserva
    {
        $tipo = TipoHabitacion::where('nombre', $nombreTipo)->firstOrFail();
        $habitacion = $numeroHabitacion ? Habitacion::where('numero', $numeroHabitacion)->firstOrFail() : null;

        $noches = (int) $entrada->diffInDays($salida);
        $precioNoche = $tipo->precioPara($entrada->toDateString());
        $montos = Reserva::montosEstadia($precioNoche, $noches);

        $reserva = Reserva::create([
            'codigo'             => Reserva::generarCodigo(),
            'user_id'            => $huesped->id,
            'tipo_habitacion_id' => $tipo->id,
            'habitacion_id'      => $habitacion?->id,
            'fecha_entrada'      => $entrada->toDateString(),
            'fecha_salida'       => $salida->toDateString(),
            'huespedes'          => min(2, $tipo->capacidad),
            'noches'             => $noches,
            'precio_noche'       => $precioNoche,
            'total_estadia'      => $montos['total'],
            'estado'             => $estado,
        ]);

        // La estadía se paga completa al reservar (D2)
        Pago::create([
            'reserva_id' => $reserva->id,
            'concepto'   => 'Estadía',
            'monto'      => $montos['total'],
            'metodo'     => 'Tarjeta terminada en 1111',
            'referencia' => 'PAG-' . strtoupper(Str::random(8)),
            'estado'     => $estado === 'Cancelada' ? 'Reembolsado' : 'Aprobado',
        ]);

        // Estado de la habitación según cómo va la reserva
        if ($habitacion && $estado === 'En curso') {
            $habitacion->update(['estado' => 'Ocupada']);
        }
        if ($habitacion && $estado === 'Finalizada') {
            $habitacion->update(['estado' => 'Limpieza']);
        }

        return $reserva;
    }

    private function crearConsumo(Reserva $reserva, string $nombreServicio, int $cantidad): void
    {
        $servicio = ServicioAdicional::where('nombre', $nombreServicio)->firstOrFail();

        Consumo::create([
            'reserva_id'            => $reserva->id,
            'servicio_adicional_id' => $servicio->id,
            'cantidad'              => $cantidad,
            'precio_unitario'       => $servicio->precio,
        ]);
    }

    // El cobro de los consumos que se hace en el check-out: subtotal más IGV
    private function cobrarConsumos(Reserva $reserva): void
    {
        $reserva->load('consumos');
        $subtotal = $reserva->subtotalConsumos();

        Pago::create([
            'reserva_id' => $reserva->id,
            'concepto'   => 'Consumos',
            'monto'      => $subtotal + round($subtotal * Reserva::IGV, 2),
            'metodo'     => 'Efectivo',
            'referencia' => 'PAG-' . strtoupper(Str::random(8)),
            'estado'     => 'Aprobado',
        ]);
    }
}
