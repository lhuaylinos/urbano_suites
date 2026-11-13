{{--
    Campo de formulario con su etiqueta y su mensaje de error (Figma: "Campo de acceso").
    Sirve para todos los formularios: login, registro y los CRUD.
    Uso: <x-campo label="Correo electrónico" name="email" type="email" placeholder="juan.perez@email.com" />
--}}
@props([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'ayuda' => null,
])

<div class="flex flex-col gap-[9px]">
    <label for="{{ $name }}" class="text-xs font-medium">{{ $label }}</label>

    <div class="relative">
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
            {{ $attributes->class([
                'h-[52px] w-full rounded-[2px] border bg-[#fdfcfa] px-4 text-xs text-marino placeholder:text-[#7c8386] focus:border-dorado focus:outline-none',
                'border-linea' => ! $errors->has($name),
                'border-red-600' => $errors->has($name),
                'pr-12' => $type === 'password',
            ]) }}
        >

        {{-- Botón del ojo: alterna entre ocultar y mostrar la contraseña --}}
        @if ($type === 'password')
            <button type="button"
                    onclick="const c = document.getElementById('{{ $name }}'); c.type = c.type === 'password' ? 'text' : 'password';"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-[#7c8386] hover:text-marino"
                    aria-label="Mostrar u ocultar contraseña">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
            </button>
        @endif
    </div>

    @if ($ayuda)
        <p class="text-[10px] text-[#7c8386]">{{ $ayuda }}</p>
    @endif

    @error($name)
        <p class="text-xs text-red-700">{{ $message }}</p>
    @enderror
</div>
