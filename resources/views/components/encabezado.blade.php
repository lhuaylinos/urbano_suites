{{-- enlaces principales --}}
@php
    $enlaces = [
        'hotel'        => 'El hotel',
        'suites'       => 'Suites',
        'experiencias' => 'Experiencias',
        'contacto'     => 'Contacto',
    ];
@endphp

<header class="relative z-20 bg-crema">
    <div class="mx-auto flex h-[112px] max-w-[1440px] items-center justify-between px-6 lg:px-[72px]">
        <a href="{{ route('inicio') }}" class="font-serif text-[26px] font-semibold text-marino lg:text-[31px]">
            URBANO SUITES
        </a>

        {{-- menu de escritorio y acceso --}}
        <nav class="flex items-center gap-6 lg:gap-[34px]">
            <ul class="hidden items-center gap-[34px] text-[13px] md:flex">
                @foreach ($enlaces as $ruta => $texto)
                    <li>
                        <a href="{{ route($ruta) }}"
                           @class([
                               'transition hover:text-dorado',
                               'text-dorado' => request()->routeIs($ruta),
                           ])>
                            {{ $texto }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <span class="hidden h-6 w-px bg-dorado/45 md:block"></span>

            {{-- sin sesion --}}
            @guest
                <a href="{{ route('login') }}"
                   class="flex h-11 items-center rounded border border-dorado/45 px-[22px] text-[10px] font-medium tracking-wide transition hover:bg-dorado hover:text-white">
                    INICIAR SESIÓN
                </a>
            @endguest

            {{-- con sesion: menu del usuario --}}
            @auth
                <details class="group relative">
                    <summary class="flex h-11 cursor-pointer list-none items-center gap-2 rounded border border-dorado/45 px-[18px] text-[12px] transition hover:border-dorado">
                        <span class="flex size-6 items-center justify-center rounded-full bg-dorado text-[11px] font-semibold text-white">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </span>
                        <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                        <svg class="size-3 transition group-open:rotate-180" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="m3 4.5 3 3 3-3" />
                        </svg>
                    </summary>

                    <div class="absolute right-0 mt-2 w-64 overflow-hidden rounded-lg border border-linea bg-white text-[13px] shadow-[0_14px_40px_rgba(17,35,53,0.08)]">
                        <div class="border-b border-linea px-4 py-3">
                            <p class="font-medium">{{ auth()->user()->name }} {{ auth()->user()->apellidos }}</p>
                            <p class="truncate text-xs text-gris">{{ auth()->user()->email }}</p>
                        </div>

                        <a href="{{ route('perfil') }}" class="block px-4 py-2.5 hover:bg-crema">Mi perfil</a>

                        {{-- cada rol ve solo sus opciones --}}
                        @role('huesped')
                            <a href="{{ route('mis-reservas') }}" class="block px-4 py-2.5 hover:bg-crema">Mis reservas</a>
                        @endrole

                        @role('recepcionista')
                            <a href="{{ route('recepcion.panel') }}" class="block px-4 py-2.5 hover:bg-crema">Panel de recepción</a>
                        @endrole

                        @role('limpieza')
                            <a href="{{ route('limpieza.panel') }}" class="block px-4 py-2.5 hover:bg-crema">Habitaciones por limpiar</a>
                        @endrole

                        @role('administrador')
                            <a href="{{ route('admin.panel') }}" class="block px-4 py-2.5 hover:bg-crema">Panel de administración</a>
                        @endrole

                        {{-- cerrar sesion (post con csrf) --}}
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-linea">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2.5 text-left text-dorado hover:bg-crema">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </details>
            @endauth
        </nav>
    </div>

    {{-- menu en celular --}}
    <ul class="flex justify-center gap-6 pb-4 text-[13px] md:hidden">
        @foreach ($enlaces as $ruta => $texto)
            <li>
                <a href="{{ route($ruta) }}" @class(['text-dorado' => request()->routeIs($ruta)])>{{ $texto }}</a>
            </li>
        @endforeach
    </ul>
</header>
