{{-- A15 Iniciar sesión. Fortify recibe el formulario en POST /login --}}
<x-layout titulo="Iniciar sesión">
    <x-tarjeta-acceso titulo="INICIAR SESIÓN">
        <x-slot:subtitulo>Ingresa a tu cuenta</x-slot:subtitulo>

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-[19px]">
            @csrf

            <x-campo label="Correo electrónico" name="email" type="email"
                     placeholder="juan.perez@email.com" required autofocus />

            <x-campo label="Contraseña" name="password" type="password"
                     placeholder="Ingresa tu contraseña" required />

            <a href="{{ route('password.request') }}" class="self-end text-[11px] text-dorado underline">
                ¿Olvidaste tu contraseña?
            </a>

            <button type="submit"
                    class="h-[52px] w-full rounded-[2px] bg-dorado text-[11px] font-semibold text-white transition hover:bg-marino">
                INGRESAR
            </button>
        </form>

        <x-slot:pie>
            ¿No tienes cuenta? <a href="{{ route('register') }}" class="font-medium text-dorado underline">Crear cuenta</a>
        </x-slot:pie>
    </x-tarjeta-acceso>
</x-layout>
