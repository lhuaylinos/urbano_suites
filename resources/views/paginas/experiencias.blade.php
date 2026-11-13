{{-- A05 Experiencias: los datos salen de la tabla servicios_adicionales --}}
<x-layout titulo="Experiencias">
    <div class="mx-auto flex max-w-[1440px] flex-col gap-9 px-6 py-14 lg:px-[80px]">

        <section class="flex flex-col gap-3.5">
            <p class="text-[10px] tracking-[2px] text-dorado">MOMENTOS QUE COMPLETAN TU VIAJE</p>
            <h1 class="font-serif text-5xl font-medium leading-[1.1] tracking-[0.5px] lg:text-[60px]">EXPERIENCIAS A TU RITMO</h1>
            <p class="text-sm leading-[1.7] text-gris">Bienestar, gastronomía y ciudad. Haz de tu estadía algo más que una noche de descanso.</p>
        </section>

        @php
            // Los 3 primeros van en una fila de 3 tarjetas; el resto, en filas de 2
            $primeros = $servicios->take(3);
            $resto = $servicios->slice(3);
        @endphp

        @foreach ([[$primeros, 'md:grid-cols-3'], [$resto, 'md:grid-cols-2']] as [$grupo, $columnas])
            @if ($grupo->isNotEmpty())
                <div class="grid gap-6 {{ $columnas }}">
                    @foreach ($grupo as $servicio)
                        <article class="flex flex-col overflow-hidden rounded border border-linea bg-white">
                            <x-foto :src="$servicio->imagen ?: 'images/sin-foto.png'" :alt="$servicio->nombre" class="h-[240px] w-full" />
                            <div class="flex flex-col gap-4 p-7">
                                <h2 class="font-serif text-[32px] font-medium leading-[1.1] tracking-[0.5px]">{{ $servicio->nombre }}</h2>
                                <p class="text-sm leading-[1.7] text-[#52606a]">{{ $servicio->descripcion }}</p>
                                <p class="text-[10px] tracking-[2px] text-dorado">DESDE S/ {{ number_format($servicio->precio, 2) }}</p>
                                @if ($servicio->nota)
                                    <p class="text-[11px] leading-[1.7] text-gris">{{ $servicio->nota }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        @endforeach

        <p class="rounded bg-[#f6eee5] p-6 text-sm leading-[1.7] text-[#52606a]">
            Se cargan a tu cuenta durante la estadía. Solicita cualquiera de estos servicios en recepción; nuestro equipo te ayudará a coordinar los detalles.
        </p>
    </div>
</x-layout>
