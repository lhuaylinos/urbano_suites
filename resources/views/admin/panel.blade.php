{{-- C01 Panel del administrador. Solo entra el rol "administrador" (middleware role:administrador) --}}
<x-layout titulo="Panel de administración">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-10 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Panel del administrador">
            Desde aquí gestionarás el catálogo del hotel.
        </x-encabezado-pagina>

        {{-- Cada tarjeta se convertirá en enlace cuando su CRUD esté listo --}}
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach (['Tipos de habitación', 'Habitaciones', 'Servicios adicionales', 'Tarifas por temporada'] as $modulo)
                <div class="flex flex-col gap-3 rounded-xl bg-white p-6 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                    <p class="font-serif text-2xl font-semibold leading-tight">{{ $modulo }}</p>
                    <p class="text-xs text-gris">En desarrollo</p>
                </div>
            @endforeach
        </div>
    </section>
</x-layout>
