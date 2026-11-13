<?php

namespace Database\Seeders;

use App\Models\Tarifa;
use App\Models\TipoHabitacion;
use Illuminate\Database\Seeder;

class TarifaSeeder extends Seeder
{
    public function run(): void
    {
        // tipo, temporada, días desde hoy hasta el inicio y hasta el fin, precio por noche
        // La primera está vigente hoy; las otras son para fechas futuras
        $tarifas = [
            ['Suite Familiar',            'Alta – Fin de año', -10, 60,  380],
            ['Doble Standard',            'Baja – Otoño',       90, 150, 150],
            ['Suite Matrimonial Deluxe',  'Alta – Verano',     120, 140, 290],
        ];

        foreach ($tarifas as [$nombreTipo, $temporada, $inicio, $fin, $precio]) {
            $tipo = TipoHabitacion::where('nombre', $nombreTipo)->firstOrFail();

            Tarifa::create([
                'tipo_habitacion_id' => $tipo->id,
                'temporada'          => $temporada,
                'fecha_inicio'       => now()->addDays($inicio)->toDateString(),
                'fecha_fin'          => now()->addDays($fin)->toDateString(),
                'precio_noche'       => $precio,
            ]);
        }
    }
}
