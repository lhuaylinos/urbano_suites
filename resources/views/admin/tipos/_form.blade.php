{{-- Formulario compartido por "crear" y "editar". Si llega $tipo, estamos editando. --}}
@php $t = $tipo ?? null; @endphp

<form method="POST" action="{{ $accion }}" class="flex max-w-[760px] flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
    @csrf
    @if ($t)
        @method('PUT')
    @endif

    <x-campo label="Nombre" name="nombre" :value="$t?->nombre" placeholder="Ej. Suite Familiar" />

    <div class="flex flex-col gap-[9px]">
        <label for="descripcion" class="text-xs font-medium">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3"
                  placeholder="Describe brevemente la habitación"
                  @class([
                      'w-full rounded-[2px] border bg-[#fdfcfa] p-4 text-xs text-marino placeholder:text-[#7c8386] focus:border-dorado focus:outline-none',
                      'border-linea' => ! $errors->has('descripcion'),
                      'border-red-600' => $errors->has('descripcion'),
                  ])>{{ old('descripcion', $t?->descripcion) }}</textarea>
        @error('descripcion')
            <p class="text-xs text-red-700">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <x-campo label="Capacidad (personas)" name="capacidad" type="number" min="1" max="10" :value="$t?->capacidad" />
        <x-campo label="Tamaño (m²)" name="tamano_m2" type="number" step="0.01" min="1" :value="$t?->tamano_m2" />
        <x-campo label="Precio base por noche (S/)" name="precio_base" type="number" step="0.01" min="1" :value="$t?->precio_base" />
    </div>

    <x-campo label="Imagen" name="imagen" :value="$t?->imagen" placeholder="Ej. images/suite-familiar.png"
             ayuda="Ruta de la imagen dentro de la carpeta public (opcional)." />

    <label class="flex items-center gap-3 text-sm">
        {{-- El campo oculto envía 0 cuando la casilla está desmarcada --}}
        <input type="hidden" name="activo" value="0">
        <input type="checkbox" name="activo" value="1" class="size-4 accent-[#b68253]"
               @checked(old('activo', $t?->activo ?? true))>
        Tipo activo (se puede reservar)
    </label>

    <div class="flex gap-4">
        <button type="submit"
                class="rounded bg-dorado px-[22px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
            GUARDAR
        </button>
        <a href="{{ route('admin.tipos-habitacion.index') }}"
           class="rounded border border-dorado/45 px-[22px] py-3 text-[11px] font-medium tracking-[1.2px] transition hover:bg-crema">
            CANCELAR
        </a>
    </div>
</form>
