<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // el orden importa: cada seeder usa datos del anterior
        $this->call([
            RolSeeder::class,
            TipoHabitacionSeeder::class,
            HabitacionSeeder::class,
            ServicioAdicionalSeeder::class,
            TarifaSeeder::class,
            ReservaSeeder::class,
        ]);
    }
}
