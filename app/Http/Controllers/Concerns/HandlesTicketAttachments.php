<?php

namespace App\Http\Controllers\Concerns;

use App\Jobs\AnalyzeTicketAttachmentJob;
use App\Models\Ticket;
use Illuminate\Http\Request;

trait HandlesTicketAttachments
{
    /**
     * @return array<string, mixed>
     */
    protected function attachmentValidationRules(): array
    {
        return [
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240',
        ];
    }

    protected function processUploadedAttachments(Request $request, Ticket $ticket): void
    {
        if (! $request->hasFile('attachments')) {
            return;
        }

        foreach ($request->file('attachments') as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $path = $file->store('ticket-attachments', 'public');
            $mimeType = $file->getMimeType() ?: 'application/octet-stream';

            $attachment = $ticket->attachments()->create([
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $mimeType,
                'size' => (string) $file->getSize(),
                'type' => str_starts_with($mimeType, 'image/') ? 'image' : 'document',
            ]);

            AnalyzeTicketAttachmentJob::dispatch($attachment->id);
        }
    }
}
