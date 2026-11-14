<x-layout titulo="Panel de recepción">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-10 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="RECEPCIÓN" titulo="Panel de recepción">
            Registra el check-in, los consumos y el check-out de las estadías.
        </x-encabezado-pagina>

        <x-alerta />

        @include('recepcion._buscador')

        <div class="flex flex-col gap-4">
            <h2 class="font-serif text-3xl font-semibold">Llegadas</h2>

            <div class="overflow-x-auto rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="border-b border-linea text-xs text-gris">
                        <tr>
                            <th class="px-5 py-4 font-medium">Código</th>
                            <th class="px-5 py-4 font-medium">Huésped</th>
                            <th class="px-5 py-4 font-medium">Tipo de habitación</th>
                            <th class="px-5 py-4 font-medium">Entrada</th>
                            <th class="px-5 py-4 font-medium">Salida</th>
                            <th class="px-5 py-4 font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($llegadas as $reserva)
                            <tr class="border-b border-linea last:border-0">
                                <td class="px-5 py-4 font-medium">{{ $reserva->codigo }}</td>
                                <td class="px-5 py-4">{{ $reserva->user->name }} {{ $reserva->user->apellidos }}</td>
                                <td class="px-5 py-4">{{ $reserva->tipo->nombre }}</td>
                                <td class="px-5 py-4">{{ $reserva->fecha_entrada->format('d/m/Y') }}</td>
                                <td class="px-5 py-4">{{ $reserva->fecha_salida->format('d/m/Y') }}</td>
                                <td class="px-5 py-4">
                                    @if ($reserva->fecha_entrada->isFuture())
                                        <span class="text-xs text-gris">Aún no llega</span>
                                    @else
                                        <a href="{{ route('recepcion.check-in', $reserva) }}" class="text-dorado hover:underline">Registrar check-in</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-8 text-center text-gris">No hay llegadas pendientes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <h2 class="font-serif text-3xl font-semibold">Huéspedes en el hotel</h2>

            <div class="overflow-x-auto rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="border-b border-linea text-xs text-gris">
                        <tr>
                            <th class="px-5 py-4 font-medium">Habitación</th>
                            <th class="px-5 py-4 font-medium">Huésped</th>
                            <th class="px-5 py-4 font-medium">Tipo</th>
                            <th class="px-5 py-4 font-medium">Salida</th>
                            <th class="px-5 py-4 font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enCasa as $reserva)
                            <tr class="border-b border-linea last:border-0">
                                <td class="px-5 py-4 font-medium">{{ $reserva->habitacion->numero }}</td>
                                <td class="px-5 py-4">{{ $reserva->user->name }} {{ $reserva->user->apellidos }}</td>
                                <td class="px-5 py-4">{{ $reserva->tipo->nombre }}</td>
                                <td class="px-5 py-4">{{ $reserva->fecha_salida->format('d/m/Y') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex gap-4">
                                        <a href="{{ route('recepcion.estadia', $reserva) }}" class="text-dorado hover:underline">Consumos</a>
                                        <a href="{{ route('recepcion.cuenta', $reserva) }}" class="text-dorado hover:underline">Check-out</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-gris">No hay huéspedes alojados ahora.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-layout>
