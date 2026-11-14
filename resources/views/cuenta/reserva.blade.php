@php
    $simServicios = $servicios
        ->mapWithKeys(fn ($s) => [$s->id => ['nombre' => $s->nombre, 'precio' => (float) $s->precio]])
        ->all();
@endphp

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

        {{-- simulacion de servicios extra, solo en pantalla --}}
        <div class="flex flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <div>
                <h2 class="font-serif text-2xl font-semibold">Simula tus servicios extra</h2>
                <p class="mt-1 text-sm text-gris">Mira cuánto costaría agregar un servicio a tu estadía.</p>
            </div>

            @if ($servicios->isEmpty())
                <p class="text-sm text-gris">Por ahora no hay servicios extra disponibles.</p>
            @else
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="flex flex-col gap-6">
                        <x-selector label="Servicio" name="servicio"
                                    :opciones="$servicios->mapWithKeys(fn ($s) => [$s->id => $s->nombre . ' · S/ ' . number_format($s->precio, 2)])"
                                    placeholder="Selecciona un servicio" />
                        <x-campo label="Cantidad" name="cantidad" type="number" min="1" max="20" value="1" />
                    </div>

                    <div class="rounded border border-dorado/40 bg-crema p-4 text-sm">
                        <p class="text-[10px] font-medium tracking-wide text-dorado">COSTO ESTIMADO</p>

                        <p id="sim-mensaje" class="mt-2 text-gris">Elige un servicio para ver cuánto costaría.</p>

                        <dl id="sim-detalle" class="mt-3 hidden flex-col gap-1.5">
                            <div class="flex justify-between gap-4">
                                <dt id="sim-linea" class="text-gris"></dt>
                                <dd id="sim-subtotal"></dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-gris">IGV (18 %)</dt>
                                <dd id="sim-igv"></dd>
                            </div>
                            <div class="flex justify-between gap-4 border-t border-linea pt-2 font-semibold">
                                <dt>Total estimado</dt>
                                <dd id="sim-total" class="text-dorado"></dd>
                            </div>
                        </dl>

                        <p class="mt-3 text-[11px] text-gris">
                            Es solo una simulación: no se guarda ni se cobra nada. Los servicios reales se registran en recepción durante tu estadía.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if ($servicios->isNotEmpty())
        <script>
            const servicios = @json($simServicios, JSON_HEX_TAG);
            const IGV = {{ \App\Models\Reserva::IGV }};

            const selector = document.getElementById('servicio');
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
                detalle.classList.remove('flex');
            }

            // solo calcula en pantalla, no envia nada al servidor
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

                document.getElementById('sim-linea').textContent =
                    cantidad + ' × ' + servicio.nombre + ' (' + soles(servicio.precio) + ' c/u)';
                document.getElementById('sim-subtotal').textContent = soles(subtotal);
                document.getElementById('sim-igv').textContent = soles(igv);
                document.getElementById('sim-total').textContent = soles(redondear(subtotal + igv));

                mensaje.classList.add('hidden');
                detalle.classList.remove('hidden');
                detalle.classList.add('flex');
            }

            selector.addEventListener('change', simular);
            cantidadInput.addEventListener('input', simular);
            simular();
        </script>
    @endif
</x-layout>
