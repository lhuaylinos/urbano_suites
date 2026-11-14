<x-layout titulo="Editar usuario">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Editar usuario" />

        <x-alerta />

        <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}"
              class="flex max-w-[760px] flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            @csrf
            @method('PUT')

            {{-- datos de solo lectura --}}
            <dl class="grid gap-3 text-sm sm:grid-cols-2">
                <div><dt class="text-gris">Nombre</dt><dd class="font-medium">{{ $usuario->name }} {{ $usuario->apellidos }}</dd></div>
                <div><dt class="text-gris">Correo</dt><dd>{{ $usuario->email }}</dd></div>
                <div><dt class="text-gris">Documento</dt><dd>{{ $usuario->tipo_documento }} {{ $usuario->numero_documento }}</dd></div>
                <div><dt class="text-gris">Teléfono</dt><dd>{{ $usuario->telefono ?: '—' }}</dd></div>
            </dl>

            {{-- lo unico que se cambia: rol y estado --}}
            <x-selector label="Rol" name="rol" :opciones="$roles"
                        :value="$usuario->roles->first()?->name" placeholder="Selecciona un rol" />

            <label class="flex items-center gap-3 text-sm">
                <input type="hidden" name="activo" value="0">
                <input type="checkbox" name="activo" value="1" class="size-4 accent-[#b68253]"
                       @checked(old('activo', $usuario->activo))>
                Usuario activo (puede iniciar sesión)
            </label>

            <div class="flex gap-4">
                <button type="submit"
                        class="rounded bg-dorado px-[22px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
                    GUARDAR
                </button>
                <a href="{{ route('admin.usuarios.index') }}"
                   class="rounded border border-dorado/45 px-[22px] py-3 text-[11px] font-medium tracking-[1.2px] transition hover:bg-crema">
                    CANCELAR
                </a>
            </div>
        </form>
    </section>
</x-layout>
