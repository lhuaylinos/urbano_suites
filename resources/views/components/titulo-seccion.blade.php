@props(['titulo'])

<div class="flex flex-col gap-2.5">
    <h2 class="font-serif text-4xl font-bold leading-none tracking-[-1.5px] md:text-[56px]">{{ $titulo }}</h2>
    <p class="max-w-[760px] leading-[1.8] text-gris">{{ $slot }}</p>
</div>
