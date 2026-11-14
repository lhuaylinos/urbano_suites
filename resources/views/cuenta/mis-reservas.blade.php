<x-layout titulo="Mis reservas">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="MI CUENTA" titulo="Mis reservas">
            Aquí ves el historial y el estado de todas tus reservas.
        </x-encabezado-pagina>

        <x-alerta />

        <div class="overflow-x-auto rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="border-b border-linea text-xs text-gris">
                    <tr>
                        <th class="px-5 py-4 font-medium">Código</th>
                        <th class="px-5 py-4 font-medium">Habitación</th>
                        <th class="px-5 py-4 font-medium">Entrada</th>
                        <th class="px-5 py-4 font-medium">Salida</th>
                        <th class="px-5 py-4 font-medium">Total</th>
                        <th class="px-5 py-4 font-medium">Estado</th>
                        <th class="px-5 py-4 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservas as $reserva)
                        <tr class="border-b border-linea last:border-0">
                            <td class="px-5 py-4 font-medium">{{ $reserva->codigo }}</td>
                            <td class="px-5 py-4">{{ $reserva->tipo->nombre }}</td>
                            <td class="px-5 py-4">{{ $reserva->fecha_entrada->format('d/m/Y') }}</td>
                            <td class="px-5 py-4">{{ $reserva->fecha_salida->format('d/m/Y') }}</td>
                            <td class="px-5 py-4">S/ {{ number_format($reserva->total_estadia, 2) }}</td>
                            <td class="px-5 py-4"><x-estado :valor="$reserva->estado" /></td>
                            <td class="px-5 py-4">
                                <a href="{{ route('reservas.show', $reserva) }}" class="text-dorado hover:underline">Ver detalle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-gris">
                                Todavía no tienes reservas.
                                <a href="{{ route('inicio') }}#buscador" class="text-dorado hover:underline">Reserva tu primera estadía</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layout>
