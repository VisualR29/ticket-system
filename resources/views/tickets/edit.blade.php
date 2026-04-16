@extends('layouts.app')
@section('title', 'Editar ' . $ticket->numero_reporte)
@section('content')
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar {{ $ticket->numero_reporte }}</h1>
        <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST"
            class="space-y-6 bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">N° reporte</label>
                    <input type="text" value="{{ $ticket->numero_reporte }}" disabled
                        class="w-full rounded-md border-gray-200 bg-gray-100 text-sm text-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del cliente *</label>
                    <input type="text" name="cliente_nombre" value="{{ old('cliente_nombre', $ticket->cliente_nombre) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    @error('cliente_nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="cliente_email" value="{{ old('cliente_email', $ticket->cliente_email) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento *</label>
                    <input type="text" name="departamento" value="{{ old('departamento', $ticket->departamento) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                    <select name="categoria" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach (['software', 'hardware', 'comunicaciones', 'plataformas', 'email', 'otro'] as $cat)
                            <option value="{{ $cat }}" @selected(old('categoria', $ticket->categoria) === $cat)>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urgencia *</label>
                    <select name="nivel_urgencia" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach (['baja', 'media', 'alta', 'critica'] as $nivel)
                            <option value="{{ $nivel }}" @selected(old('nivel_urgencia', $ticket->nivel_urgencia) === $nivel)>{{ ucfirst($nivel) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción corta *</label>
                    <input type="text" name="descripcion_corta" value="{{ old('descripcion_corta', $ticket->descripcion_corta) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción detallada</label>
                    <textarea name="descripcion_detallada" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('descripcion_detallada', $ticket->descripcion_detallada) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Técnico asignado</label>
                    <input type="text" name="tecnico_asignado" value="{{ old('tecnico_asignado', $ticket->tecnico_asignado) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                    <select name="status" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach (['pendiente', 'en_curso', 'en_espera', 'cancelada', 'finalizada'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $ticket->status) === $st)>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha promesa</label>
                    <input type="datetime-local" name="fecha_promesa"
                        value="{{ old('fecha_promesa', $ticket->fecha_promesa?->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha resolución</label>
                    <input type="datetime-local" name="fecha_resolucion"
                        value="{{ old('fecha_resolucion', $ticket->fecha_resolucion?->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Comentarios del técnico</label>
                    <textarea name="comentarios_tecnico" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('comentarios_tecnico', $ticket->comentarios_tecnico) }}</textarea>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-md hover:bg-amber-600">
                    Actualizar
                </button>
                <a href="{{ route('admin.tickets.show', $ticket) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
