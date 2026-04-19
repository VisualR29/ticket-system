<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTicketAttachments;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    use HandlesTicketAttachments;

    public function dashboard()
    {
        $misTickets = Ticket::where('cliente_email', auth()->user()->email)
            ->orderBy('fecha_reporte', 'desc')
            ->take(5)
            ->get();

        return view('usuario.dashboard', compact('misTickets'));
    }

    public function index()
    {
        $tickets = Ticket::where('cliente_email', auth()->user()->email)
            ->orderBy('fecha_reporte', 'desc')
            ->get();

        return view('usuario.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('usuario.tickets.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate(array_merge([
            'descripcion_corta' => 'required|max:255',
            'categoria' => 'required|in:software,hardware,comunicaciones,plataformas,email,otro',
            'nivel_urgencia' => 'required|in:baja,media,alta,critica',
            'descripcion_detallada' => 'nullable',
            'departamento' => 'required|max:100',
        ], $this->attachmentValidationRules()));

        $datos['cliente_nombre'] = auth()->user()->name;
        $datos['cliente_email'] = auth()->user()->email;
        $datos['fecha_reporte'] = now();
        $datos['status'] = 'pendiente';

        $ticket = null;

        DB::transaction(function () use ($datos, $request, &$ticket): void {
            $year = date('Y');
            $prefix = "TKT-{$year}-";

            $numeros = Ticket::where('numero_reporte', 'like', $prefix.'%')
                ->lockForUpdate()
                ->pluck('numero_reporte');

            $maxSeq = $numeros->map(function (string $num) use ($prefix) {
                $suffix = substr($num, strlen($prefix));

                return (int) $suffix;
            })->max() ?? 0;

            $datos['numero_reporte'] = $prefix.str_pad((string) ($maxSeq + 1), 4, '0', STR_PAD_LEFT);

            $ticket = Ticket::create(collect($datos)->except(['attachments'])->all());

            $this->processUploadedAttachments($request, $ticket);
        });

        return redirect()->route('usuario.tickets.show', $ticket)
            ->with('success', 'Ticket creado con adjuntos');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->cliente_email !== auth()->user()->email) {
            abort(403, 'No tienes acceso a este ticket.');
        }

        $ticket->load(['attachments', 'aiAnalysis']);

        return view('usuario.tickets.show', compact('ticket'));
    }
}
