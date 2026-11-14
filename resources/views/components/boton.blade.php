@props(['href' => null, 'variante' => 'primario'])

@php
    $clases = 'inline-flex items-center justify-center rounded-[2px] px-6 py-3.5 text-[11px] tracking-[1.3px] transition ';
    $clases .= $variante === 'borde'
        ? 'border border-dorado/45 font-medium text-marino hover:bg-white'
        : 'bg-dorado font-semibold text-white hover:bg-marino';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $clases]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $clases]) }}>{{ $slot }}</button>
@endif
