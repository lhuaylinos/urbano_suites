<?php

namespace Database\Seeders;

use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use Illuminate\Database\Seeder;

class HabitacionSeeder extends Seeder
{
    public function run(): void
    {
        // nombre del tipo => [piso, números de habitación]  (20 habitaciones en total)
        $plan = [
            'Simple Ejecutiva'         => [1, ['101', '102', '103', '104', '105']],
            'Doble Standard'           => [2, ['201', '202', '203', '204', '205', '206', '207']],
            'Suite Matrimonial Deluxe' => [3, ['301', '302', '303', '304']],
            'Suite Familiar'           => [4, ['401', '402', '403', '404']],
        ];

        foreach ($plan as $nombreTipo => [$piso, $numeros]) {
            $tipo = TipoHabitacion::where('nombre', $nombreTipo)->first();

            foreach ($numeros as $numero) {
                Habitacion::create([
                    'numero'             => $numero,
                    'piso'               => $piso,
                    'tipo_habitacion_id' => $tipo->id,
                    'estado'             => 'Disponible',
                ]);
            }
        }
    }
}
