@extends('layouts.app')
@section('title', 'Nuevo ticket')
@section('content')
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Crear ticket</h1>
        <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6 bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">N° reporte *</label>
                    <input type="text" name="numero_reporte" value="{{ old('numero_reporte') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        placeholder="TKT-2026-0001" required>
                    @error('numero_reporte')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del cliente *</label>
                    <input type="text" name="cliente_nombre" value="{{ old('cliente_nombre') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    @error('cliente_nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email del cliente</label>
                    <input type="email" name="cliente_email" value="{{ old('cliente_email') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('cliente_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento *</label>
                    <input type="text" name="departamento" value="{{ old('departamento') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    @error('departamento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                    <select name="categoria" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">— Selecciona —</option>
                        @foreach (['software', 'hardware', 'comunicaciones', 'plataformas', 'email', 'otro'] as $cat)
                            <option value="{{ $cat }}" @selected(old('categoria') === $cat)>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                    @error('categoria')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urgencia *</label>
                    <select name="nivel_urgencia" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach (['baja', 'media', 'alta', 'critica'] as $nivel)
                            <option value="{{ $nivel }}" @selected(old('nivel_urgencia', 'media') === $nivel)>{{ ucfirst($nivel) }}</option>
                        @endforeach
                    </select>
                    @error('nivel_urgencia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción corta *</label>
                    <input type="text" name="descripcion_corta" value="{{ old('descripcion_corta') }}" maxlength="255"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    @error('descripcion_corta')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción detallada</label>
                    <textarea name="descripcion_detallada" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('descripcion_detallada') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Técnico asignado</label>
                    <input type="text" name="tecnico_asignado" value="{{ old('tecnico_asignado') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach (['pendiente', 'en_curso', 'en_espera', 'cancelada', 'finalizada'] as $st)
                            <option value="{{ $st }}" @selected(old('status', 'pendiente') === $st)>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de reporte *</label>
                    <input type="datetime-local" name="fecha_reporte" value="{{ old('fecha_reporte', now()->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    @error('fecha_reporte')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha promesa</label>
                    <input type="datetime-local" name="fecha_promesa" value="{{ old('fecha_promesa') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Comentarios del técnico</label>
                    <textarea name="comentarios_tecnico" rows="2"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('comentarios_tecnico') }}</textarea>
                </div>
                <div class="sm:col-span-2 mb-3">
                    <label class="form-label">Adjuntar imágenes y documentos</label>
                    <input type="file" name="attachments[]" multiple class="form-control" accept="image/*,.pdf,.doc,.docx,.txt,.xls,.xlsx">
                    <small class="text-muted">Máximo 10 MB por archivo. Puedes seleccionar varios.</small>
                    @error('attachments.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                    Guardar
                </button>
                <a href="{{ route('admin.tickets.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
