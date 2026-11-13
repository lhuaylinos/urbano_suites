<x-layout titulo="Nueva tarifa">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Nueva tarifa por temporada" />

        <x-alerta />

        @include('admin.tarifas._form', ['accion' => route('admin.tarifas.store')])
    </section>
</x-layout>
