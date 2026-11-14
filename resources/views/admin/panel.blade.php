@php
    // texto => [ruta, descripcion]
    $mantenimientos = [
        'Tipos de habitación'   => ['admin.tipos-habitacion.index', 'Categorías, capacidad y precio base'],
        'Habitaciones'          => ['admin.habitaciones.index', 'Habitaciones físicas y su estado'],
        'Servicios adicionales' => ['admin.servicios-adicionales.index', 'Spa, restaurante, tours y más'],
        'Tarifas por temporada' => ['admin.tarifas.index', 'Precios por noche según la fecha'],
    ];

    $gestion = [
        'Usuarios y roles'     => ['admin.usuarios.index', 'Asigna el rol de cada usuario'],
        'Reporte de ocupación' => ['admin.reporte', 'Ocupación, reservas e ingresos'],
    ];
@endphp

<x-layout titulo="Panel de administración">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-10 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Panel del administrador">
            Desde aquí gestionas el catálogo del hotel, los usuarios y el reporte de ocupación.
        </x-encabezado-pagina>

        <x-alerta />

        {{-- mantenimientos (cruds) --}}
        <div class="flex flex-col gap-4">
            <h2 class="font-serif text-3xl font-semibold">Mantenimientos</h2>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($mantenimientos as $nombre => [$ruta, $descripcion])
                    <a href="{{ route($ruta) }}"
                       class="flex flex-col gap-3 rounded-xl bg-white p-6 shadow-[0_14px_40px_rgba(17,35,53,0.04)] transition hover:-translate-y-0.5 hover:shadow-[0_14px_40px_rgba(17,35,53,0.1)]">
                        <p class="font-serif text-2xl font-semibold leading-tight">{{ $nombre }}</p>
                        <p class="text-xs text-gris">{{ $descripcion }}</p>
                        <p class="text-xs text-dorado">GESTIONAR →</p>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- gestion: usuarios y reporte --}}
        <div class="flex flex-col gap-4">
            <h2 class="font-serif text-3xl font-semibold">Gestión</h2>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($gestion as $nombre => [$ruta, $descripcion])
                    <a href="{{ route($ruta) }}"
                       class="flex flex-col gap-3 rounded-xl bg-white p-6 shadow-[0_14px_40px_rgba(17,35,53,0.04)] transition hover:-translate-y-0.5 hover:shadow-[0_14px_40px_rgba(17,35,53,0.1)]">
                        <p class="font-serif text-2xl font-semibold leading-tight">{{ $nombre }}</p>
                        <p class="text-xs text-gris">{{ $descripcion }}</p>
                        <p class="text-xs text-dorado">ABRIR →</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-layout>
