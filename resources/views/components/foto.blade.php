{{--
    Fotografía con respaldo: si el archivo no existe en public/, se ve un bloque beige en vez de una imagen rota.
    Uso: <x-foto src="images/spa.png" alt="Spa" class="h-[240px]" />
--}}
@props(['src', 'alt' => ''])

<div {{ $attributes->class(['overflow-hidden bg-linea']) }}>
    <img src="{{ asset($src) }}" alt="{{ $alt }}" class="size-full object-cover"
         onerror="this.style.visibility='hidden'">
</div>
