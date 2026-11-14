<x-layout titulo="Usuarios y roles">
    <section class="mx-auto flex max-w-[1440px] flex-col gap-8 px-6 py-[72px] lg:px-[88px]">
        <x-encabezado-pagina etiqueta="ADMINISTRACIÓN" titulo="Usuarios y roles">
            Los usuarios se registran solos desde la web como huéspedes. Aquí asignas su rol y los activas o desactivas.
        </x-encabezado-pagina>

        <x-alerta />

        <a href="{{ route('admin.panel') }}" class="text-sm text-gris hover:text-dorado">← Volver al panel</a>

        <div class="overflow-x-auto rounded-xl bg-white shadow-[0_14px_40px_rgba(17,35,53,0.04)]">
            <table class="w-full min-w-[820px] text-left text-sm">
                <thead class="border-b border-linea text-xs text-gris">
                    <tr>
                        <th class="px-5 py-4 font-medium">Nombre</th>
                        <th class="px-5 py-4 font-medium">Documento</th>
                        <th class="px-5 py-4 font-medium">Correo</th>
                        <th class="px-5 py-4 font-medium">Rol</th>
                        <th class="px-5 py-4 font-medium">Estado</th>
                        <th class="px-5 py-4 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr class="border-b border-linea last:border-0">
                            <td class="px-5 py-4 font-medium">{{ $usuario->name }} {{ $usuario->apellidos }}</td>
                            <td class="px-5 py-4">{{ $usuario->tipo_documento }} {{ $usuario->numero_documento }}</td>
                            <td class="px-5 py-4">{{ $usuario->email }}</td>
                            <td class="px-5 py-4">{{ $roles[$usuario->roles->first()?->name] ?? '—' }}</td>
                            <td class="px-5 py-4">
                                @if ($usuario->activo)
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs text-emerald-700">Activo</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gris">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="text-dorado hover:underline">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gris">Todavía no hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $usuarios->links() }}
    </section>
</x-layout>
