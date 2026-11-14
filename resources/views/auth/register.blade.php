<x-layout titulo="Crear cuenta">
    <x-tarjeta-acceso titulo="CREA TU CUENTA">
        <x-slot:subtitulo>
            Un lugar para tus próximas escapadas.<br>
            Reserva con calma, disfruta de lo extraordinario.
        </x-slot:subtitulo>

        {{-- formulario (lo procesa fortify con CreateNewUser) --}}
        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-[19px]">
            @csrf

            <x-campo label="Nombres" name="name" placeholder="Juan" required autofocus />

            <x-campo label="Apellidos" name="apellidos" placeholder="Pérez Velásquez" required />

            {{-- documento: tipo y numero --}}
            <div class="flex flex-col gap-[9px]">
                <label for="numero_documento" class="text-xs font-medium">Documento de identidad</label>
                <div class="grid grid-cols-[130px_1fr] gap-3">
                    <select name="tipo_documento" aria-label="Tipo de documento"
                            class="h-[52px] rounded-[2px] border border-linea bg-[#fdfcfa] px-3 text-xs focus:border-dorado focus:outline-none">
                        @foreach (['DNI', 'Pasaporte', 'CE'] as $tipo)
                            <option value="{{ $tipo }}" @selected(old('tipo_documento') === $tipo)>{{ $tipo }}</option>
                        @endforeach
                    </select>
                    <input id="numero_documento" name="numero_documento" value="{{ old('numero_documento') }}"
                           placeholder="72839401" required
                           @class([
                               'h-[52px] w-full rounded-[2px] border bg-[#fdfcfa] px-4 text-xs placeholder:text-[#7c8386] focus:border-dorado focus:outline-none',
                               'border-linea' => ! $errors->has('numero_documento'),
                               'border-red-600' => $errors->has('numero_documento'),
                           ])>
                </div>
                @error('tipo_documento') <p class="text-xs text-red-700">{{ $message }}</p> @enderror
                @error('numero_documento') <p class="text-xs text-red-700">{{ $message }}</p> @enderror
            </div>

            <x-campo label="Correo electrónico" name="email" type="email" placeholder="juan.perez@email.com" required />

            {{-- telefono (opcional, la regla esta en CreateNewUser) --}}
            <x-campo label="Teléfono" name="telefono" type="tel" placeholder="+51 999 123 456" />

            <x-campo label="Contraseña" name="password" type="password" placeholder="Crea una contraseña"
                     ayuda="Utiliza al menos 8 caracteres." required />

            {{-- terminos y condiciones --}}
            <div class="flex flex-col gap-1 pt-[3px]">
                <label class="flex items-start gap-2.5 text-[11px] leading-[1.7] text-[#52606a]">
                    <input type="checkbox" name="terminos" value="1" @checked(old('terminos'))
                           class="mt-1 size-4 accent-dorado">
                    <span>
                        He leído y acepto los <span class="text-marino underline">Términos y condiciones</span>
                        y la <span class="text-marino underline">Política de privacidad</span>.
                    </span>
                </label>
                @error('terminos') <p class="text-xs text-red-700">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="h-[52px] w-full rounded-[2px] bg-dorado text-[11px] font-semibold text-white transition hover:bg-marino">
                CREAR CUENTA
            </button>
        </form>

        <x-slot:pie>
            ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="font-medium text-dorado underline">Inicia sesión</a>
        </x-slot:pie>
    </x-tarjeta-acceso>
</x-layout>
