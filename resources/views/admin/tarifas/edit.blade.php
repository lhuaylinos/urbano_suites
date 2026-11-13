<x-layout titulo="Editar tarifa">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Editar tarifa por temporada" />

        <x-alerta />

        @include('admin.tarifas._form', ['accion' => route('admin.tarifas.update', $tarifa), 'tarifa' => $tarifa])
    </section>
</x-layout>
