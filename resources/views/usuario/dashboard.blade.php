@extends('layouts.app')
@section('title', 'Mi panel')
@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Bienvenido, {{ auth()->user()->name }}</h1>
        <p class="text-gray-600 mt-1">Aquí puedes ver y gestionar tus tickets de soporte.</p>
    </div>
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="{{ route('usuario.tickets.create') }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
            Nuevo ticket
        </a>
        <a href="{{ route('usuario.tickets.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Ver mis tickets
        </a>
    </div>
    <h2 class="text-lg font-semibold text-gray-900 mb-3">Últimos tickets</h2>
    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-700">Número</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700">Descripción</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($misTickets as $ticket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-mono text-xs">{{ $ticket->numero_reporte }}</td>
                        <td class="px-4 py-2">{{ \Illuminate\Support\Str::limit($ticket->descripcion_corta, 50) }}</td>
                        <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $ticket->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">No tienes tickets aún.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
