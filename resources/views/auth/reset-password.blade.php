{{-- Nueva contraseña: se llega desde el enlace del correo de recuperación --}}
<x-layout titulo="Nueva contraseña">
    <x-tarjeta-acceso titulo="NUEVA CONTRASEÑA">
        <x-slot:subtitulo>Crea una nueva contraseña para tu cuenta.</x-slot:subtitulo>

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-[19px]">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <x-campo label="Correo electrónico" name="email" type="email" :value="$request->email" required />

            <x-campo label="Nueva contraseña" name="password" type="password"
                     ayuda="Utiliza al menos 8 caracteres." required autofocus />

            <x-campo label="Repite la contraseña" name="password_confirmation" type="password" required />

            <button type="submit"
                    class="h-[52px] w-full rounded-[2px] bg-dorado text-[11px] font-semibold text-white transition hover:bg-marino">
                GUARDAR CONTRASEÑA
            </button>
        </form>
    </x-tarjeta-acceso>
</x-layout>
