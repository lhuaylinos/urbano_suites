{{-- formulario de crear y editar --}}
@php $s = $servicio ?? null; @endphp

<form method="POST" action="{{ $accion }}" class="flex max-w-[760px] flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
    @csrf
    @if ($s)
        @method('PUT')
    @endif

    <x-campo label="Nombre" name="nombre" :value="$s?->nombre" placeholder="Ej. Spa" />

    <div class="flex flex-col gap-[9px]">
        <label for="descripcion" class="text-xs font-medium">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3"
                  placeholder="Describe brevemente el servicio"
                  @class([
                      'w-full rounded-[2px] border bg-[#fdfcfa] p-4 text-xs text-marino placeholder:text-[#7c8386] focus:border-dorado focus:outline-none',
                      'border-linea' => ! $errors->has('descripcion'),
                      'border-red-600' => $errors->has('descripcion'),
                  ])>{{ old('descripcion', $s?->descripcion) }}</textarea>
        @error('descripcion')
            <p class="text-xs text-red-700">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <x-campo label="Precio (S/)" name="precio" type="number" step="0.01" min="1" :value="$s?->precio" />
        <x-campo label="Nota" name="nota" :value="$s?->nota" placeholder="Ej. Sesión de 60 minutos" />
    </div>

    <x-campo label="Imagen" name="imagen" :value="$s?->imagen" placeholder="Ej. images/spa.png"
             ayuda="Ruta de la imagen dentro de la carpeta public (opcional)." />

    <label class="flex items-center gap-3 text-sm">
        {{-- el campo oculto manda 0 cuando la casilla no esta marcada --}}
        <input type="hidden" name="activo" value="0">
        <input type="checkbox" name="activo" value="1" class="size-4 accent-[#b68253]"
               @checked(old('activo', $s?->activo ?? true))>
        Servicio activo (se puede cargar a la cuenta)
    </label>

    <div class="flex gap-4">
        <button type="submit"
                class="rounded bg-dorado px-[22px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
            GUARDAR
        </button>
        <a href="{{ route('admin.servicios-adicionales.index') }}"
           class="rounded border border-dorado/45 px-[22px] py-3 text-[11px] font-medium tracking-[1.2px] transition hover:bg-crema">
            CANCELAR
        </a>
    </div>
</form>
