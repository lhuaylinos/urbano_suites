{{-- A11 Mis reservas (CUS06). Por ahora sin datos: la tabla reservas se crea con el proceso principal --}}
<x-layout titulo="Mis reservas">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-10 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="MI CUENTA" titulo="Mis reservas">
            Aquí verás tus reservas y el estado de cada una.
        </x-encabezado-pagina>

        <div class="flex max-w-[760px] flex-col items-start gap-5 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <p class="font-serif text-[28px] font-semibold leading-tight">Aún no tienes reservas</p>
            <p class="text-sm leading-[1.7] text-gris">Elige tus fechas y encuentra la suite ideal para tu próxima estadía.</p>
            <a href="{{ route('suites') }}"
               class="rounded bg-dorado px-[18px] py-3 text-[11px] font-semibold tracking-[1.2px] text-white transition hover:bg-marino">
                VER SUITES
            </a>
        </div>
    </section>
</x-layout>
