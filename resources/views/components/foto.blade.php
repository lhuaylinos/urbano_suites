@props(['src', 'alt' => ''])

<div {{ $attributes->class(['overflow-hidden bg-linea']) }}>
    <img src="{{ asset($src) }}" alt="{{ $alt }}" class="size-full object-cover"
         onerror="this.style.visibility='hidden'">
</div>
