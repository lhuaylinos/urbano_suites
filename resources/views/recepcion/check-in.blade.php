{{-- B06 Registrar check-in: se asigna la habitación física --}}
<x-layout titulo="Registrar check-in">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="RECEPCIÓN" titulo="Registrar check-in" />

        <x-alerta />

        @include('recepcion._buscador')

        <a href="{{ route('recepcion.panel') }}" class="text-sm text-gris hover:text-dorado">← Volver al panel</a>

        <div class="flex max-w-[760px] flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <dl class="grid gap-3 text-sm sm:grid-cols-2">
                <div><dt class="text-gris">Reserva</dt><dd class="font-medium">{{ $reserva->codigo }}</dd></div>
                <div><dt class="text-gris">Huésped</dt><dd>{{ $reserva->user->name }} {{ $reserva->user->apellidos }}</dd></div>
                <div><dt class="text-gris">Documento</dt><dd>{{ $reserva->user->tipo_documento }} {{ $reserva->user->numero_documento }}</dd></div>
                <div><dt class="text-gris">Tipo reservado</dt><dd>{{ $reserva->tipo->nombre }}</dd></div>
                <div><dt class="text-gris">Entrada</dt><dd>{{ $reserva->fecha_entrada->format('d/m/Y') }}</dd></div>
                <div><dt class="text-gris">Salida</dt><dd>{{ $reserva->fecha_salida->format('d/m/Y') }}</dd></div>
            </dl>

            @if ($habitaciones->isEmpty())
                <p class="rounded bg-amber-50 p-4 text-sm text-amber-800">
                    No hay habitaciones disponibles de este tipo en este momento. Revisa en limpieza o mantenimiento.
                </p>
            @else
                <form method="POST" action="{{ route('recepcion.check-in.guardar', $reserva) }}" class="flex flex-col gap-6">
                    @csrf
                    {{-- Se muestra el número de habitación, no su id --}}
                    <x-selector label="Habitación a asignar" name="habitacion_id" :opciones="$habitaciones" placeholder="Selecciona una habitación" />
                    <div><x-boton>CONFIRMAR CHECK-IN</x-boton></div>
                </form>
            @endif
        </div>
    </section>
</x-layout>
