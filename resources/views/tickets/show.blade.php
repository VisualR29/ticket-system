@extends('layouts.app')
@section('title', 'Ticket ' . $ticket->numero_reporte)
@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-800 text-white flex flex-wrap justify-between items-center gap-2">
                <h1 class="text-lg font-semibold">{{ $ticket->numero_reporte }}</h1>
                <span class="text-sm px-2 py-1 rounded bg-gray-700 capitalize">{{ str_replace('_', ' ', $ticket->status) }}</span>
            </div>
            <div class="p-6 space-y-4 text-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Cliente</p>
                        <p class="font-medium">{{ $ticket->cliente_nombre }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Email</p>
                        <p class="font-medium">{{ $ticket->cliente_email ?? '—' }}</p>
                    </div>
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
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Técnico asignado</p>
                        <p class="font-medium">{{ $ticket->tecnico_asignado ?? '—' }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Descripción corta</p>
                    <p class="font-medium">{{ $ticket->descripcion_corta }}</p>
                </div>
                @if ($ticket->descripcion_detallada)
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Descripción detallada</p>
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->descripcion_detallada }}</p>
                    </div>
                @endif
                <div class="pt-4 border-t border-gray-100">
    <h5 class="text-gray-900 font-semibold mb-3">Adjuntos del ticket</h5>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @foreach($ticket->attachments as $attachment)
            <div class="group relative bg-gray-50 p-2 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                @if(str_starts_with($attachment->mime_type, 'image/'))
                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="block">
                        <img src="{{ Storage::url($attachment->file_path) }}"
                            class="w-full h-32 object-cover rounded-md shadow-sm mb-2" 
                            alt="{{ $attachment->original_name }}">
                    </a>
                @else
                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                        class="flex items-center justify-center h-32 bg-white rounded-md border border-dashed border-gray-300 mb-2 group-hover:border-indigo-400 transition-colors">
                        <span class="text-indigo-600 text-xs font-bold uppercase">{{ $attachment->type }}</span>
                    </a>
                @endif
                <p class="text-gray-600 text-xs truncate font-medium" title="{{ $attachment->original_name }}">
                    {{ $attachment->original_name }}
                </p>
                <p class="text-[10px] text-gray-400 uppercase">{{ $attachment->mime_type }}</p>
            </div>
        @endforeach
    </div>
</div>
                @if ($ticket->comentarios_tecnico)
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Comentarios del técnico</p>
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->comentarios_tecnico }}</p>
                    </div>
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-gray-100">
                    <div>
                        <p class="text-gray-500 text-xs">Fecha reporte</p>
                        <p class="font-medium">{{ $ticket->fecha_reporte?->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Fecha promesa</p>
                        <p class="font-medium">{{ $ticket->fecha_promesa?->format('d/m/Y H:i') ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Fecha resolución</p>
                        <p class="font-medium">{{ $ticket->fecha_resolucion?->format('d/m/Y H:i') ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-wrap gap-3">
                <a href="{{ route('admin.tickets.edit', $ticket) }}"
                    class="inline-flex items-center px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-md hover:bg-amber-600">
                    Editar
                </a>
                <a href="{{ route('admin.tickets.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Volver al listado
                </a>
                <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="ml-auto"
                    onsubmit="return confirm('¿Eliminar este ticket?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
