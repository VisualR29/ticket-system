@extends('layouts.app')
@section('title', 'Panel administrador')
@section('content')
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Panel de administración</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="rounded-lg border border-gray-200 bg-white p-6 text-center shadow-sm">
            <p class="text-sm text-gray-500">Total tickets</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $estadisticas['total'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-6 text-center shadow-sm">
            <p class="text-sm text-gray-500">Pendientes</p>
            <p class="text-3xl font-bold text-amber-600">{{ $estadisticas['pendientes'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-6 text-center shadow-sm">
            <p class="text-sm text-gray-500">En curso</p>
            <p class="text-3xl font-bold text-sky-600">{{ $estadisticas['en_curso'] }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-6 text-center shadow-sm">
            <p class="text-sm text-gray-500">Finalizados</p>
            <p class="text-3xl font-bold text-emerald-600">{{ $estadisticas['finalizados'] }}</p>
        </div>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.tickets.index') }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
            Todos los tickets
        </a>
        <a href="{{ route('admin.usuarios.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Gestionar usuarios
        </a>
    </div>
@endsection
