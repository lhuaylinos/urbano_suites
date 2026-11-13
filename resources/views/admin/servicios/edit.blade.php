<x-layout titulo="Editar servicio adicional">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Editar servicio adicional" />

        <x-alerta />

        @include('admin.servicios._form', ['accion' => route('admin.servicios-adicionales.update', $servicio), 'servicio' => $servicio])
    </section>
</x-layout>
