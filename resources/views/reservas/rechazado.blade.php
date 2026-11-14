<x-layout titulo="Pago rechazado">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="PAGO" titulo="No pudimos procesar tu pago">
            El banco rechazó la tarjeta, por eso la reserva no se completó y no se hizo ningún cobro.
        </x-encabezado-pagina>

        <div class="flex max-w-[760px] flex-col gap-5 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <p class="text-sm leading-[1.7] text-gris">
                {{ $tipo->nombre }} · del {{ \Carbon\Carbon::parse($entrada)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($salida)->format('d/m/Y') }}
            </p>
            <div class="flex flex-wrap gap-4">
                <x-boton :href="route('reservas.resumen', ['tipo' => $tipo, 'fecha_ingreso' => $entrada, 'fecha_salida' => $salida, 'huespedes' => $huespedes])">
                    INTENTAR CON OTRA TARJETA
                </x-boton>
                <x-boton variante="borde" :href="route('inicio') . '#buscador'">CAMBIAR FECHAS</x-boton>
            </div>
        </div>
    </section>
</x-layout>
