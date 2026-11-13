<x-layout titulo="Editar habitación">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Editar habitación" />

        <x-alerta />

        @include('admin.habitaciones._form', ['accion' => route('admin.habitaciones.update', $habitacion), 'habitacion' => $habitacion])
    </section>
</x-layout>
