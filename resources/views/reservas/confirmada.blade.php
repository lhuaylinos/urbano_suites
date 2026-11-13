{{-- A17 Reserva confirmada --}}
<x-layout titulo="Reserva confirmada">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="RESERVA CONFIRMADA" titulo="¡Tu reserva está lista!">
            Pago aprobado. Te esperamos en Miraflores; la habitación se te asigna en el check-in.
        </x-encabezado-pagina>

        <div class="flex max-w-[760px] flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <div>
                <p class="text-[10px] tracking-[2px] text-dorado">CÓDIGO DE RESERVA</p>
                <p class="font-serif text-4xl font-semibold">{{ $reserva->codigo }}</p>
            </div>

            <dl class="grid gap-3 text-sm sm:grid-cols-2">
                <div><dt class="text-gris">Habitación</dt><dd class="font-medium">{{ $reserva->tipo->nombre }}</dd></div>
                <div><dt class="text-gris">Huéspedes</dt><dd>{{ $reserva->huespedes }}</dd></div>
                <div><dt class="text-gris">Entrada</dt><dd>{{ $reserva->fecha_entrada->format('d/m/Y') }}</dd></div>
                <div><dt class="text-gris">Salida</dt><dd>{{ $reserva->fecha_salida->format('d/m/Y') }}</dd></div>
                <div><dt class="text-gris">Noches</dt><dd>{{ $reserva->noches }}</dd></div>
                <div><dt class="text-gris">Total pagado (con IGV)</dt><dd class="font-semibold text-dorado">S/ {{ number_format($reserva->total_estadia, 2) }}</dd></div>
            </dl>

            <div class="flex flex-wrap gap-4">
                <x-boton :href="route('mis-reservas')">VER MIS RESERVAS</x-boton>
                <x-boton variante="borde" :href="route('inicio')">VOLVER AL INICIO</x-boton>
            </div>
        </div>
    </section>
</x-layout>
