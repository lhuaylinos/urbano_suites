<x-layout titulo="Habitaciones">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Habitaciones">
            Habitaciones físicas del hotel y su estado actual.
        </x-encabezado-pagina>

        <x-alerta />

        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('admin.panel') }}" class="text-sm text-gris hover:text-dorado">← Volver al panel</a>
            <a href="{{ route('admin.habitaciones.create') }}"
               class="rounded bg-dorado px-[18px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
                NUEVA HABITACIÓN
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="border-b border-linea text-xs text-gris">
                    <tr>
                        <th class="px-5 py-4 font-medium">Número</th>
                        <th class="px-5 py-4 font-medium">Piso</th>
                        <th class="px-5 py-4 font-medium">Tipo</th>
                        <th class="px-5 py-4 font-medium">Estado</th>
                        <th class="px-5 py-4 font-medium">Activa</th>
                        <th class="px-5 py-4 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($habitaciones as $habitacion)
                        <tr class="border-b border-linea last:border-0">
                            <td class="px-5 py-4 font-medium">{{ $habitacion->numero }}</td>
                            <td class="px-5 py-4">{{ $habitacion->piso }}</td>
                            {{-- Se muestra el nombre del tipo, no su id --}}
                            <td class="px-5 py-4">{{ $habitacion->tipo->nombre }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $colores = [
                                        'Disponible'    => 'bg-emerald-50 text-emerald-700',
                                        'Ocupada'       => 'bg-red-50 text-red-700',
                                        'Limpieza'      => 'bg-amber-50 text-amber-700',
                                        'Mantenimiento' => 'bg-slate-100 text-slate-600',
                                    ];
                                @endphp
                                <span class="rounded-full px-3 py-1 text-xs {{ $colores[$habitacion->estado] ?? 'bg-gray-100 text-gris' }}">
                                    {{ $habitacion->estado }}
                                </span>
                            </td>
                            <td class="px-5 py-4">{{ $habitacion->activo ? 'Sí' : 'No' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('admin.habitaciones.edit', $habitacion) }}" class="text-dorado hover:underline">Editar</a>

                                    @if ($habitacion->activo)
                                        <form method="POST" action="{{ route('admin.habitaciones.destroy', $habitacion) }}"
                                              onsubmit="return confirm('¿Desactivar esta habitación?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-700 hover:underline">Desactivar</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gris">Todavía no hay habitaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $habitaciones->links() }}
    </section>
</x-layout>
