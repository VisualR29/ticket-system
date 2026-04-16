@extends('layouts.app')
@section('title', 'Reportes')
@section('content')
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Reportes</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Tickets por categoría</h2>
            @if ($porCategoria->isEmpty())
                <p class="text-gray-500 text-sm">Sin datos.</p>
            @else
                <ul class="space-y-2">
                    @foreach ($porCategoria as $row)
                        <li class="flex justify-between text-sm border-b border-gray-100 pb-2">
                            <span class="capitalize">{{ $row->categoria }}</span>
                            <span class="font-medium">{{ $row->total }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Tickets por urgencia</h2>
            @if ($porUrgencia->isEmpty())
                <p class="text-gray-500 text-sm">Sin datos.</p>
            @else
                <ul class="space-y-2">
                    @foreach ($porUrgencia as $row)
                        <li class="flex justify-between text-sm border-b border-gray-100 pb-2">
                            <span class="capitalize">{{ $row->nivel_urgencia }}</span>
                            <span class="font-medium">{{ $row->total }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
