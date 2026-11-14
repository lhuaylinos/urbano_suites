{{-- A02 El hotel --}}
<x-layout titulo="El hotel">
    <div class="mx-auto flex max-w-[1440px] flex-col gap-12 px-6 py-16 lg:px-[80px]">

        {{-- Historia y fotografía --}}
        <section class="flex flex-col items-center gap-10 lg:flex-row lg:gap-16">
            <div class="flex flex-col gap-6 lg:w-[540px] lg:shrink-0">
                <div class="flex flex-col gap-3.5">
                    <p class="text-[10px] tracking-[2px] text-dorado">— HOTEL · MIRAFLORES, LIMA</p>
                    <h1 class="font-serif text-5xl font-medium leading-[1.1] tracking-[0.5px] lg:text-[64px]">UN REFUGIO EN EL RITMO DE LIMA</h1>
                    <p class="text-sm leading-[1.7] text-gris">Hospitalidad cercana, diseño sereno y una ciudad por descubrir.</p>
                </div>
                <p class="text-sm leading-[1.7] text-[#52606a]">
                    Urbano Suites nace de una idea sencilla: hacer que cada viaje se sienta propio. En el corazón de Miraflores, reunimos el carácter de Lima y la calma de un hotel boutique.
                </p>
                <p class="text-sm leading-[1.7] text-[#52606a]">
                    Nuestros espacios acompañan tus días de descanso, trabajo y encuentro. Un equipo atento cuida los detalles, desde tu llegada hasta el último momento de tu estadía.
                </p>
                <div>
                    <x-boton :href="route('inicio') . '#buscador'">RESERVAR AHORA</x-boton>
                </div>
            </div>

            <x-foto src="images/elhotel-lobby.png" alt="Recepción de Urbano Suites" class="h-[320px] w-full rounded lg:h-[480px] lg:flex-1" />
        </section>

        {{-- El hotel en cifras --}}
        <section class="grid gap-8 border-y border-linea py-8 md:grid-cols-3 md:gap-10">
            @foreach ([
                ['UBICACIÓN PRIVILEGIADA', 'Miraflores', 'A pasos del malecón y de la vida cultural de Lima.'],
                ['DESCANSO A TU MEDIDA', '20 habitaciones', 'Cuatro categorías para cada forma de viajar.'],
                ['ATENCIÓN PERSONALIZADA', '24 horas', 'Siempre cerca para acompañar tu estadía.'],
            ] as [$etiqueta, $titulo, $texto])
                <div class="flex flex-col gap-3">
                    <p class="text-[10px] tracking-[2px] text-dorado">{{ $etiqueta }}</p>
                    <p class="font-serif text-4xl font-medium leading-[1.1]">{{ $titulo }}</p>
                    <p class="text-[13px] leading-[1.7] text-[#52606a]">{{ $texto }}</p>
                </div>
            @endforeach
        </section>

        {{-- Galería --}}
        <section class="flex flex-col gap-6">
            <div class="flex flex-col gap-3.5">
                <p class="text-[10px] tracking-[2px] text-dorado">ESPACIOS PARA SENTIRTE BIEN</p>
                <h2 class="font-serif text-4xl font-medium leading-[1.1] tracking-[0.5px] lg:text-[48px]">CONOCE NUESTROS ESPACIOS</h2>
                <p class="text-sm leading-[1.7] text-gris">Luz natural, materiales cálidos y pequeños detalles que hacen la diferencia.</p>
            </div>

            <div class="grid grid-cols-2 gap-5 lg:grid-cols-4">
                @foreach ([
                    ['images/elhotel-eldescanso.png', 'El descanso'],
                    ['images/elhotel-losencuentros.png', 'Los encuentros'],
                    ['images/elhotel-lagastronomia.png', 'La gastronomía'],
                    ['images/elhotel-elbienestar.png', 'El bienestar'],
                ] as [$imagen, $leyenda])
                    <figure class="flex flex-col gap-3">
                        <x-foto :src="$imagen" :alt="$leyenda" class="h-[230px] rounded" />
                        <figcaption class="text-xs text-gris">{{ $leyenda }}</figcaption>
                    </figure>
                @endforeach
            </div>
        </section>
    </div>
</x-layout>
