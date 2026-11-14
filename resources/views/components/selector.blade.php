@props([
    'label',
    'name',
    'opciones',
    'value' => null,
    'placeholder' => 'Selecciona una opción',
])

<div class="flex flex-col gap-[9px]">
    <label for="{{ $name }}" class="text-xs font-medium">{{ $label }}</label>

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->class([
            'h-[52px] w-full rounded-[2px] border bg-[#fdfcfa] px-4 text-xs text-marino focus:border-dorado focus:outline-none',
            'border-linea' => ! $errors->has($name),
            'border-red-600' => $errors->has($name),
        ]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach ($opciones as $clave => $texto)
            <option value="{{ $clave }}" @selected((string) old($name, $value) === (string) $clave)>{{ $texto }}</option>
        @endforeach
    </select>

    @error($name)
        <p class="text-xs text-red-700">{{ $message }}</p>
    @enderror
</div>
