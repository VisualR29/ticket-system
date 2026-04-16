<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class GerenteController extends Controller
{
    public function dashboard()
    {
        $resumen = [
            'total'      => Ticket::count(),
            'pendientes' => Ticket::where('status', 'pendiente')->count(),
            'en_curso'   => Ticket::where('status', 'en_curso')->count(),
            'criticos'   => Ticket::where('nivel_urgencia', 'critica')->count(),
        ];
        return view('gerente.dashboard', compact('resumen'));
    }

    public function reportes()
    {
        $porCategoria  = Ticket::selectRaw('categoria, count(*) as total')
            ->groupBy('categoria')->get();
        $porUrgencia   = Ticket::selectRaw('nivel_urgencia, count(*) as total')
            ->groupBy('nivel_urgencia')->get();
        return view('gerente.reportes', compact('porCategoria', 'porUrgencia'));
    }
    public function verTodos()
    {
        $tickets = Ticket::orderBy('fecha_reporte', 'desc')->get();
        return view('gerente.tickets', compact('tickets'));
    }
}
