<x-layout titulo="Servicios adicionales">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Servicios adicionales">
            Spa, restaurante, tours y otros servicios que recepción carga a la cuenta del huésped.
        </x-encabezado-pagina>

        <x-alerta />

        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('admin.panel') }}" class="text-sm text-gris hover:text-dorado">← Volver al panel</a>
            <a href="{{ route('admin.servicios-adicionales.create') }}"
               class="rounded bg-dorado px-[18px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
                NUEVO SERVICIO
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="border-b border-linea text-xs text-gris">
                    <tr>
                        <th class="px-5 py-4 font-medium">Nombre</th>
                        <th class="px-5 py-4 font-medium">Precio</th>
                        <th class="px-5 py-4 font-medium">Nota</th>
                        <th class="px-5 py-4 font-medium">Estado</th>
                        <th class="px-5 py-4 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($servicios as $servicio)
                        <tr class="border-b border-linea last:border-0">
                            <td class="px-5 py-4 font-medium">{{ $servicio->nombre }}</td>
                            <td class="px-5 py-4">S/ {{ number_format($servicio->precio, 2) }}</td>
                            <td class="px-5 py-4 text-gris">{{ $servicio->nota ?: '—' }}</td>
                            <td class="px-5 py-4">
                                @if ($servicio->activo)
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs text-emerald-700">Activo</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gris">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('admin.servicios-adicionales.edit', $servicio) }}" class="text-dorado hover:underline">Editar</a>

                                    @if ($servicio->activo)
                                        <form method="POST" action="{{ route('admin.servicios-adicionales.destroy', $servicio) }}"
                                              onsubmit="return confirm('¿Desactivar este servicio?');">
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
                            <td colspan="5" class="px-5 py-8 text-center text-gris">Todavía no hay servicios adicionales registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $servicios->links() }}
    </section>
</x-layout>
