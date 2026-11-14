<x-layout titulo="Check-out">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="RECEPCIÓN" titulo="Check-out y cuenta">
            Habitación {{ $reserva->habitacion->numero }} · {{ $reserva->user->name }} {{ $reserva->user->apellidos }}
        </x-encabezado-pagina>

        <x-alerta />

        @include('recepcion._buscador')

        <a href="{{ route('recepcion.panel') }}" class="text-sm text-gris hover:text-dorado">← Volver al panel</a>

        <div class="flex max-w-[760px] flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <p class="rounded bg-[#f6eee5] p-4 text-sm text-[#52606a]">
                La estadía ({{ $reserva->noches }} {{ $reserva->noches == 1 ? 'noche' : 'noches' }}, S/ {{ number_format($reserva->total_estadia, 2) }}) ya fue pagada al reservar.
            </p>

            @forelse ($reserva->consumos as $consumo)
                <div class="flex justify-between text-sm">
                    <span>{{ $consumo->cantidad }} × {{ $consumo->servicio->nombre }}</span>
                    <span>S/ {{ number_format($consumo->cantidad * $consumo->precio_unitario, 2) }}</span>
                </div>
            @empty
                <p class="text-sm text-gris">No se registraron consumos.</p>
            @endforelse

            <dl class="flex flex-col gap-2 border-t border-linea pt-4 text-sm">
                <div class="flex justify-between"><dt class="text-gris">Subtotal consumos</dt><dd>S/ {{ number_format($subtotal, 2) }}</dd></div>
                <div class="flex justify-between"><dt class="text-gris">IGV (18 %)</dt><dd>S/ {{ number_format($igv, 2) }}</dd></div>
                <div class="flex justify-between text-lg"><dt class="font-serif font-semibold">Total a cobrar</dt><dd class="font-semibold text-dorado">S/ {{ number_format($total, 2) }}</dd></div>
            </dl>

            <form method="POST" action="{{ route('recepcion.check-out', $reserva) }}" class="flex flex-col gap-6"
                  onsubmit="return confirm('¿Completar el check-out?');">
                @csrf
                <x-selector label="Método de pago" name="metodo"
                            :opciones="['Efectivo' => 'Efectivo', 'Tarjeta' => 'Tarjeta', 'Yape' => 'Yape']"
                            value="Efectivo" placeholder="Selecciona un método" />
                <div><x-boton>COBRAR Y COMPLETAR CHECK-OUT</x-boton></div>
            </form>
        </div>
    </section>
</x-layout>
