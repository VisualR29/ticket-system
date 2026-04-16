<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;


class TicketController extends Controller
{
    // ── GET /api/tickets ────────────────────────────────────────
    // Retorna todos los tickets. En produccion se paginaria.
    public function index(): JsonResponse
    {
        $tickets = Ticket::orderBy('fecha_reporte', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $tickets,
            'total' => $tickets->count(),
        ], 200);
    }
    // ── POST /api/tickets ───────────────────────────────────────
    // Recibe los datos del nuevo ticket y lo guarda en la DB.
    public function store(Request $request)
    {
        // 1. Validación (incluyendo los adjuntos)
        $validated = $request->validate([
            'numero_reporte' => 'required|string|max:20|unique:tickets',
            'cliente_nombre' => 'required|string|max:100',
            'cliente_email' => 'nullable|email|max:150',
            'departamento' => 'required|string|max:100',
            'categoria' => 'required|in:software,hardware,comunicaciones,plataformas,email,otro',
            'nivel_urgencia' => 'required|in:baja,media,alta,critica',
            'descripcion_corta' => 'required|string|max:255',
            'descripcion_detallada' => 'nullable|string',
            'tecnico_asignado' => 'nullable|string|max:100',
            'fecha_reporte' => 'required|date',
            'fecha_promesa' => 'nullable|date|after:fecha_reporte',
            'comentarios_tecnico' => 'nullable|string',
            'status' => 'nullable|in:pendiente,en_curso,en_espera,cancelada,finalizada',
            // Validación de archivos
            'attachments.*' => 'nullable|file|max:10240', // 10MB máx por archivo
        ]);
    
        // 2. Crear el Ticket (quitando los archivos de la data del ticket)
        $validated['status'] = $validated['status'] ?? 'pendiente';
        $ticket = Ticket::create($request->except('attachments'));
    
        // 3. Procesar los archivos si existen
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Guarda el archivo en storage/app/public/ticket-attachments
                $path = $file->store('ticket-attachments', 'public');
    
                // Crea el registro en la base de datos vinculado al ticket
                $ticket->attachments()->create([
                    'original_name' => $file->getClientOriginalName(),
                    'file_path'     => $path,
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'type'          => str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'document',
                ]);
            }
        }
    
        // 4. Respuesta (Si usas web, usa redirect. Si usas API, deja el JSON)
        // Para tu caso de la vista Blade, usamos redirect:
        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket creado exitosamente con sus adjuntos.');
    }
    // ── GET /api/tickets/{ticket} ────────────────────────────────
    // Route Model Binding: Laravel busca automaticamente el ticket por ID.
    // ── GET /admin/tickets/{ticket} ────────────────────────────────
    // Cambiamos JsonResponse por la vista de Blade para ver los adjuntos
    public function show(Ticket $ticket)
    {
        // Cargamos la relación 'attachments' para que la vista pueda iterarlos
        $ticket->load('attachments'); 

        // Retornamos la vista (asegúrate de que la ruta del archivo coincida)
        return view('admin.tickets.show', compact('ticket'));
    }
    // ── PUT/PATCH /api/tickets/{ticket} ─────────────────────────
    // Actualiza los datos del ticket. Solo los campos enviados cambian.
    public function update(Request $request, Ticket $ticket): JsonResponse
    {
        $validated = $request->validate([
            'cliente_nombre' => 'sometimes|string|max:100',
            'cliente_email' => 'sometimes|email|max:150',
            'departamento' => 'sometimes|string|max:100',
            'categoria' =>
            'sometimes|in:software,hardware,comunicaciones,plataformas,email,otro',
            'nivel_urgencia' => 'sometimes|in:baja,media,alta,critica',
            'descripcion_corta' => 'sometimes|string|max:255',
            'descripcion_detallada' => 'sometimes|nullable|string',
            'tecnico_asignado' => 'sometimes|nullable|string|max:100',
            'fecha_promesa' => 'sometimes|nullable|date',
            'fecha_resolucion' => 'sometimes|nullable|date',
            'comentarios_tecnico' => 'sometimes|nullable|string',
            'status' =>
            'sometimes|in:pendiente,en_curso,en_espera,cancelada,finalizada',
        ]);
        $ticket->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Ticket actualizado correctamente.',
            'data' => $ticket->fresh(), // Recarga desde DB
        ], 200);
    }

    public function updateStatus(Request $request, Ticket $ticket): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pendiente,en_curso,en_espera,cancelada,finalizada',
        ]);
        $ticket->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.',
            'data' => $ticket->fresh(),
        ], 200);
    }

    // ── DELETE /api/tickets/{ticket} ─────────────────────────────
    // Elimina el ticket de la base de datos.
    public function destroy(Ticket $ticket): JsonResponse
    {
        $ticket->delete();
        return response()->json([
            'success' => true,
            'message' => 'Ticket eliminado correctamente.',
        ], 200);
    }
}
