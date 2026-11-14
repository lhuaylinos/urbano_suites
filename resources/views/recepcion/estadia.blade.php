@php
    $subtotalActual = $reserva->subtotalConsumos();
    $igvActual = round($subtotalActual * \App\Models\Reserva::IGV, 2);
    $simServicios = $servicios
        ->mapWithKeys(fn ($s) => [$s->id => ['nombre' => $s->nombre, 'precio' => (float) $s->precio]])
        ->all();
@endphp

<x-layout titulo="Servicios de la estadía">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="RECEPCIÓN" titulo="Servicios de la estadía">
            Habitación {{ $reserva->habitacion->numero }} · {{ $reserva->user->name }} {{ $reserva->user->apellidos }}
        </x-encabezado-pagina>

        <x-alerta />

        <a href="{{ route('recepcion.panel') }}" class="text-sm text-gris hover:text-dorado">← Volver al panel</a>

        <div class="grid gap-8 lg:grid-cols-2">
            {{-- registrar consumo --}}
            <form method="POST" action="{{ route('recepcion.consumos.guardar', $reserva) }}"
                  class="flex flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                @csrf
                <h2 class="font-serif text-2xl font-semibold">Registrar consumo</h2>

                <x-selector label="Servicio" name="servicio_adicional_id"
                            :opciones="$servicios->mapWithKeys(fn ($s) => [$s->id => $s->nombre . ' · S/ ' . number_format($s->precio, 2)])"
                            placeholder="Selecciona un servicio" />
                <x-campo label="Cantidad" name="cantidad" type="number" min="1" max="20" value="1" />

                {{-- simulacion del costo (solo en pantalla) --}}
                <div class="rounded border border-dorado/40 bg-crema p-4 text-sm">
                    <p class="text-[10px] font-medium tracking-wide text-dorado">SIMULACIÓN DEL COSTO</p>

                    <p id="sim-mensaje" class="mt-2 text-gris">Elige un servicio para ver cuánto costaría.</p>

                    <div id="sim-detalle" class="mt-3 hidden">
                        <dl class="flex flex-col gap-1.5">
                            <div class="flex justify-between gap-4">
                                <dt id="sim-linea" class="text-gris"></dt>
                                <dd id="sim-subtotal"></dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-gris">IGV (18 %)</dt>
                                <dd id="sim-igv"></dd>
                            </div>
                            <div class="flex justify-between gap-4 font-semibold">
                                <dt>Este consumo</dt>
                                <dd id="sim-total" class="text-dorado"></dd>
                            </div>
                            <div class="mt-2 flex justify-between gap-4 border-t border-linea pt-2">
                                <dt class="text-gris">Total de la cuenta con este consumo</dt>
                                <dd id="sim-cuenta" class="font-semibold"></dd>
                            </div>
                        </dl>
                    </div>

                    <p class="mt-3 text-[11px] text-gris">
                        Es solo una simulación: no se guarda nada hasta que pulses AGREGAR A LA CUENTA.
                    </p>
                </div>

                <div><x-boton>AGREGAR A LA CUENTA</x-boton></div>
            </form>

            {{-- consumos ya registrados --}}
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

                @if ($reserva->consumos->isNotEmpty())
                    <dl class="flex flex-col gap-2 border-t border-linea pt-4 text-sm">
                        <div class="flex justify-between"><dt class="text-gris">Subtotal consumos</dt><dd>S/ {{ number_format($subtotalActual, 2) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gris">IGV (18 %)</dt><dd>S/ {{ number_format($igvActual, 2) }}</dd></div>
                        <div class="flex justify-between font-semibold"><dt>Total a cobrar en el check-out</dt><dd class="text-dorado">S/ {{ number_format($subtotalActual + $igvActual, 2) }}</dd></div>
                    </dl>
                @endif

                <div class="mt-2"><x-boton :href="route('recepcion.cuenta', $reserva)">IR AL CHECK-OUT</x-boton></div>
            </div>
        </div>
    </section>

    {{-- js de la simulacion --}}
    <script>
        const servicios = @json($simServicios, JSON_HEX_TAG);
        const subtotalActual = {{ $subtotalActual }};
        const IGV = {{ \App\Models\Reserva::IGV }};

        const selector = document.getElementById('servicio_adicional_id');
        const cantidadInput = document.getElementById('cantidad');
        const mensaje = document.getElementById('sim-mensaje');
        const detalle = document.getElementById('sim-detalle');

        const soles = (n) => 'S/ ' + n.toFixed(2);
        const redondear = (n) => Math.round((n + Number.EPSILON) * 100) / 100;

        function mostrarMensaje(texto, esError) {
            mensaje.textContent = texto;
            mensaje.classList.toggle('text-red-700', esError);
            mensaje.classList.toggle('text-gris', !esError);
            mensaje.classList.remove('hidden');
            detalle.classList.add('hidden');
        }

        function simular() {
            const servicio = servicios[selector.value];
            const cantidad = Number(cantidadInput.value);

            if (!servicio) {
                mostrarMensaje('Elige un servicio para ver cuánto costaría.', false);
                return;
            }

            if (cantidadInput.value === '' || !Number.isInteger(cantidad) || cantidad < 1 || cantidad > 20) {
                mostrarMensaje('La cantidad debe ser un número entero entre 1 y 20.', true);
                return;
            }

            const subtotal = redondear(cantidad * servicio.precio);
            const igv = redondear(subtotal * IGV);
            const cuentaSubtotal = subtotalActual + cantidad * servicio.precio;
            const cuentaTotal = redondear(cuentaSubtotal + redondear(cuentaSubtotal * IGV));

            document.getElementById('sim-linea').textContent =
                cantidad + ' × ' + servicio.nombre + ' (' + soles(servicio.precio) + ' c/u)';
            document.getElementById('sim-subtotal').textContent = soles(subtotal);
            document.getElementById('sim-igv').textContent = soles(igv);
            document.getElementById('sim-total').textContent = soles(redondear(subtotal + igv));
            document.getElementById('sim-cuenta').textContent = soles(cuentaTotal);

            mensaje.classList.add('hidden');
            detalle.classList.remove('hidden');
        }

        selector.addEventListener('change', simular);
        cantidadInput.addEventListener('input', simular);
        simular();
    </script>
</x-layout>
