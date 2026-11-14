@php
    $usuario = auth()->user();
    $nombresRoles = [
        'administrador' => 'Administrador',
        'recepcionista' => 'Recepción',
        'limpieza'      => 'Personal de limpieza',
        'huesped'       => 'Huésped',
    ];
@endphp

<x-layout titulo="Mi perfil">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-10 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="MI CUENTA" titulo="Mi perfil">
            Estos son los datos con los que te registraste en Urbano Suites.
        </x-encabezado-pagina>

        {{-- datos del usuario (solo lectura) --}}
        <div class="max-w-[760px] rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <div class="mb-6 flex items-center gap-4 border-b border-linea pb-6">
                <span class="flex size-14 items-center justify-center rounded-full bg-dorado font-serif text-2xl font-semibold text-white">
                    {{ mb_substr($usuario->name, 0, 1) }}
                </span>
                <div>
                    <p class="font-serif text-[28px] font-semibold leading-tight">{{ $usuario->name }} {{ $usuario->apellidos }}</p>
                    <p class="text-[11px] font-medium tracking-wide text-dorado">
                        {{ $usuario->getRoleNames()->map(fn ($rol) => $nombresRoles[$rol] ?? $rol)->join(', ') }}
                    </p>
                </div>
            </div>

            <dl class="grid gap-6 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-[10px] font-medium text-dorado">CORREO ELECTRÓNICO</dt>
                    <dd class="mt-1">{{ $usuario->email }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-medium text-dorado">DOCUMENTO</dt>
                    <dd class="mt-1">{{ $usuario->tipo_documento }} {{ $usuario->numero_documento }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-medium text-dorado">TELÉFONO</dt>
                    <dd class="mt-1">{{ $usuario->telefono ?? 'No registrado' }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-medium text-dorado">MIEMBRO DESDE</dt>
                    <dd class="mt-1">{{ $usuario->created_at->format('d/m/Y') }}</dd>
                </div>
            </dl>
        </div>
    </section>
</x-layout>
