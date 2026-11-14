@php
    $fechaEntrada = \Carbon\Carbon::parse($entrada);
    $fechaSalida = \Carbon\Carbon::parse($salida);
@endphp

<x-layout titulo="Disponibilidad">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="DISPONIBILIDAD" titulo="Habitaciones disponibles">
            Del {{ $fechaEntrada->format('d/m/Y') }} al {{ $fechaSalida->format('d/m/Y') }}
            · {{ $noches }} {{ $noches == 1 ? 'noche' : 'noches' }}
            · {{ $huespedes }} {{ $huespedes == 1 ? 'huésped' : 'huéspedes' }}
        </x-encabezado-pagina>

        <x-alerta />

        @if ($errors->any())
            <ul class="list-disc pl-5 text-sm text-red-700">
                @foreach ($errors->all() as $mensaje)
                    <li>{{ $mensaje }}</li>
                @endforeach
            </ul>
        @endif

        {{-- resultados: una tarjeta por tipo con cupo --}}
        @if ($tipos->isEmpty())
            <div class="flex max-w-[760px] flex-col gap-4 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                <h2 class="font-serif text-2xl font-semibold">No encontramos habitaciones para esas fechas</h2>
                <p class="text-sm leading-[1.7] text-gris">Prueba con otras fechas o con una cantidad distinta de huéspedes.</p>
                <div><x-boton :href="route('inicio') . '#buscador'">CAMBIAR FECHAS</x-boton></div>
            </div>
        @else
            <div class="flex flex-col gap-6">
                @foreach ($tipos as $tipo)
                    <article class="flex flex-col overflow-hidden rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)] md:flex-row">
                        <x-foto :src="$tipo->imagen ?: 'images/sin-foto.png'" :alt="$tipo->nombre" class="h-[200px] md:h-auto md:w-[320px] md:shrink-0" />

                        <div class="flex flex-1 flex-col justify-between gap-6 p-6 md:flex-row md:items-center">
                            <div class="flex flex-col gap-2.5">
                                <h2 class="font-serif text-[28px] font-semibold leading-[1.1]">{{ $tipo->nombre }}</h2>
                                <p class="max-w-[460px] text-sm leading-[1.7] text-gris">{{ $tipo->descripcion }}</p>
                                <p class="text-xs text-gris">
                                    Hasta {{ $tipo->capacidad }} {{ $tipo->capacidad == 1 ? 'persona' : 'personas' }}
                                    · Quedan {{ $tipo->libres }} {{ $tipo->libres == 1 ? 'habitación' : 'habitaciones' }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-3 md:items-end">
                                <p class="font-semibold text-dorado">S/ {{ number_format($tipo->precio_noche, 2) }} por noche</p>
                                {{-- tarifa de temporada, si hay una vigente --}}
                                @if ($tipo->temporada)
                                    <p class="text-xs text-dorado">Tarifa de temporada: {{ $tipo->temporada }}</p>
                                @endif
                                <p class="text-xs text-gris">Total {{ $noches }} {{ $noches == 1 ? 'noche' : 'noches' }} con IGV: S/ {{ number_format($tipo->total_con_igv, 2) }}</p>
                                <x-boton :href="route('reservas.resumen', ['tipo' => $tipo, 'fecha_ingreso' => $entrada, 'fecha_salida' => $salida, 'huespedes' => $huespedes])">
                                    RESERVAR
                                </x-boton>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- aviso a visitantes --}}
            @guest
                <p class="text-sm text-gris">Para reservar te pediremos que inicies sesión o crees una cuenta.</p>
            @endguest
        @endif
    </section>
</x-layout>
