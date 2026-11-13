{{--
    Diseño común de las páginas de error (403, 404, 419).
    Laravel las muestra solo: basta con que existan en resources/views/errors/
--}}
@props(['codigo', 'titulo'])

<x-layout :titulo="$titulo">
    <section class="mx-auto flex max-w-[760px] flex-col items-center gap-6 px-6 py-[96px] text-center">
        <p class="font-serif text-[96px] font-bold leading-none text-dorado">{{ $codigo }}</p>
        <h1 class="font-serif text-4xl font-bold leading-tight">{{ $titulo }}</h1>
        <p class="leading-[1.8] text-gris">{{ $slot }}</p>
        <a href="{{ route('inicio') }}"
           class="rounded bg-dorado px-[18px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
            VOLVER AL INICIO
        </a>
    </section>
</x-layout>
