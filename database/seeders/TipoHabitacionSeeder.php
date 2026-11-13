<?php

namespace Database\Seeders;

use App\Models\TipoHabitacion;
use Illuminate\Database\Seeder;

class TipoHabitacionSeeder extends Seeder
{
    public function run(): void
    {
        TipoHabitacion::create([
            'nombre'      => 'Simple Ejecutiva',
            'descripcion' => 'Habitación individual con escritorio de trabajo, ideal para viajes de negocios.',
            'capacidad'   => 1,
            'tamano_m2'   => 18,
            'precio_base' => 140.00,
            'imagen'      => 'images/simple-ejecutiva.png',
        ]);

        TipoHabitacion::create([
            'nombre'      => 'Doble Standard',
            'descripcion' => 'Dos camas de plaza y media, baño privado y vista a la ciudad.',
            'capacidad'   => 2,
            'tamano_m2'   => 24,
            'precio_base' => 180.00,
            'imagen'      => 'images/doble-standard.png',
        ]);

        TipoHabitacion::create([
            'nombre'      => 'Suite Matrimonial Deluxe',
            'descripcion' => 'Cama king, sala de estar y tina de hidromasaje.',
            'capacidad'   => 2,
            'tamano_m2'   => 35,
            'precio_base' => 250.00,
            'imagen'      => 'images/suite-matrimonial-deluxe.png',
        ]);

        TipoHabitacion::create([
            'nombre'      => 'Suite Familiar',
            'descripcion' => 'Dos ambientes, una cama king y dos camas individuales.',
            'capacidad'   => 4,
            'tamano_m2'   => 45,
            'precio_base' => 320.00,
            'imagen'      => 'images/suite-familiar.png',
        ]);
    }
}
