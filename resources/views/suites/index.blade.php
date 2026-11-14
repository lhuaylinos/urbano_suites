<x-layout titulo="Suites">
    <div class="mx-auto flex max-w-[1440px] flex-col gap-10 px-6 py-16 lg:px-[80px]">

        <section class="flex flex-col gap-3.5">
            <p class="text-[10px] tracking-[2px] text-dorado">NUESTRAS HABITACIONES</p>
            <h1 class="font-serif text-5xl font-medium leading-[1.1] tracking-[0.5px] lg:text-[60px]">SUITES Y HABITACIONES</h1>
            <p class="text-sm leading-[1.7] text-gris">Elige el espacio ideal para tu estadía, desde una habitación ejecutiva hasta una suite familiar.</p>
        </section>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            @forelse ($tipos as $tipo)
                <x-tarjeta :imagen="$tipo->imagen ?: 'images/sin-foto.png'" :titulo="$tipo->nombre"
                           :precio="'S/ ' . number_format($tipo->precio_base, 0) . ' por noche'"
                           :url="route('suites.show', $tipo)" boton="VER SUITE">
                    {{ $tipo->capacidad }} {{ $tipo->capacidad == 1 ? 'huésped' : 'huéspedes' }}
                    @if ($tipo->tamano_m2) · {{ rtrim(rtrim($tipo->tamano_m2, '0'), '.') }} m² @endif
                </x-tarjeta>
            @empty
                <p class="text-gris">Por ahora no hay habitaciones disponibles para mostrar.</p>
            @endforelse
        </div>
    </div>
</x-layout>
