<x-layout titulo="Reporte de ocupación">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-10 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Reporte de ocupación">
            La ocupación de hoy, las reservas por estado y los ingresos del rango de fechas que elijas.
        </x-encabezado-pagina>

        <x-alerta />

        <a href="{{ route('admin.panel') }}" class="-mt-4 text-sm text-gris hover:text-dorado">← Volver al panel</a>

        <div class="flex flex-col gap-4">
            <h2 class="font-serif text-3xl font-semibold">Ocupación de hoy</h2>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="rounded-xl bg-white p-6 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                    <p class="text-[10px] tracking-[2px] text-dorado">OCUPACIÓN</p>
                    <p class="mt-2 font-serif text-5xl font-semibold">{{ $porcentaje }} %</p>
                    <p class="mt-1 text-xs text-gris">{{ $ocupadas }} de {{ $totalHabitaciones }} habitaciones activas</p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="border-b border-linea text-xs text-gris">
                        <tr>
                            <th class="px-5 py-4 font-medium">Tipo de habitación</th>
                            <th class="px-5 py-4 font-medium">Activas</th>
                            <th class="px-5 py-4 font-medium">Ocupadas</th>
                            <th class="px-5 py-4 font-medium">En limpieza</th>
                            <th class="px-5 py-4 font-medium">En mantenimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($porTipo as $fila)
                            <tr class="border-b border-linea last:border-0">
                                <td class="px-5 py-4 font-medium">{{ $fila['nombre'] }}</td>
                                <td class="px-5 py-4">{{ $fila['total'] }}</td>
                                <td class="px-5 py-4">{{ $fila['ocupadas'] }}</td>
                                <td class="px-5 py-4">{{ $fila['limpieza'] }}</td>
                                <td class="px-5 py-4">{{ $fila['mantenimiento'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-gris">No hay habitaciones activas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.reporte') }}"
              class="flex flex-col gap-4 rounded-xl bg-white p-6 shadow-[0_14px_40px_rgba(17,35,53,0.04)] md:flex-row md:items-end">
            <div class="md:w-[220px]">
                <x-campo label="Desde" name="desde" type="date" :value="$desde" />
            </div>
            <div class="md:w-[220px]">
                <x-campo label="Hasta" name="hasta" type="date" :value="$hasta" />
            </div>
            <x-boton class="h-[52px]">VER REPORTE</x-boton>
        </form>

        <div class="grid gap-8 lg:grid-cols-2">
            <div class="flex flex-col gap-5 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                <div>
                    <h2 class="font-serif text-2xl font-semibold">Reservas por estado</h2>
                    <p class="text-xs text-gris">Con ingreso entre el {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} y el {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}</p>
                </div>

                @foreach ($reservasPorEstado as $estado => $cantidad)
                    <div class="flex items-center justify-between border-b border-linea pb-3 text-sm last:border-0">
                        <x-estado :valor="$estado" />
                        <span class="font-medium">{{ $cantidad }}</span>
                    </div>
                @endforeach
            </div>

            <div class="flex flex-col gap-5 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                <div>
                    <h2 class="font-serif text-2xl font-semibold">Ingresos</h2>
                    <p class="text-xs text-gris">Pagos aprobados en el mismo rango, con IGV</p>
                </div>

                <dl class="flex flex-col gap-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gris">Estadías</dt><dd>S/ {{ number_format($ingresosEstadia, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Consumos</dt><dd>S/ {{ number_format($ingresosConsumos, 2) }}</dd></div>
                    <div class="flex justify-between border-t border-linea pt-3 text-lg">
                        <dt class="font-serif font-semibold">Total</dt>
                        <dd class="font-semibold text-dorado">S/ {{ number_format($ingresosEstadia + $ingresosConsumos, 2) }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>
</x-layout>
