<x-layout>

    <section class="relative overflow-hidden">
        <svg class="pointer-events-none absolute -right-[100px] top-[30px] hidden lg:block"
             width="510" height="557" viewBox="0 0 510 557" fill="none" aria-hidden="true">
            <circle cx="255" cy="288" r="254.5" stroke="#b68253" stroke-opacity="0.25" />
            <circle cx="255" cy="288" r="150.5" stroke="#b68253" stroke-opacity="0.3" />
            <circle cx="255" cy="288" r="74.5" stroke="#b68253" stroke-opacity="0.35" />
            <line x1="254.5" y1="0" x2="254.5" y2="557" stroke="#b68253" stroke-opacity="0.2" />
            <circle cx="254.5" cy="137" r="5" fill="#b68253" />
        </svg>

        <div class="relative mx-auto max-w-[1440px] px-6 pb-12 pt-16 lg:px-[88px] lg:pb-[90px]">
            <p class="text-[11px] font-medium tracking-wide text-dorado">— HOTEL · MIRAFLORES, LIMA</p>

            <h1 class="mt-[26px] font-serif text-6xl font-bold leading-[0.98] md:text-8xl lg:text-[116px]">
                TU ESTADÍA,<br>
                <span class="text-dorado">A TU RITMO.</span>
            </h1>

            <p class="mt-[26px] max-w-[580px] leading-[1.8] text-gris">
                Descansa, trabaja, celebra y descubre Lima desde un espacio diseñado para hacer simple cada momento de tu viaje.
            </p>
        </div>
    </section>

    <section id="buscador" class="mx-auto max-w-[1440px] px-6 pb-[54px] pt-5 lg:px-[88px]">
        <x-alerta />

        <form method="GET" action="{{ route('buscar') }}"
              class="flex flex-col gap-6 rounded-xl bg-white p-6 shadow-[0_14px_40px_rgba(17,35,53,0.04)] lg:flex-row lg:items-center">

            <label class="flex flex-1 flex-col gap-3 lg:pl-3">
                <span class="text-[10px] font-medium text-dorado">HUÉSPEDES</span>
                <span class="relative">
                    <select name="huespedes"
                            class="w-full cursor-pointer appearance-none bg-transparent pr-8 text-base outline-none">
                        @foreach ([1 => '1 adulto', 2 => '2 adultos', 3 => '3 adultos', 4 => '4 adultos'] as $valor => $texto)
                            <option value="{{ $valor }}" @selected(request('huespedes', 2) == $valor)>{{ $texto }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-0 top-1/2 size-3.5 -translate-y-1/2 text-dorado" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path d="m3 4.5 3 3 3-3" />
                    </svg>
                </span>
            </label>

            <span class="hidden h-[46px] w-px bg-linea lg:block"></span>

            <label class="flex flex-col gap-3 lg:w-[214px] lg:px-3">
                <span class="text-[10px] font-medium text-dorado">FECHA DE ENTRADA</span>
                <input type="date" id="fecha_ingreso" name="fecha_ingreso" required
                       min="{{ now()->toDateString() }}" value="{{ request('fecha_ingreso') }}"
                       class="cursor-pointer bg-transparent text-base outline-none">
            </label>

            <span class="hidden h-[46px] w-px bg-linea lg:block"></span>

            <label class="flex flex-col gap-3 lg:w-[214px] lg:px-3">
                <span class="text-[10px] font-medium text-dorado">FECHA DE SALIDA</span>
                <input type="date" id="fecha_salida" name="fecha_salida" required
                       min="{{ now()->addDay()->toDateString() }}" value="{{ request('fecha_salida') }}"
                       class="cursor-pointer bg-transparent text-base outline-none">
            </label>

            <button type="submit"
                    class="h-[68px] rounded bg-dorado text-[11px] font-semibold text-white transition hover:bg-marino lg:w-[286px]">
                VER DISPONIBILIDAD
            </button>
        </form>

        @if ($errors->any())
            <ul class="mt-4 list-disc pl-5 text-sm text-red-700">
                @foreach ($errors->all() as $mensaje)
                    <li>{{ $mensaje }}</li>
                @endforeach
            </ul>
        @endif

        <script>
            const entrada = document.getElementById('fecha_ingreso');
            const salida = document.getElementById('fecha_salida');

            entrada.addEventListener('change', () => {
                if (!entrada.value) return;

                const diaSiguiente = new Date(entrada.value); // la fecha se lee en utc
                diaSiguiente.setUTCDate(diaSiguiente.getUTCDate() + 1);
                const minimo = diaSiguiente.toISOString().slice(0, 10); // formato aaaa-mm-dd

                salida.min = minimo;
                if (!salida.value || salida.value <= entrada.value) {
                    salida.value = minimo;
                }
            });
        </script>
    </section>

    <div class="mx-auto flex max-w-[1440px] flex-col gap-[72px] px-6 py-[72px] lg:px-[88px]">

        <section class="flex flex-col gap-6">
            <x-titulo-seccion titulo="Nuestras suites">
                Espacios pensados para descansar, trabajar y disfrutar de la ciudad con todo el confort que necesitas.
            </x-titulo-seccion>

            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($suites as $suite)
                    <x-tarjeta :imagen="$suite->imagen ?: 'images/sin-foto.png'" :titulo="$suite->nombre"
                               :precio="'S/ ' . number_format($suite->precio_base, 0) . ' por noche'"
                               :url="route('suites.show', $suite)" boton="VER SUITE" />
                @endforeach
            </div>
        </section>

        <section class="flex flex-col gap-6">
            <x-titulo-seccion titulo="Experiencias">
                Descubre los espacios y servicios que te ayudarán a relajarte, disfrutar y vivir la ciudad con más profundidad.
            </x-titulo-seccion>

            <div class="grid gap-6 md:grid-cols-3">
                <x-tarjeta imagen="images/spa.png" titulo="Spa" :url="route('experiencias')" boton="VER EXPERIENCIAS">
                    Masajes, tratamientos y un espacio pensado para el bienestar y la relajación.
                </x-tarjeta>
                <x-tarjeta imagen="images/restaurante.png" titulo="Restaurante" :url="route('experiencias')" boton="VER EXPERIENCIAS">
                    Cocina contemporánea y momentos gastronómicos diseñados para sorprender.
                </x-tarjeta>
                <x-tarjeta imagen="images/tours.png" titulo="Tours" :url="route('experiencias')" boton="VER EXPERIENCIAS">
                    Rutas y experiencias locales para conocer la ciudad desde otra perspectiva.
                </x-tarjeta>
            </div>
        </section>

        <section class="flex flex-col gap-6">
            <x-titulo-seccion titulo="Reserva directo con nosotros">
                Al reservar directamente, accedes a beneficios exclusivos y una atención más personalizada.
            </x-titulo-seccion>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="flex flex-col gap-4 rounded-xl bg-white p-6 shadow-[0_14px_40px_rgba(17,35,53,0.04)] lg:min-h-[220px]">
                    <h3 class="font-serif text-[28px] font-semibold leading-[1.1] tracking-[-0.5px]">Mejor tarifa garantizada</h3>
                    <p class="text-sm leading-[1.7] text-gris">Si encuentras una tarifa más baja, te igualamos el precio y te damos una mejora adicional.</p>
                </div>
                <div class="flex flex-col gap-4 rounded-xl bg-white p-6 shadow-[0_14px_40px_rgba(17,35,53,0.04)] lg:min-h-[220px]">
                    <h3 class="font-serif text-[28px] font-semibold leading-[1.1] tracking-[-0.5px]">Check-in flexible</h3>
                    <p class="text-sm leading-[1.7] text-gris">Sujeto a disponibilidad, podemos coordinar tu llegada con mayor flexibilidad.</p>
                </div>
                <div class="flex flex-col gap-4 rounded-xl bg-white p-6 shadow-[0_14px_40px_rgba(17,35,53,0.04)] lg:min-h-[220px]">
                    <h3 class="font-serif text-[28px] font-semibold leading-[1.1] tracking-[-0.5px]">Atención 24 horas</h3>
                    <p class="text-sm leading-[1.7] text-gris">Nuestro equipo está disponible para ayudarte antes, durante y después de tu estadía.</p>
                </div>
            </div>
        </section>
    </div>

</x-layout>
