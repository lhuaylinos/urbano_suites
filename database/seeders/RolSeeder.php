<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (['administrador', 'recepcionista', 'limpieza', 'huesped'] as $rol) {
            Role::firstOrCreate(['name' => $rol]);
        }

        $usuarios = [
            ['Ana',    'Torres Ríos',    '70000001', 'admin@urbanosuites.com',     'administrador'],
            ['Carlos', 'Mendoza Paz',    '70000002', 'recepcion@urbanosuites.com', 'recepcionista'],
            ['Rosa',   'Quispe Huamán',  '70000003', 'limpieza@urbanosuites.com',  'limpieza'],
            ['Diego',  'Salazar Vega',   '70000004', 'huesped@urbanosuites.com',   'huesped'],
        ];

        foreach ($usuarios as [$nombre, $apellidos, $dni, $email, $rol]) {
            $usuario = User::create([
                'name'             => $nombre,
                'apellidos'        => $apellidos,
                'tipo_documento'   => 'DNI',
                'numero_documento' => $dni,
                'telefono'         => '987654321',
                'email'            => $email,
                'password'         => 'password',
            ]);
            $usuario->assignRole($rol);
        }
    }
}