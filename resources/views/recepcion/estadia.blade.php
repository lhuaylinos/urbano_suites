{{-- B03 Check-in exitoso / B04 Servicios de la estadía --}}
<x-layout titulo="Servicios de la estadía">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="RECEPCIÓN" titulo="Servicios de la estadía">
            Habitación {{ $reserva->habitacion->numero }} · {{ $reserva->user->name }} {{ $reserva->user->apellidos }}
        </x-encabezado-pagina>

        <x-alerta />

        @include('recepcion._buscador')

        <a href="{{ route('recepcion.panel') }}" class="text-sm text-gris hover:text-dorado">← Volver al panel</a>

        <div class="grid gap-8 lg:grid-cols-2">
            {{-- Registrar un consumo --}}
            <form method="POST" action="{{ route('recepcion.consumos.guardar', $reserva) }}"
                  class="flex flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                @csrf
                <h2 class="font-serif text-2xl font-semibold">Registrar consumo</h2>

                <x-selector label="Servicio" name="servicio_adicional_id"
                            :opciones="$servicios->mapWithKeys(fn ($s) => [$s->id => $s->nombre . ' · S/ ' . number_format($s->precio, 2)])"
                            placeholder="Selecciona un servicio" />
                <x-campo label="Cantidad" name="cantidad" type="number" min="1" max="20" value="1" />

                <div><x-boton>AGREGAR A LA CUENTA</x-boton></div>
            </form>

            {{-- Consumos registrados --}}
            <div class="flex flex-col gap-5 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                <h2 class="font-serif text-2xl font-semibold">Consumos registrados</h2>

                @forelse ($reserva->consumos as $consumo)
                    <div class="flex justify-between border-b border-linea pb-3 text-sm last:border-0">
                        <span>{{ $consumo->cantidad }} × {{ $consumo->servicio->nombre }}</span>
                        <span>S/ {{ number_format($consumo->cantidad * $consumo->precio_unitario, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gris">Todavía no hay consumos.</p>
                @endforelse

                <div class="mt-2"><x-boton :href="route('recepcion.cuenta', $reserva)">IR AL CHECK-OUT</x-boton></div>
            </div>
        </div>
    </section>
</x-layout>
