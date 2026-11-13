<?php

namespace Database\Seeders;

use App\Models\ServicioAdicional;
use Illuminate\Database\Seeder;

class ServicioAdicionalSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            ['Spa', 'Masajes y tratamientos para recuperar el equilibrio en un ambiente de calma.', 120, 'Sesión de 60 minutos · Previa coordinación', 'images/spa.png'],
            ['Restaurante', 'Sabores peruanos y cocina contemporánea en una mesa para disfrutar sin prisa.', 65, 'Precio referencial por persona', 'images/restaurante.png'],
            ['Tour por Lima', 'Descubre la historia, la arquitectura y los rincones más auténticos de la ciudad.', 90, 'Recorrido guiado · Consulta los horarios', 'images/tours.png'],
            ['Room Service', 'Disfruta una selección de nuestra cocina en la comodidad de tu habitación.', 80, 'Precio referencial por pedido', 'images/room-service.png'],
            ['Lavandería', 'Cuidamos tus prendas para que puedas dedicar más tiempo a disfrutar tu viaje.', 35, 'Precio referencial por servicio', 'images/lavanderia.png'],
        ];

        foreach ($servicios as [$nombre, $descripcion, $precio, $nota, $imagen]) {
            ServicioAdicional::create([
                'nombre'      => $nombre,
                'descripcion' => $descripcion,
                'precio'      => $precio,
                'nota'        => $nota,
                'imagen'      => $imagen,
            ]);
        }
    }
}
