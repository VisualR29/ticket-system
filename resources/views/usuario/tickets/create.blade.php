@extends('layouts.app')
@section('title', 'Nuevo ticket')
@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Nuevo ticket de soporte</h1>
        <p class="text-sm text-gray-600 mb-6">Describe tu problema. Lo atenderemos según la urgencia indicada.</p>

        <form action="{{ route('usuario.tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Departamento *</label>
                <input type="text" name="departamento" value="{{ old('departamento') }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    placeholder="Ej. Sistemas, Finanzas…" required maxlength="100">
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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Resumen *</label>
                <input type="text" name="descripcion_corta" value="{{ old('descripcion_corta') }}" maxlength="255"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    placeholder="Una línea que describa el problema" required>
                @error('descripcion_corta')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Detalle (opcional)</label>
                <textarea name="descripcion_detallada" rows="4"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    placeholder="Pasos para reproducir, mensajes de error, etc.">{{ old('descripcion_detallada') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Adjuntar imágenes y documentos</label>
                <input type="file" name="attachments[]" multiple class="form-control" accept="image/*,.pdf,.doc,.docx,.txt,.xls,.xlsx">
                <small class="text-muted">Máximo 10 MB por archivo. Puedes seleccionar varios.</small>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                    Enviar ticket
                </button>
                <a href="{{ route('usuario.tickets.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
