<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTicketAttachments;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketWebController extends Controller
{
    use HandlesTicketAttachments;

    public function index()
    {
        $tickets = Ticket::orderBy('fecha_reporte', 'desc')->get();

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(array_merge([
            'numero_reporte' => 'required|string|max:20|unique:tickets,numero_reporte',
            'cliente_nombre' => 'required|string|max:100',
            'cliente_email' => 'nullable|email|max:150',
            'departamento' => 'required|string|max:100',
            'categoria' => 'required|in:software,hardware,comunicaciones,plataformas,email,otro',
            'nivel_urgencia' => 'required|in:baja,media,alta,critica',
            'descripcion_corta' => 'required|string|max:255',
            'descripcion_detallada' => 'nullable|string',
            'tecnico_asignado' => 'nullable|string|max:100',
            'fecha_reporte' => 'required|date',
            'fecha_promesa' => 'nullable|date|after_or_equal:fecha_reporte',
            'comentarios_tecnico' => 'nullable|string',
            'status' => 'in:pendiente,en_curso,en_espera,cancelada,finalizada',
        ], $this->attachmentValidationRules()));

        $validated['status'] = $validated['status'] ?? 'pendiente';

        $ticket = Ticket::create(collect($validated)->except(['attachments'])->all());

        $this->processUploadedAttachments($request, $ticket);

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket creado con adjuntos');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('attachments');

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate(array_merge([
            'cliente_nombre' => 'required|string|max:100',
            'cliente_email' => 'nullable|email|max:150',
            'departamento' => 'required|string|max:100',
            'categoria' => 'required|in:software,hardware,comunicaciones,plataformas,email,otro',
            'nivel_urgencia' => 'required|in:baja,media,alta,critica',
            'descripcion_corta' => 'required|string|max:255',
            'descripcion_detallada' => 'nullable|string',
            'tecnico_asignado' => 'nullable|string|max:100',
            'fecha_promesa' => 'nullable|date',
            'fecha_resolucion' => 'nullable|date',
            'comentarios_tecnico' => 'nullable|string',
            'status' => 'required|in:pendiente,en_curso,en_espera,cancelada,finalizada',
        ], $this->attachmentValidationRules()));

        $ticket->update(collect($validated)->except(['attachments'])->all());

        $this->processUploadedAttachments($request, $ticket);

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket actualizado correctamente.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket eliminado.');
    }

    public function close(Ticket $ticket)
    {
        if (! in_array($ticket->status, ['pendiente', 'en_curso'], true)) {
            return redirect()->back()
                ->with(
                    'error',
                    'Solo se pueden cerrar tickets en estado pendiente o en curso. Este ticket está en estado «'.str_replace('_', ' ', $ticket->status).'».'
                );
        }

        $ticket->update([
            'status' => 'finalizada',
            'fecha_resolucion' => now(),
        ]);

        $listado = auth()->user()->rol === 'gerente'
            ? 'gerente.tickets.index'
            : 'admin.tickets.index';

        return redirect()->route($listado)
            ->with('success', 'Ticket cerrado correctamente.');
    }
}
