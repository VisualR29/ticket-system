@if ($ticket->attachments->isNotEmpty())
    <div class="pt-4 border-t border-gray-100">
        <h5 class="text-base font-semibold text-gray-900 mb-3">Adjuntos del ticket</h5>
        <div class="flex flex-wrap -mx-2">
            @foreach ($ticket->attachments as $attachment)
                <div class="w-full sm:w-1/2 md:w-1/4 px-2 mb-3">
                    @if (str_starts_with($attachment->mime_type, 'image/'))
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($attachment->file_path) }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($attachment->file_path) }}"
                                class="w-full rounded shadow-sm max-h-[180px] object-cover"
                                alt="{{ $attachment->original_name }}">
                        </a>
                    @else
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($attachment->file_path) }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex w-full items-center justify-center rounded border border-indigo-600 px-3 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50 truncate">
                            {{ $attachment->original_name }}
                        </a>
                    @endif
                    <small class="mt-1 block text-xs text-gray-500 truncate" title="{{ $attachment->original_name }}">{{ $attachment->original_name }}</small>
                </div>
            @endforeach
        </div>
    </div>
@endif
