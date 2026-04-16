<?php 

namespace App\Http\Middleware; 

use Closure; 
use Illuminate\Http\Request; 
use Symfony\Component\HttpFoundation\Response; 

class VerificarRol 
{ 
    public function handle(Request $request, Closure $next, string ...$rolesPermitidos): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión.');
        }

        $rolesPermitidos = array_map('trim', $rolesPermitidos);
        $rolUsuario = trim((string) auth()->user()->rol);

        if ($rolUsuario === '' || ! in_array($rolUsuario, $rolesPermitidos, true)) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}