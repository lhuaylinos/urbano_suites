<form method="GET" action="{{ route('recepcion.buscar') }}" class="no-imprimir flex flex-col gap-2">
    <div class="flex max-w-[560px] gap-3">
        <input type="text" name="q" value="{{ request('q') }}" maxlength="30" required
               placeholder="Buscar reserva por DNI, pasaporte o código"
               class="h-[48px] flex-1 rounded-[2px] border border-linea bg-white px-4 text-sm focus:border-dorado focus:outline-none">
        <x-boton class="h-[48px]">BUSCAR</x-boton>
    </div>

    @error('q')
        <p class="text-xs text-red-700">{{ $message }}</p>
    @enderror
</form>
