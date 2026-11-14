@props(['titulo'])

<section class="flex justify-center px-4 pb-12 pt-10">
    <div class="flex w-full max-w-[528px] flex-col gap-7 rounded-[2px] border border-linea bg-white px-6 pb-[30px] pt-[34px] shadow-[0_12px_40px_rgba(17,35,53,0.02)] sm:px-12">

        <div class="flex flex-col items-center gap-[13px] text-center">
            <svg width="28" height="32" viewBox="0 0 28 32" fill="none" aria-hidden="true">
                <path d="M14 1 27 16 14 31 1 16Z" stroke="#b68253" />
                <path d="M10 10v7a4 4 0 0 0 8 0v-7" stroke="#b68253" />
            </svg>

            <p class="text-[9px] text-dorado">TU PRÓXIMA ESTANCIA EMPIEZA AQUÍ</p>

            <h1 class="font-serif text-[37px] font-medium leading-[1.1]">{{ $titulo }}</h1>

            @isset($subtitulo)
                <p class="text-xs leading-[1.7] text-[#52606a]">{{ $subtitulo }}</p>
            @endisset
        </div>

        @if (session('status'))
            <p class="rounded-[2px] border border-dorado/40 bg-crema px-4 py-3 text-xs text-marino">
                {{ session('status') }}
            </p>
        @endif

        {{ $slot }}

        <div class="flex flex-col items-center gap-[22px]">
            @isset($pie)
                <p class="text-[11px] text-[#52606a]">{{ $pie }}</p>
            @endisset

            <div class="h-px w-full bg-linea"></div>

            <p class="flex items-center gap-[7px] text-[9px] text-[#7c8386]">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <rect x="4" y="11" width="16" height="10" rx="2" />
                    <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                </svg>
                Tus datos, siempre a buen recaudo.
            </p>
        </div>
    </div>
</section>
