<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
    public function store(Request $request): JsonResponse
    {
        // Validacion de datos entrantes
        $validated = $request->validate([
            'numero_reporte' => 'required|string|max:20|unique:tickets',
            'cliente_nombre' => 'required|string|max:100',
            'cliente_email' => 'nullable|email|max:150',
            'departamento' => 'required|string|max:100',
            'categoria' =>
            'required|in:software,hardware,comunicaciones,plataformas,email,otro',
            'nivel_urgencia' => 'required|in:baja,media,alta,critica',
            'descripcion_corta' => 'required|string|max:255',
            'descripcion_detallada' => 'nullable|string',
            'tecnico_asignado' => 'nullable|string|max:100',
            'fecha_reporte' => 'required|date',
            'fecha_promesa' => 'nullable|date|after:fecha_reporte',
            'comentarios_tecnico' => 'nullable|string',
            'status' => 'nullable|in:pendiente,en_curso,en_espera,cancelada,finalizada',
        ]);
        $validated['status'] = $validated['status'] ?? 'pendiente';
        $ticket = Ticket::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Ticket creado exitosamente.',
            'data' => $ticket,
        ], 201); // 201 Created
    }
    // ── GET /api/tickets/{ticket} ────────────────────────────────
    // Route Model Binding: Laravel busca automaticamente el ticket por ID.
    public function show(Ticket $ticket): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $ticket,
        ], 200);
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
