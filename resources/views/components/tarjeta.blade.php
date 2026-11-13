{{--
    Tarjeta con foto, título, texto y botón (Figma: "Tarjeta de suite" / "Tarjeta de experiencia").
    Uso:
    <x-tarjeta imagen="images/spa.png" titulo="Spa" :url="route('experiencias')" boton="VER EXPERIENCIAS">
        Masajes y tratamientos...
    </x-tarjeta>
--}}
@props([
    'imagen',
    'titulo',
    'url',
    'boton' => 'VER MÁS',
    'precio' => null,
])

<article class="flex flex-col overflow-hidden rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
    <x-foto :src="$imagen" :alt="$titulo" class="h-[200px] w-full lg:h-[220px]" />

    <div class="flex flex-1 flex-col justify-between gap-8 p-6">
        <div class="flex flex-col gap-2.5">
            <h3 class="font-serif text-[28px] font-semibold leading-[1.1] tracking-[-0.5px]">{{ $titulo }}</h3>

            @if ($precio)
                <p class="font-semibold text-dorado">{{ $precio }}</p>
            @endif

            @if ($slot->isNotEmpty())
                <p class="text-sm leading-[1.7] text-gris">{{ $slot }}</p>
            @endif
        </div>

        <a href="{{ $url }}"
           class="self-start rounded bg-dorado px-[18px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
            {{ $boton }}
        </a>
    </div>
</article>
