<x-layout titulo="Tarifas por temporada">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Tarifas por temporada">
            Si hay una tarifa vigente en la fecha de ingreso, se usa su precio; si no, el precio base del tipo.
        </x-encabezado-pagina>

        <x-alerta />

        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('admin.panel') }}" class="text-sm text-gris hover:text-dorado">← Volver al panel</a>
            <a href="{{ route('admin.tarifas.create') }}"
               class="rounded bg-dorado px-[18px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
                NUEVA TARIFA
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <table class="w-full min-w-[820px] text-left text-sm">
                <thead class="border-b border-linea text-xs text-gris">
                    <tr>
                        <th class="px-5 py-4 font-medium">Tipo de habitación</th>
                        <th class="px-5 py-4 font-medium">Temporada</th>
                        <th class="px-5 py-4 font-medium">Desde</th>
                        <th class="px-5 py-4 font-medium">Hasta</th>
                        <th class="px-5 py-4 font-medium">Precio por noche</th>
                        <th class="px-5 py-4 font-medium">Estado</th>
                        <th class="px-5 py-4 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tarifas as $tarifa)
                        <tr class="border-b border-linea last:border-0">
                            {{-- Se muestra el nombre del tipo, no su id --}}
                            <td class="px-5 py-4 font-medium">{{ $tarifa->tipo->nombre }}</td>
                            <td class="px-5 py-4">{{ $tarifa->temporada }}</td>
                            <td class="px-5 py-4">{{ $tarifa->fecha_inicio->format('d/m/Y') }}</td>
                            <td class="px-5 py-4">{{ $tarifa->fecha_fin->format('d/m/Y') }}</td>
                            <td class="px-5 py-4">S/ {{ number_format($tarifa->precio_noche, 2) }}</td>
                            <td class="px-5 py-4">
                                @if ($tarifa->estaVigente())
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs text-emerald-700">Vigente hoy</span>
                                @elseif ($tarifa->activo)
                                    <span class="rounded-full bg-sky-50 px-3 py-1 text-xs text-sky-700">Activa</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gris">Inactiva</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('admin.tarifas.edit', $tarifa) }}" class="text-dorado hover:underline">Editar</a>

                                    @if ($tarifa->activo)
                                        <form method="POST" action="{{ route('admin.tarifas.destroy', $tarifa) }}"
                                              onsubmit="return confirm('¿Desactivar esta tarifa?');">
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
                            <td colspan="7" class="px-5 py-8 text-center text-gris">Todavía no hay tarifas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $tarifas->links() }}
    </section>
</x-layout>
