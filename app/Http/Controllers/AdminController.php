<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard: cuenta tickets por estado 
    public function dashboard()
    {
        $estadisticas = [
            'total'      => Ticket::count(),
            'pendientes' => Ticket::where('status', 'pendiente')->count(),
            'en_curso'   => Ticket::where('status', 'en_curso')->count(),
            'finalizados' => Ticket::where('status', 'finalizada')->count(),
        ];
        return view('admin.dashboard', compact('estadisticas'));
    }
    // Lista todos los usuarios 
    public function usuarios()
    {
        $usuarios = User::orderBy('name')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }
    // Ver detalle de un usuario 
    public function verUsuario(User $user)
    {
        return view('admin.usuarios.show', compact('user'));
    }
    // Cambiar el rol de un usuario 
    public function cambiarRol(Request $request, User $user)
    {
        $request->validate(['rol' => 'required|in:admin,gerente,usuario']);
        $user->update(['rol' => $request->rol]);
        return back()->with('success', 'Rol actualizado correctamente.');
    }

    // Eliminar usuario 
    public function eliminarUsuario(User $user)
    {
        $user->delete();
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado.');
    }
}
