<x-layout titulo="Tipos de habitación">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Tipos de habitación">
            Categorías de habitación que el huésped puede reservar.
        </x-encabezado-pagina>

        <x-alerta />

        {{-- volver y boton de alta --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('admin.panel') }}" class="text-sm text-gris hover:text-dorado">← Volver al panel</a>
            <a href="{{ route('admin.tipos-habitacion.create') }}"
               class="rounded bg-dorado px-[18px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
                NUEVO TIPO
            </a>
        </div>

        {{-- tabla del listado --}}
        <div class="overflow-x-auto rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="border-b border-linea text-xs text-gris">
                    <tr>
                        <th class="px-5 py-4 font-medium">Nombre</th>
                        <th class="px-5 py-4 font-medium">Capacidad</th>
                        <th class="px-5 py-4 font-medium">Tamaño</th>
                        <th class="px-5 py-4 font-medium">Precio base</th>
                        <th class="px-5 py-4 font-medium">Estado</th>
                        <th class="px-5 py-4 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tipos as $tipo)
                        <tr class="border-b border-linea last:border-0">
                            <td class="px-5 py-4 font-medium">{{ $tipo->nombre }}</td>
                            <td class="px-5 py-4">{{ $tipo->capacidad }} {{ $tipo->capacidad == 1 ? 'persona' : 'personas' }}</td>
                            <td class="px-5 py-4">{{ $tipo->tamano_m2 ? $tipo->tamano_m2 . ' m²' : '—' }}</td>
                            <td class="px-5 py-4">S/ {{ number_format($tipo->precio_base, 2) }}</td>
                            <td class="px-5 py-4">
                                @if ($tipo->activo)
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs text-emerald-700">Activo</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gris">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                {{-- editar y desactivar (baja logica) --}}
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('admin.tipos-habitacion.edit', $tipo) }}" class="text-dorado hover:underline">Editar</a>

                                    @if ($tipo->activo)
                                        <form method="POST" action="{{ route('admin.tipos-habitacion.destroy', $tipo) }}"
                                              onsubmit="return confirm('¿Desactivar este tipo de habitación?');">
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
                            <td colspan="6" class="px-5 py-8 text-center text-gris">Todavía no hay tipos de habitación registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- paginacion --}}
        {{ $tipos->links() }}
    </section>
</x-layout>
