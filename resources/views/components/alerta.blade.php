{{-- mensajes flash: exito y error --}}
@if (session('exito'))
    <div class="rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('exito') }}
    </div>
@endif

@if (session('error'))
    <div class="rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        {{ session('error') }}
    </div>
@endif
