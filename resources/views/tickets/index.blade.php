@extends('layouts.app')
@section('title', 'Todos los tickets')
@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tickets de soporte</h1>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-600">Total: {{ $tickets->count() }}</span>
            <a href="{{ route('admin.tickets.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                Nuevo ticket
            </a>
        </div>
    </div>

    @if ($tickets->isEmpty())
        <div class="rounded-lg border border-blue-100 bg-blue-50 text-blue-900 px-4 py-8 text-center">
            No hay tickets.
            <a href="{{ route('admin.tickets.create') }}" class="font-medium underline">Crea el primero</a>.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium"># Reporte</th>
                        <th class="px-3 py-2 text-left font-medium">Cliente</th>
                        <th class="px-3 py-2 text-left font-medium">Depto.</th>
                        <th class="px-3 py-2 text-left font-medium">Categoría</th>
                        <th class="px-3 py-2 text-left font-medium">Urgencia</th>
                        <th class="px-3 py-2 text-left font-medium">Estado</th>
                        <th class="px-3 py-2 text-left font-medium">Técnico</th>
                        <th class="px-3 py-2 text-right font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach ($tickets as $ticket)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 font-mono text-xs">{{ $ticket->numero_reporte }}</td>
                            <td class="px-3 py-2">{{ $ticket->cliente_nombre }}</td>
                            <td class="px-3 py-2">{{ $ticket->departamento }}</td>
                            <td class="px-3 py-2 capitalize">{{ $ticket->categoria }}</td>
                            <td class="px-3 py-2">
                                @php
                                    $u = $ticket->nivel_urgencia;
                                    $uClass =
                                        [
                                            'baja' => 'bg-green-100 text-green-800',
                                            'media' => 'bg-sky-100 text-sky-800',
                                            'alta' => 'bg-amber-100 text-amber-800',
                                            'critica' => 'bg-red-100 text-red-800',
                                        ][$u] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium {{ $uClass }}">{{ $u }}</span>
                            </td>
                            <td class="px-3 py-2 capitalize">{{ str_replace('_', ' ', $ticket->status) }}</td>
                            <td class="px-3 py-2">{{ $ticket->tecnico_asignado ?? '—' }}</td>
                            <td class="px-3 py-2 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('admin.tickets.show', $ticket) }}"
                                    class="text-indigo-600 hover:text-indigo-900 text-xs font-medium">Ver</a>
                                <a href="{{ route('admin.tickets.edit', $ticket) }}"
                                    class="text-amber-600 hover:text-amber-900 text-xs font-medium">Editar</a>
                                @if (in_array(auth()->user()->rol, ['admin', 'gerente'], true) && in_array($ticket->status, ['pendiente', 'en_curso'], true))
                                    <form action="{{ route('tickets.cerrar', $ticket) }}" method="POST" class="inline"
                                        onsubmit="return confirm('¿Cerrar este ticket? Se marcará como finalizado.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-900 text-xs font-medium">Cerrar</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="inline"
                                    onsubmit="return confirm('¿Eliminar este ticket?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-medium">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
