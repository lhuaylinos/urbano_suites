{{--
    Encabezado de las páginas internas (cuenta, paneles).
    Uso: <x-encabezado-pagina etiqueta="MI CUENTA" titulo="Mi perfil">Texto de apoyo</x-encabezado-pagina>
--}}
@props(['etiqueta', 'titulo'])

<div class="flex flex-col gap-3">
    <p class="text-[11px] font-medium tracking-wide text-dorado">— {{ $etiqueta }}</p>
    <h1 class="font-serif text-4xl font-bold leading-none tracking-[-1.5px] md:text-[56px]">{{ $titulo }}</h1>
    @if ($slot->isNotEmpty())
        <p class="max-w-[760px] leading-[1.8] text-gris">{{ $slot }}</p>
    @endif
</div>
