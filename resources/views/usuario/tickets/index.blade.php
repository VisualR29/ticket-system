@extends('layouts.app')
@section('title', 'Mis tickets')
@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mis tickets</h1>
        <a href="{{ route('usuario.tickets.create') }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
            Nuevo ticket
        </a>
    </div>

    @if ($tickets->isEmpty())
        <div class="rounded-lg border border-gray-200 bg-white p-8 text-center text-gray-600">
            Aún no has creado tickets.
            <a href="{{ route('usuario.tickets.create') }}" class="text-indigo-600 font-medium hover:underline">Crear el primero</a>.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-700">#</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-700">Descripción</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-700">Estado</th>
                        <th class="px-4 py-2 text-right font-medium text-gray-700"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($tickets as $ticket)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-mono text-xs">{{ $ticket->numero_reporte }}</td>
                            <td class="px-4 py-2">{{ \Illuminate\Support\Str::limit($ticket->descripcion_corta, 60) }}</td>
                            <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $ticket->status) }}</td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('usuario.tickets.show', $ticket) }}"
                                    class="text-indigo-600 hover:text-indigo-900 text-xs font-medium">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
