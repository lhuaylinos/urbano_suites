{{-- A12 Detalle de reserva --}}
<x-layout titulo="Detalle de reserva">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="MI CUENTA" :titulo="'Reserva ' . $reserva->codigo" />

        <x-alerta />

        <a href="{{ route('mis-reservas') }}" class="text-sm text-gris hover:text-dorado">← Volver a mis reservas</a>

        <div class="grid gap-8 lg:grid-cols-2">
            <div class="flex flex-col gap-5 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                <div class="flex items-center justify-between">
                    <h2 class="font-serif text-2xl font-semibold">Estadía</h2>
                    <x-estado :valor="$reserva->estado" />
                </div>

                <dl class="flex flex-col gap-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gris">Tipo de habitación</dt><dd class="font-medium">{{ $reserva->tipo->nombre }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Habitación asignada</dt><dd>{{ $reserva->habitacion?->numero ?? 'Se asigna en el check-in' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Entrada</dt><dd>{{ $reserva->fecha_entrada->format('d/m/Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Salida</dt><dd>{{ $reserva->fecha_salida->format('d/m/Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Huéspedes</dt><dd>{{ $reserva->huespedes }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Noches</dt><dd>{{ $reserva->noches }}</dd></div>
                    <div class="flex justify-between border-t border-linea pt-3"><dt class="text-gris">Precio por noche</dt><dd>S/ {{ number_format($reserva->precio_noche, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Subtotal</dt><dd>S/ {{ number_format($reserva->subtotalEstadia(), 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">IGV (18 %)</dt><dd>S/ {{ number_format($reserva->igvEstadia(), 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Total de la estadía</dt><dd class="font-semibold text-dorado">S/ {{ number_format($reserva->total_estadia, 2) }}</dd></div>
                </dl>

                @if ($reserva->estado === 'Confirmada')
                    <form method="POST" action="{{ route('reservas.cancelar', $reserva) }}"
                          onsubmit="return confirm('¿Cancelar esta reserva?');">
                        @csrf
                        <button type="submit" class="text-sm text-red-700 hover:underline">Cancelar reserva</button>
                    </form>
                @endif
            </div>

            <div class="flex flex-col gap-5 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                <h2 class="font-serif text-2xl font-semibold">Pagos</h2>

                @forelse ($reserva->pagos as $pago)
                    <div class="flex items-center justify-between border-b border-linea pb-3 text-sm last:border-0">
                        <div>
                            <p class="font-medium">{{ $pago->concepto }}</p>
                            <p class="text-xs text-gris">{{ $pago->metodo }} · {{ $pago->referencia }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span>S/ {{ number_format($pago->monto, 2) }}</span>
                            <x-estado :valor="$pago->estado" />
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gris">Sin pagos registrados.</p>
                @endforelse

                @if ($reserva->consumos->isNotEmpty())
                    <h3 class="mt-2 font-serif text-xl font-semibold">Consumos</h3>
                    @foreach ($reserva->consumos as $consumo)
                        <div class="flex justify-between text-sm">
                            <span>{{ $consumo->cantidad }} × {{ $consumo->servicio->nombre }}</span>
                            <span>S/ {{ number_format($consumo->cantidad * $consumo->precio_unitario, 2) }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
</x-layout>
