{{-- Formulario compartido por "crear" y "editar". Si llega $habitacion, estamos editando. --}}
@php $h = $habitacion ?? null; @endphp

<form method="POST" action="{{ $accion }}" class="flex max-w-[760px] flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
    @csrf
    @if ($h)
        @method('PUT')
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <x-campo label="Número de habitación" name="numero" :value="$h?->numero" placeholder="Ej. 101" />
        <x-campo label="Piso" name="piso" type="number" min="1" max="20" :value="$h?->piso" />
    </div>

    {{-- Llave foránea: se elige por nombre, se guarda el id --}}
    <x-selector label="Tipo de habitación" name="tipo_habitacion_id" :opciones="$tipos"
                :value="$h?->tipo_habitacion_id" placeholder="Selecciona un tipo" />

    <x-selector label="Estado" name="estado" :opciones="array_combine(\App\Models\Habitacion::ESTADOS, \App\Models\Habitacion::ESTADOS)"
                :value="$h?->estado ?? 'Disponible'" placeholder="Selecciona un estado" />

    <label class="flex items-center gap-3 text-sm">
        <input type="hidden" name="activo" value="0">
        <input type="checkbox" name="activo" value="1" class="size-4 accent-[#b68253]"
               @checked(old('activo', $h?->activo ?? true))>
        Habitación activa
    </label>

    <div class="flex gap-4">
        <button type="submit"
                class="rounded bg-dorado px-[22px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
            GUARDAR
        </button>
        <a href="{{ route('admin.habitaciones.index') }}"
           class="rounded border border-dorado/45 px-[22px] py-3 text-[11px] font-medium tracking-[1.2px] transition hover:bg-crema">
            CANCELAR
        </a>
    </div>
</form>
