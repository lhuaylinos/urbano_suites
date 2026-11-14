{{-- formulario de crear y editar --}}
@php $t = $tarifa ?? null; @endphp

<form method="POST" action="{{ $accion }}" class="flex max-w-[760px] flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
    @csrf
    @if ($t)
        @method('PUT')
    @endif

    <x-selector label="Tipo de habitación" name="tipo_habitacion_id" :opciones="$tipos"
                :value="$t?->tipo_habitacion_id" placeholder="Selecciona un tipo" />

    <x-campo label="Temporada" name="temporada" :value="$t?->temporada" placeholder="Ej. Alta – Fiestas Patrias" />

    <div class="grid gap-6 md:grid-cols-3">
        <x-campo label="Fecha de inicio" name="fecha_inicio" type="date" :value="$t?->fecha_inicio?->toDateString()" />
        <x-campo label="Fecha de fin" name="fecha_fin" type="date" :value="$t?->fecha_fin?->toDateString()" />
        <x-campo label="Precio por noche (S/)" name="precio_noche" type="number" step="0.01" min="1" :value="$t?->precio_noche" />
    </div>

    <label class="flex items-center gap-3 text-sm">
        <input type="hidden" name="activo" value="0">
        <input type="checkbox" name="activo" value="1" class="size-4 accent-[#b68253]"
               @checked(old('activo', $t?->activo ?? true))>
        Tarifa activa
    </label>

    <div class="flex gap-4">
        <button type="submit"
                class="rounded bg-dorado px-[22px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
            GUARDAR
        </button>
        <a href="{{ route('admin.tarifas.index') }}"
           class="rounded border border-dorado/45 px-[22px] py-3 text-[11px] font-medium tracking-[1.2px] transition hover:bg-crema">
            CANCELAR
        </a>
    </div>
</form>
