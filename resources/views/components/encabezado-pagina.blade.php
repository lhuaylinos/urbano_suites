@props(['etiqueta', 'titulo'])

<div class="flex flex-col gap-3">
    <p class="text-[11px] font-medium tracking-wide text-dorado">— {{ $etiqueta }}</p>
    <h1 class="font-serif text-4xl font-bold leading-none tracking-[-1.5px] md:text-[56px]">{{ $titulo }}</h1>
    @if ($slot->isNotEmpty())
        <p class="max-w-[760px] leading-[1.8] text-gris">{{ $slot }}</p>
    @endif
</div>
