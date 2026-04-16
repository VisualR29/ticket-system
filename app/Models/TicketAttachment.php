<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketAttachment extends Model
{
    use HasFactory;

    // Esto es vital para que puedas guardar los datos masivamente
    protected $fillable = [
        'ticket_id',
        'original_name',
        'file_path',
        'mime_type',
        'size',
        'type',
    ];

    // La relación va AQUÍ ADENTRO
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}