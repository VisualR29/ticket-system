@extends('layouts.app')
@section('title', 'Usuario ' . $user->name)
@section('content')
    <div class="max-w-xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.usuarios.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">← Volver al listado</a>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-6">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-600 text-sm">{{ $user->email }}</p>
            </div>

            <form action="{{ route('admin.usuarios.cambiar-rol', $user) }}" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')
                <label class="block text-sm font-medium text-gray-700">Rol</label>
                <select name="rol"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @foreach (['admin', 'gerente', 'usuario'] as $r)
                        <option value="{{ $r }}" @selected($user->rol === $r)>{{ $r }}</option>
                    @endforeach
                </select>
                @error('rol')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                    Guardar rol
                </button>
            </form>

            @if ($user->id !== auth()->id())
                <div class="pt-4 border-t border-gray-200">
                    <form action="{{ route('admin.usuarios.destroy', $user) }}" method="POST"
                        onsubmit="return confirm('¿Eliminar a este usuario? Esta acción no se puede deshacer.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                            Eliminar usuario
                        </button>
                    </form>
                </div>
            @else
                <p class="text-sm text-gray-500">No puedes eliminar tu propia cuenta desde aquí. Usa el apartado de perfil si lo necesitas.</p>
            @endif
        </div>
    </div>
@endsection
