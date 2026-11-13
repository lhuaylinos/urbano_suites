<x-layout titulo="Nuevo servicio adicional">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Nuevo servicio adicional" />

        <x-alerta />

        @include('admin.servicios._form', ['accion' => route('admin.servicios-adicionales.store')])
    </section>
</x-layout>
