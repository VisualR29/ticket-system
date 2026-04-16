@extends('layouts.app')
@section('title', 'Todos los tickets')
@section('content')
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Vista gerencial — todos los tickets</h1>

    @if ($tickets->isEmpty())
        <div class="rounded-lg border border-gray-200 bg-white p-8 text-center text-gray-600">No hay tickets registrados.</div>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium text-gray-700">#</th>
                        <th class="px-3 py-2 text-left font-medium text-gray-700">Cliente</th>
                        <th class="px-3 py-2 text-left font-medium text-gray-700">Depto.</th>
                        <th class="px-3 py-2 text-left font-medium text-gray-700">Urgencia</th>
                        <th class="px-3 py-2 text-left font-medium text-gray-700">Estado</th>
                        <th class="px-3 py-2 text-left font-medium text-gray-700">Fecha</th>
                        <th class="px-3 py-2 text-right font-medium text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($tickets as $ticket)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 font-mono text-xs">{{ $ticket->numero_reporte }}</td>
                            <td class="px-3 py-2">{{ $ticket->cliente_nombre }}</td>
                            <td class="px-3 py-2">{{ $ticket->departamento }}</td>
                            <td class="px-3 py-2 capitalize">{{ $ticket->nivel_urgencia }}</td>
                            <td class="px-3 py-2 capitalize">{{ str_replace('_', ' ', $ticket->status) }}</td>
                            <td class="px-3 py-2 whitespace-nowrap">{{ $ticket->fecha_reporte?->format('d/m/Y H:i') }}</td>
                            <td class="px-3 py-2 text-right whitespace-nowrap">
                                @if (in_array(auth()->user()->rol, ['admin', 'gerente'], true) && in_array($ticket->status, ['pendiente', 'en_curso'], true))
                                    <form action="{{ route('tickets.cerrar', $ticket) }}" method="POST" class="inline"
                                        onsubmit="return confirm('¿Cerrar este ticket? Se marcará como finalizado.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-emerald-700 hover:text-emerald-900 text-xs font-medium">Cerrar</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
