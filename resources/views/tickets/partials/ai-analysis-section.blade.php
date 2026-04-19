@php
    $analysis = $ticket->aiAnalysis;
    $hasAttachments = $ticket->attachments->isNotEmpty();
@endphp
@if ($hasAttachments)
    <div class="pt-4 border-t border-gray-100">
        <div class="flex items-center gap-2 mb-3">
            <h5 class="text-base font-semibold text-gray-900">Análisis asistido (IA)</h5>
            @if ($analysis && $analysis->needs_human_review)
                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-amber-100 text-amber-900">Revisión recomendada</span>
            @endif
        </div>

        @if (! $analysis)
            <div class="rounded-lg border border-dashed border-indigo-200 bg-indigo-50/60 px-4 py-3 text-sm text-indigo-900">
                <p class="font-medium">Generando análisis…</p>
                <p class="mt-1 text-indigo-800/90">
                    Los adjuntos están en cola para análisis automático. Si usas cola en segundo plano, puede tardar unos momentos.
                    <a href="{{ url()->current() }}" class="underline font-medium hover:text-indigo-950">Actualizar página</a>
                </p>
            </div>
        @else
            <div class="rounded-lg border border-indigo-100 bg-gradient-to-b from-indigo-50/80 to-white p-4 space-y-4 text-sm">
                @if ($analysis->executive_summary)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700 mb-1">Resumen ejecutivo</p>
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $analysis->executive_summary }}</p>
                    </div>
                @endif

                @if ($analysis->technical_description)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700 mb-1">Descripción técnica</p>
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $analysis->technical_description }}</p>
                    </div>
                @endif

                @if ($analysis->what_the_image_says)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700 mb-1">Qué muestra el archivo</p>
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $analysis->what_the_image_says }}</p>
                    </div>
                @endif

                @if ($analysis->suggested_category)
                    <div class="flex flex-wrap items-baseline gap-2">
                        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700">Categoría sugerida</p>
                        <p class="font-medium text-gray-900 capitalize">{{ $analysis->suggested_category }}</p>
                    </div>
                @endif

                @if (is_array($analysis->possible_causes) && count($analysis->possible_causes) > 0)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700 mb-2">Posibles causas</p>
                        <ul class="list-disc list-inside space-y-1 text-gray-800">
                            @foreach ($analysis->possible_causes as $cause)
                                <li>{{ $cause }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($analysis->ocr_text)
                    <details class="group">
                        <summary class="cursor-pointer text-xs font-semibold uppercase tracking-wide text-indigo-700 list-none flex items-center gap-1">
                            <span class="group-open:rotate-90 transition-transform inline-block">▸</span>
                            Texto detectado (OCR)
                        </summary>
                        <p class="mt-2 text-gray-700 whitespace-pre-wrap text-xs leading-relaxed max-h-48 overflow-y-auto rounded border border-gray-100 bg-white p-2">{{ $analysis->ocr_text }}</p>
                    </details>
                @endif

                <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-indigo-100/80 text-xs text-gray-600">
                    @if ($analysis->confidence !== null)
                        @php
                            $c = (float) $analysis->confidence;
                            $confPct = ($c >= 0 && $c <= 1) ? $c * 100 : $c;
                        @endphp
                        <span>
                            Confianza:
                            <span class="font-semibold text-gray-900">{{ number_format($confPct, 0) }}%</span>
                        </span>
                    @endif
                    @if ($analysis->updated_at)
                        <span>Actualizado: {{ $analysis->updated_at->format('d/m/Y H:i') }}</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endif
