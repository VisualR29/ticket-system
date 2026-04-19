<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAiAnalysis extends Model
{
    protected $fillable = [
        'ticket_id',
        'technical_description',
        'ocr_text',
        'suggested_category',
        'possible_causes',
        'executive_summary',
        'what_the_image_says',
        'confidence',
        'needs_human_review',
    ];

    protected $casts = [
        'possible_causes' => 'array',
        'needs_human_review' => 'boolean',
        'confidence' => 'decimal:2',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}