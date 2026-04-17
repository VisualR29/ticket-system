<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'numero_reporte',
        'cliente_nombre',
        'cliente_email',
        'departamento',
        'categoria',
        'nivel_urgencia',
        'descripcion_corta',
        'descripcion_detallada',
        'tecnico_asignado',
        'fecha_reporte',
        'fecha_promesa',
        'fecha_resolucion',
        'comentarios_tecnico',
        'status',
    ];

    protected $casts = [
        'fecha_reporte' => 'datetime',
        'fecha_promesa' => 'datetime',
        'fecha_resolucion' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Ticket $ticket): void {
            $ticket->loadMissing('attachments');
            foreach ($ticket->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        });
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
}
