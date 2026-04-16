@extends('layouts.app')
@section('title', $ticket->numero_reporte)
@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('usuario.tickets.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">← Volver a mis tickets</a>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-800 text-white flex flex-wrap justify-between items-center gap-2">
                <h1 class="text-lg font-semibold">{{ $ticket->numero_reporte }}</h1>
                <span class="text-sm px-2 py-1 rounded bg-gray-700 capitalize">{{ str_replace('_', ' ', $ticket->status) }}</span>
            </div>
            <div class="p-6 space-y-4 text-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Departamento</p>
                        <p class="font-medium">{{ $ticket->departamento }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Categoría</p>
                        <p class="font-medium capitalize">{{ $ticket->categoria }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Urgencia</p>
                        <p class="font-medium capitalize">{{ $ticket->nivel_urgencia }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Fecha de reporte</p>
                        <p class="font-medium">{{ $ticket->fecha_reporte?->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Resumen</p>
                    <p class="font-medium text-gray-900">{{ $ticket->descripcion_corta }}</p>
                </div>
                @if ($ticket->descripcion_detallada)
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Detalle</p>
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->descripcion_detallada }}</p>
                    </div>
                @endif
                @if ($ticket->comentarios_tecnico)
                    <div class="rounded-md bg-amber-50 border border-amber-100 p-4">
                        <p class="text-amber-900 text-xs font-semibold uppercase tracking-wide mb-1">Respuesta del equipo</p>
                        <p class="text-amber-950 whitespace-pre-wrap">{{ $ticket->comentarios_tecnico }}</p>
                    </div>
                @endif
                @if ($ticket->fecha_promesa)
                    <p class="text-sm text-gray-600">
                        <span class="font-medium text-gray-800">Compromiso de respuesta:</span>
                        {{ $ticket->fecha_promesa->format('d/m/Y H:i') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
