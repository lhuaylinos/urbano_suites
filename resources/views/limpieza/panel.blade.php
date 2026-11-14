<x-layout titulo="Habitaciones por limpiar">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="LIMPIEZA" titulo="Habitaciones por limpiar">
            Después del check-out, cada habitación pasa a limpieza. Márcala como disponible cuando esté lista.
        </x-encabezado-pagina>

        <x-alerta />

        <div class="overflow-x-auto rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead class="border-b border-linea text-xs text-gris">
                    <tr>
                        <th class="px-5 py-4 font-medium">Habitación</th>
                        <th class="px-5 py-4 font-medium">Piso</th>
                        <th class="px-5 py-4 font-medium">Tipo</th>
                        <th class="px-5 py-4 font-medium">Estado</th>
                        <th class="px-5 py-4 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($habitaciones as $habitacion)
                        <tr class="border-b border-linea last:border-0">
                            <td class="px-5 py-4 font-medium">{{ $habitacion->numero }}</td>
                            <td class="px-5 py-4">{{ $habitacion->piso }}</td>
                            <td class="px-5 py-4">{{ $habitacion->tipo->nombre }}</td>
                            <td class="px-5 py-4"><x-estado :valor="$habitacion->estado" /></td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('limpieza.liberar', $habitacion) }}">
                                    @csrf
                                    <button type="submit" class="text-dorado hover:underline">Marcar como disponible</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-gris">No hay habitaciones pendientes de limpieza.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layout>
