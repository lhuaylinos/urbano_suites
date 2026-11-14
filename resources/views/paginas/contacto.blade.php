<x-layout titulo="Contacto">
    <div class="mx-auto flex max-w-[1440px] flex-col gap-12 px-6 py-16 lg:px-[80px]">

        <section class="flex flex-col gap-3.5">
            <p class="text-[10px] tracking-[2px] text-dorado">ESTAMOS PARA AYUDARTE</p>
            <h1 class="font-serif text-5xl font-medium leading-[1.1] tracking-[0.5px] lg:text-[60px]">CONVERSEMOS SOBRE TU ESTADÍA</h1>
            <p class="text-sm leading-[1.7] text-gris">Antes de llegar, durante tu visita o al planear tu próximo viaje: cuenta con nosotros.</p>
        </section>

        {{-- datos de contacto y formulario --}}
        <section class="flex flex-col gap-12 lg:flex-row lg:gap-16">

            {{-- columna de datos --}}
            <div class="flex flex-col gap-[30px] lg:w-[480px] lg:shrink-0">
                <h2 class="font-serif text-[34px] font-medium leading-[1.1] tracking-[0.5px]">Te esperamos en Miraflores</h2>

                @foreach ([
                    ['DIRECCIÓN', 'Av. José Larco 1234, Miraflores, Lima', 'M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11Zm0-8.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z'],
                    ['TELÉFONO', '+51 1 700 1514', 'M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z'],
                    ['CORREO ELECTRÓNICO', 'reservas@urbanosuites.pe', 'M3 6h18v12H3V6Zm0 0 9 7 9-7'],
                    ['HORARIO DE ATENCIÓN', 'Atención 24 horas', 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-13v5l3 2'],
                ] as [$etiqueta, $dato, $icono])
                    <div class="flex items-start gap-[18px]">
                        <svg class="size-[22px] shrink-0 text-dorado" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="{{ $icono }}" />
                        </svg>
                        <div class="flex flex-col gap-2.5">
                            <p class="text-[10px] tracking-[2px] text-dorado">{{ $etiqueta }}</p>
                            <p class="text-sm leading-[1.7] text-[#52606a]">{{ $dato }}</p>
                        </div>
                    </div>
                @endforeach

                <hr class="border-linea">
                <p class="text-[13px] leading-[1.7] text-gris">A pocos pasos del malecón, restaurantes y los principales espacios culturales de Miraflores.</p>
            </div>

            {{-- formulario (lo valida ContactoController) --}}
            <div class="flex-1">
                <form method="POST" action="{{ route('contacto.enviar') }}"
                      class="flex flex-col gap-6 rounded border border-linea bg-white p-6 md:p-10">
                    @csrf
                    <h2 class="font-serif text-[32px] font-medium leading-[1.1] tracking-[0.5px]">Envíanos un mensaje</h2>

                    <x-alerta />

                    <x-campo label="Nombre completo" name="nombre" placeholder="Juan Pérez Velásquez" />
                    <x-campo label="Correo electrónico" name="email" type="email" placeholder="juan.perez@email.com" />

                    <div class="flex flex-col gap-[9px]">
                        <label for="mensaje" class="text-xs font-medium">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="4"
                                  placeholder="Hola, me gustaría recibir más información sobre mi próxima estadía"
                                  @class([
                                      'w-full rounded-[2px] border bg-[#fdfcfa] p-4 text-xs text-marino placeholder:text-[#7c8386] focus:border-dorado focus:outline-none',
                                      'border-linea' => ! $errors->has('mensaje'),
                                      'border-red-600' => $errors->has('mensaje'),
                                  ])>{{ old('mensaje') }}</textarea>
                        @error('mensaje')
                            <p class="text-xs text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <p class="text-[11px] leading-[1.7] text-gris">Usaremos tus datos únicamente para responder tu consulta.</p>

                    <x-boton class="w-full">ENVIAR MENSAJE</x-boton>
                </form>
            </div>
        </section>
    </div>
</x-layout>
