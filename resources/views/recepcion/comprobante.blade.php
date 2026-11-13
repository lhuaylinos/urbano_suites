{{-- Comprobante de pago: vista imprimible armada con la reserva, sus consumos y sus pagos (RF11) --}}
<x-layout titulo="Comprobante">
    {{-- Al imprimir se ocultan la barra de navegación, el pie y los botones --}}
    <style>
        @media print {
            header, footer, .no-imprimir { display: none !important; }
            body { background: #fff !important; }
        }
    </style>

    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <div class="no-imprimir flex flex-col gap-8">
            <x-encabezado-pagina etiqueta="RECEPCIÓN" titulo="Comprobante de pago" />

            <x-alerta />

            @include('recepcion._buscador')
        </div>

        <article class="flex max-w-[760px] flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <div class="flex items-start justify-between gap-4 border-b border-linea pb-5">
                <div>
                    <p class="font-serif text-2xl font-semibold">URBANO SUITES</p>
                    <p class="text-xs text-gris">Hotel boutique · Miraflores, Lima</p>
                </div>
                <div class="text-right text-sm">
                    <p class="text-[10px] tracking-[2px] text-dorado">COMPROBANTE N.º</p>
                    <p class="font-semibold">{{ $numero ?? '—' }}</p>
                    <p class="text-xs text-gris">Emitido el {{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <dl class="grid gap-3 text-sm sm:grid-cols-2">
                <div><dt class="text-gris">Reserva</dt><dd class="font-medium">{{ $reserva->codigo }}</dd></div>
                <div><dt class="text-gris">Huésped</dt><dd>{{ $reserva->user->name }} {{ $reserva->user->apellidos }}</dd></div>
                <div><dt class="text-gris">Documento</dt><dd>{{ $reserva->user->tipo_documento }} {{ $reserva->user->numero_documento }}</dd></div>
                <div><dt class="text-gris">Habitación</dt><dd>{{ $reserva->habitacion?->numero }} · {{ $reserva->tipo->nombre }}</dd></div>
                <div><dt class="text-gris">Entrada</dt><dd>{{ $reserva->fecha_entrada->format('d/m/Y') }}</dd></div>
                <div><dt class="text-gris">Salida</dt><dd>{{ $reserva->fecha_salida->format('d/m/Y') }}</dd></div>
            </dl>

            {{-- Estadía: se pagó completa al reservar (D2) --}}
            <div class="flex flex-col gap-2 border-t border-linea pt-4 text-sm">
                <p class="font-serif text-xl font-semibold">Estadía</p>
                <div class="flex justify-between">
                    <span class="text-gris">{{ $reserva->noches }} {{ $reserva->noches == 1 ? 'noche' : 'noches' }} × S/ {{ number_format($reserva->precio_noche, 2) }}</span>
                    <span>S/ {{ number_format($reserva->subtotalEstadia(), 2) }}</span>
                </div>
                <div class="flex justify-between"><span class="text-gris">IGV (18 %)</span><span>S/ {{ number_format($reserva->igvEstadia(), 2) }}</span></div>
                <div class="flex justify-between font-medium"><span>Total estadía</span><span>S/ {{ number_format($reserva->total_estadia, 2) }}</span></div>
            </div>

            {{-- Consumos: se cobran en el check-out --}}
            <div class="flex flex-col gap-2 border-t border-linea pt-4 text-sm">
                <p class="font-serif text-xl font-semibold">Consumos</p>
                @forelse ($reserva->consumos as $consumo)
                    <div class="flex justify-between">
                        <span>{{ $consumo->cantidad }} × {{ $consumo->servicio->nombre }}</span>
                        <span>S/ {{ number_format($consumo->cantidad * $consumo->precio_unitario, 2) }}</span>
                    </div>
                @empty
                    <p class="text-gris">No se registraron consumos.</p>
                @endforelse
                <div class="flex justify-between"><span class="text-gris">Subtotal consumos</span><span>S/ {{ number_format($subtotalConsumos, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-gris">IGV (18 %)</span><span>S/ {{ number_format($igvConsumos, 2) }}</span></div>
                <div class="flex justify-between font-medium"><span>Total consumos</span><span>S/ {{ number_format($subtotalConsumos + $igvConsumos, 2) }}</span></div>
            </div>

            <div class="flex justify-between border-t border-linea pt-4 text-lg">
                <span class="font-serif font-semibold">Total general</span>
                <span class="font-semibold text-dorado">S/ {{ number_format($reserva->total_estadia + $subtotalConsumos + $igvConsumos, 2) }}</span>
            </div>

            {{-- Pagos registrados --}}
            <div class="flex flex-col gap-2 border-t border-linea pt-4 text-sm">
                <p class="font-serif text-xl font-semibold">Pagos</p>
                @foreach ($reserva->pagos as $pago)
                    <div class="flex justify-between">
                        <span class="text-gris">{{ $pago->concepto }} · {{ $pago->metodo }} · {{ $pago->referencia }} · {{ $pago->estado }}</span>
                        <span>S/ {{ number_format($pago->monto, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </article>

        <div class="no-imprimir flex flex-wrap gap-4">
            <x-boton type="button" onclick="window.print()">IMPRIMIR COMPROBANTE</x-boton>
            <x-boton variante="borde" :href="route('recepcion.panel')">VOLVER AL PANEL</x-boton>
        </div>
    </section>
</x-layout>
