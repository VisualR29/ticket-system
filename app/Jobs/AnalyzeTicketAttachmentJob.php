<?php

namespace App\Jobs;

use App\Models\TicketAiAnalysis;
use App\Models\TicketAttachment;
use App\Services\OpenAITicketAnalyzer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AnalyzeTicketAttachmentJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $attachmentId)
    {
    }

    public function handle(OpenAITicketAnalyzer $analyzer): void
    {
        $attachment = TicketAttachment::find($this->attachmentId);

        if (!$attachment) {
            return;
        }

        try {
            $result = $analyzer->analyze($attachment->file_path);

            TicketAiAnalysis::updateOrCreate(
                ['ticket_id' => $attachment->ticket_id],
                [
                    'technical_description' => $result['technical_description'] ?? null,
                    'ocr_text' => $result['ocr_text'] ?? null,
                    'suggested_category' => $result['suggested_category'] ?? null,
                    'possible_causes' => $result['possible_causes'] ?? [],
                    'executive_summary' => $result['executive_summary'] ?? null,
                    'what_the_image_says' => $result['what_the_image_says'] ?? null,
                    'confidence' => $result['confidence'] ?? 0,
                    'needs_human_review' => $result['needs_human_review'] ?? false,
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Error en análisis IA del adjunto', [
                'attachment_id' => $attachment->id,
                'ticket_id' => $attachment->ticket_id,
                'message' => $e->getMessage(),
            ]);

            TicketAiAnalysis::updateOrCreate(
                ['ticket_id' => $attachment->ticket_id],
                [
                    'technical_description' => 'No se pudo completar el análisis automático.',
                    'ocr_text' => null,
                    'suggested_category' => 'Pendiente de revisión',
                    'possible_causes' => [],
                    'executive_summary' => 'El sistema no pudo analizar el archivo automáticamente.',
                    'what_the_image_says' => null,
                    'confidence' => 0,
                    'needs_human_review' => true,
                ]
            );
        }
    }
}