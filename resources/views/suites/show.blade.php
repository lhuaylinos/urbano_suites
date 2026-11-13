{{-- A04 Detalle de suite --}}
<x-layout :titulo="$tipo->nombre">
    <div class="mx-auto flex max-w-[1440px] flex-col gap-10 px-6 py-12 lg:px-[80px]">

        <a href="{{ route('suites') }}" class="text-sm text-gris hover:text-dorado">← Volver a las suites</a>

        <section class="flex flex-col gap-3.5">
            <p class="text-[10px] tracking-[2px] text-dorado">DETALLE DE LA HABITACIÓN</p>
            <h1 class="font-serif text-5xl font-medium leading-[1.1] tracking-[0.5px] lg:text-[60px]">{{ mb_strtoupper($tipo->nombre) }}</h1>
        </section>

        <section class="flex flex-col gap-10 lg:flex-row">
            <x-foto :src="$tipo->imagen ?: 'images/sin-foto.png'" :alt="$tipo->nombre" class="h-[320px] w-full rounded lg:h-[460px] lg:flex-1" />

            <div class="flex flex-col gap-8 lg:w-[420px] lg:shrink-0">
                <p class="text-sm leading-[1.8] text-[#52606a]">{{ $tipo->descripcion }}</p>

                <dl class="grid grid-cols-3 gap-4 border-y border-linea py-5 text-sm">
                    <div>
                        <dt class="text-[10px] tracking-[2px] text-dorado">CAPACIDAD</dt>
                        <dd class="mt-2">{{ $tipo->capacidad }} {{ $tipo->capacidad == 1 ? 'persona' : 'personas' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] tracking-[2px] text-dorado">TAMAÑO</dt>
                        <dd class="mt-2">{{ $tipo->tamano_m2 ? rtrim(rtrim($tipo->tamano_m2, '0'), '.') . ' m²' : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] tracking-[2px] text-dorado">POR NOCHE</dt>
                        <dd class="mt-2 font-semibold">S/ {{ number_format($tipo->precio_base, 2) }}</dd>
                    </div>
                </dl>

                <ul class="grid grid-cols-2 gap-2 text-sm text-[#52606a]">
                    <li>· Wi-Fi gratuito</li>
                    <li>· Aire acondicionado</li>
                    <li>· Baño privado</li>
                    <li>· Atención 24 horas</li>
                </ul>
            </div>
        </section>

        {{-- Consultar disponibilidad de este tipo de habitación --}}
        <section class="rounded-xl bg-white p-6 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <h2 class="mb-4 font-serif text-2xl font-semibold">Consulta la disponibilidad</h2>

            {{-- Errores del buscador (por ejemplo, salida antes de la entrada) --}}
            @if ($errors->any())
                <ul class="mb-4 list-disc pl-5 text-sm text-red-700">
                    @foreach ($errors->all() as $mensaje)
                        <li>{{ $mensaje }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="GET" action="{{ route('buscar') }}" class="grid gap-4 md:grid-cols-4">
                <x-campo label="Fecha de entrada" name="fecha_ingreso" type="date" min="{{ now()->toDateString() }}" />
                <x-campo label="Fecha de salida" name="fecha_salida" type="date" min="{{ now()->addDay()->toDateString() }}" />
                <x-selector label="Huéspedes" name="huespedes" :opciones="array_combine(range(1, $tipo->capacidad), range(1, $tipo->capacidad))"
                            :value="min(2, $tipo->capacidad)" placeholder="Elige" />
                <div class="flex items-end">
                    <x-boton class="h-[52px] w-full">VER DISPONIBILIDAD</x-boton>
                </div>
            </form>

            {{-- Al elegir la entrada, la salida se ajusta sola al día siguiente (mismo criterio del Home) --}}
            <script>
                const entrada = document.getElementById('fecha_ingreso');
                const salida = document.getElementById('fecha_salida');

                entrada.addEventListener('change', () => {
                    if (!entrada.value) return;

                    const diaSiguiente = new Date(entrada.value);
                    diaSiguiente.setUTCDate(diaSiguiente.getUTCDate() + 1);
                    const minimo = diaSiguiente.toISOString().slice(0, 10);

                    salida.min = minimo;
                    if (!salida.value || salida.value <= entrada.value) {
                        salida.value = minimo;
                    }
                });
            </script>
        </section>
    </div>
</x-layout>
