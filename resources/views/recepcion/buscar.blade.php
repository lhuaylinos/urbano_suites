<x-layout titulo="Buscar reserva">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="RECEPCIÓN" titulo="Buscar reserva">
            Resultados para «{{ $texto }}»
        </x-encabezado-pagina>

        <x-alerta />

        @include('recepcion._buscador')

        <a href="{{ route('recepcion.panel') }}" class="text-sm text-gris hover:text-dorado">← Volver al panel</a>

        <div class="overflow-x-auto rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="border-b border-linea text-xs text-gris">
                    <tr>
                        <th class="px-5 py-4 font-medium">Código</th>
                        <th class="px-5 py-4 font-medium">Huésped</th>
                        <th class="px-5 py-4 font-medium">Documento</th>
                        <th class="px-5 py-4 font-medium">Tipo de habitación</th>
                        <th class="px-5 py-4 font-medium">Entrada</th>
                        <th class="px-5 py-4 font-medium">Salida</th>
                        <th class="px-5 py-4 font-medium">Estado</th>
                        <th class="px-5 py-4 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservas as $reserva)
                        <tr class="border-b border-linea last:border-0">
                            <td class="px-5 py-4 font-medium">{{ $reserva->codigo }}</td>
                            <td class="px-5 py-4">{{ $reserva->user->name }} {{ $reserva->user->apellidos }}</td>
                            <td class="px-5 py-4">{{ $reserva->user->tipo_documento }} {{ $reserva->user->numero_documento }}</td>
                            <td class="px-5 py-4">{{ $reserva->tipo->nombre }}</td>
                            <td class="px-5 py-4">{{ $reserva->fecha_entrada->format('d/m/Y') }}</td>
                            <td class="px-5 py-4">{{ $reserva->fecha_salida->format('d/m/Y') }}</td>
                            <td class="px-5 py-4"><x-estado :valor="$reserva->estado" /></td>
                            <td class="px-5 py-4">
                                @if ($reserva->estado === 'Confirmada')
                                    @if ($reserva->fecha_entrada->isFuture())
                                        <span class="text-xs text-gris">Aún no llega</span>
                                    @else
                                        <a href="{{ route('recepcion.check-in', $reserva) }}" class="text-dorado hover:underline">Registrar check-in</a>
                                    @endif
                                @elseif ($reserva->estado === 'En curso')
                                    <div class="flex gap-4">
                                        <a href="{{ route('recepcion.estadia', $reserva) }}" class="text-dorado hover:underline">Consumos</a>
                                        <a href="{{ route('recepcion.cuenta', $reserva) }}" class="text-dorado hover:underline">Check-out</a>
                                    </div>
                                @elseif ($reserva->estado === 'Finalizada')
                                    <a href="{{ route('recepcion.comprobante', $reserva) }}" class="text-dorado hover:underline">Ver comprobante</a>
                                @else
                                    <span class="text-xs text-gris">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-8 text-center text-gris">No se encontró ninguna reserva con ese dato.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layout>
