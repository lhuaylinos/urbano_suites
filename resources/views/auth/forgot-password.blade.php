{{-- el enlace llega al correo; en desarrollo queda en storage/logs/laravel.log --}}
<x-layout titulo="Recuperar contraseña">
    <x-tarjeta-acceso titulo="RECUPERAR ACCESO">
        <x-slot:subtitulo>Escribe tu correo y te enviaremos un enlace para crear una nueva contraseña.</x-slot:subtitulo>

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-[19px]">
            @csrf

            <x-campo label="Correo electrónico" name="email" type="email"
                     placeholder="juan.perez@email.com" required autofocus />

            <button type="submit"
                    class="h-[52px] w-full rounded-[2px] bg-dorado text-[11px] font-semibold text-white transition hover:bg-marino">
                ENVIAR ENLACE
            </button>
        </form>

        <x-slot:pie>
            <a href="{{ route('login') }}" class="font-medium text-dorado underline">Volver a iniciar sesión</a>
        </x-slot:pie>
    </x-tarjeta-acceso>
</x-layout>
