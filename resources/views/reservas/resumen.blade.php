<x-layout titulo="Confirmar reserva">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="RESERVA" titulo="Confirma tu reserva">
            Revisa los datos de tu estadía y paga con tarjeta. La estadía se cobra ahora; los consumos se cobran en el check-out.
        </x-encabezado-pagina>

        <x-alerta />

        <div class="grid gap-8 lg:grid-cols-2">

            <div class="flex flex-col gap-5 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                <h2 class="font-serif text-2xl font-semibold">Resumen de la reserva</h2>

                <x-foto :src="$tipo->imagen ?: 'images/sin-foto.png'" :alt="$tipo->nombre" class="h-[180px] w-full rounded" />

                <dl class="flex flex-col gap-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gris">Habitación</dt><dd class="font-medium">{{ $tipo->nombre }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Entrada</dt><dd>{{ \Carbon\Carbon::parse($entrada)->format('d/m/Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Salida</dt><dd>{{ \Carbon\Carbon::parse($salida)->format('d/m/Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Huéspedes</dt><dd>{{ $huespedes }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Noches</dt><dd>{{ $noches }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">Precio por noche</dt><dd>S/ {{ number_format($precioNoche, 2) }}</dd></div>
                    @if ($tarifa)
                        <div class="flex justify-between"><dt class="text-gris">Tarifa de temporada</dt><dd>{{ $tarifa->temporada }}</dd></div>
                    @endif
                    <div class="flex justify-between border-t border-linea pt-3"><dt class="text-gris">Subtotal</dt><dd>S/ {{ number_format($montos['subtotal'], 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gris">IGV (18 %)</dt><dd>S/ {{ number_format($montos['igv'], 2) }}</dd></div>
                </dl>

                <div class="flex justify-between border-t border-linea pt-4 text-lg">
                    <span class="font-serif font-semibold">Total a pagar</span>
                    <span class="font-semibold text-dorado">S/ {{ number_format($montos['total'], 2) }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('reservas.store', $tipo) }}"
                  class="flex flex-col gap-6 rounded-xl bg-white p-8 shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
                @csrf
                <h2 class="font-serif text-2xl font-semibold">Datos de pago</h2>

                <input type="hidden" name="fecha_ingreso" value="{{ $entrada }}">
                <input type="hidden" name="fecha_salida" value="{{ $salida }}">
                <input type="hidden" name="huespedes" value="{{ $huespedes }}">

                @if ($errors->hasAny(['fecha_ingreso', 'fecha_salida', 'huespedes']))
                    <p class="text-xs text-red-700">{{ $errors->first('fecha_ingreso') ?: ($errors->first('fecha_salida') ?: $errors->first('huespedes')) }}</p>
                @endif

                <x-campo label="Titular de la tarjeta" name="titular" placeholder="Como aparece en la tarjeta" :value="auth()->user()->name . ' ' . auth()->user()->apellidos" />
                <x-campo label="Número de tarjeta" name="numero_tarjeta" placeholder="4111 1111 1111 1111" inputmode="numeric" maxlength="23"
                         ayuda="Pago simulado: las tarjetas que terminan en 0000 serán rechazadas." />

                <div class="grid grid-cols-2 gap-6">
                    <x-campo label="Vencimiento (MM/AA)" name="vence" placeholder="12/28" inputmode="numeric" maxlength="5" />
                    <x-campo label="Código de seguridad" name="cvv" type="password" placeholder="123" inputmode="numeric" maxlength="4" />
                </div>

                <label class="flex items-start gap-3 text-xs leading-[1.6] text-gris">
                    <input type="checkbox" name="terminos" value="1" class="mt-0.5 size-4 accent-[#b68253]" @checked(old('terminos'))>
                    <span>Acepto los términos y condiciones y la política de cancelación del hotel.</span>
                </label>
                @error('terminos')
                    <p class="-mt-4 text-xs text-red-700">{{ $message }}</p>
                @enderror

                <x-boton class="w-full">CONFIRMAR Y PAGAR S/ {{ number_format($montos['total'], 2) }}</x-boton>
            </form>
        </div>

        <script>
            const vence = document.getElementById('vence');
            const cvv = document.getElementById('cvv');

            vence.addEventListener('input', (evento) => {
                let digitos = vence.value.replace(/\D/g, '').slice(0, 4);
                const borrando = evento.inputType && evento.inputType.startsWith('delete');

                // con dos digitos se agrega la barra, salvo que este borrando
                if (digitos.length > 2 || (digitos.length === 2 && !borrando)) {
                    digitos = digitos.slice(0, 2) + '/' + digitos.slice(2);
                }
                vence.value = digitos;
            });

            cvv.addEventListener('input', () => {
                cvv.value = cvv.value.replace(/\D/g, '').slice(0, 4);
            });
        </script>
    </section>
</x-layout>
