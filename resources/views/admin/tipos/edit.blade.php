<x-layout titulo="Editar tipo de habitación">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Editar tipo de habitación" />

        <x-alerta />

        @include('admin.tipos._form', ['accion' => route('admin.tipos-habitacion.update', $tipo), 'tipo' => $tipo])
    </section>
</x-layout>
