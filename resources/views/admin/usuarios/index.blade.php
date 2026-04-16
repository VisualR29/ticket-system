@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Usuarios</h1>
        <a href="{{ route('register') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Registrar usuario (público)</a>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-2 text-left font-medium">Nombre</th>
                    <th class="px-4 py-2 text-left font-medium">Email</th>
                    <th class="px-4 py-2 text-left font-medium">Rol</th>
                    <th class="px-4 py-2 text-right font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($usuarios as $u)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-medium">{{ $u->name }}</td>
                        <td class="px-4 py-2">{{ $u->email }}</td>
                        <td class="px-4 py-2">
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ $u->rol }}</span>
                        </td>
                        <td class="px-4 py-2 text-right whitespace-nowrap">
                            <a href="{{ route('admin.usuarios.show', $u) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-medium">Gestionar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
