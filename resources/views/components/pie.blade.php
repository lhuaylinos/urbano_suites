<footer class="bg-marino text-white">
    <div class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <div class="flex flex-col gap-10 md:flex-row md:justify-between">
            <div class="flex max-w-[420px] flex-col gap-4">
                <p class="font-serif text-[31px] font-semibold tracking-[1.6px]">URBANO SUITES</p>
                <p class="text-sm leading-[1.7] text-white/80">Av. José Larco 1234, Miraflores, Lima</p>
                <p class="text-sm leading-[1.7] text-white/80">+51 1 700 1514</p>
                <p class="text-sm leading-[1.7] text-white/80">reservas@urbanosuites.pe</p>
                <p class="text-sm leading-[1.7] text-white/80">Atención 24 horas</p>
            </div>

            {{-- enlaces del pie --}}
            <nav class="flex w-[220px] flex-col gap-3">
                <p class="text-xs font-semibold tracking-[1.4px] text-white/60">Navegación</p>
                <a href="{{ route('hotel') }}" class="text-sm hover:text-dorado">El hotel</a>
                <a href="{{ route('suites') }}" class="text-sm hover:text-dorado">Suites</a>
                <a href="{{ route('experiencias') }}" class="text-sm hover:text-dorado">Experiencias</a>
                <a href="{{ route('contacto') }}" class="text-sm hover:text-dorado">Contacto</a>
            </nav>
        </div>

        <div class="h-px w-full bg-white/10"></div>

        <div class="flex flex-col gap-2 text-xs text-white/50 md:flex-row md:justify-between">
            <p>© {{ date('Y') }} Urbano Suites. Todos los derechos reservados.</p>
            <p>Política de privacidad · Términos y condiciones</p>
        </div>
    </div>
</footer>
