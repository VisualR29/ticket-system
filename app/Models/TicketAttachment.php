<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TicketAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'original_name',
        'file_path',
        'mime_type',
        'size',
        'type',
    ];

    protected static function booted(): void
    {
        static::deleting(function (TicketAttachment $attachment): void {
            Storage::disk('public')->delete($attachment->file_path);
        });
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
