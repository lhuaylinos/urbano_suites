{{-- Etiqueta de color para el estado de una reserva, habitación o pago. Uso: <x-estado :valor="$reserva->estado" /> --}}
@props(['valor'])

@php
    $colores = [
        'Confirmada'    => 'bg-emerald-50 text-emerald-700',
        'Disponible'    => 'bg-emerald-50 text-emerald-700',
        'Aprobado'      => 'bg-emerald-50 text-emerald-700',
        'Pendiente'     => 'bg-amber-50 text-amber-700',
        'Limpieza'      => 'bg-amber-50 text-amber-700',
        'En curso'      => 'bg-sky-50 text-sky-700',
        'Ocupada'       => 'bg-red-50 text-red-700',
        'Cancelada'     => 'bg-red-50 text-red-700',
        'Rechazado'     => 'bg-red-50 text-red-700',
        'Finalizada'    => 'bg-slate-100 text-slate-600',
        'Mantenimiento' => 'bg-slate-100 text-slate-600',
        'Reembolsado'   => 'bg-slate-100 text-slate-600',
    ];
@endphp

<span class="inline-block rounded-full px-3 py-1 text-xs {{ $colores[$valor] ?? 'bg-gray-100 text-gris' }}">{{ $valor }}</span>
