<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketWebController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GerenteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProfileController;

// ─────────────────────────────────────────────────────
// RUTAS PÚBLICAS (sin autenticación)
// ─────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación (login, register, logout)
// Estas son generadas automáticamente por Breeze
require __DIR__ . '/auth.php';

// ─────────────────────────────────────────────────────
// RUTAS PROTEGIDAS: Solo usuarios autenticados
// ─────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard: redirige según el rol del usuario
    Route::get('/dashboard', function () {
        return match (auth()->user()->rol) {
            'admin'   => redirect()->route('admin.dashboard'),
            'gerente' => redirect()->route('gerente.dashboard'),
            default   => redirect()->route('usuario.dashboard'),
        };
    })->name('dashboard');

    Route::middleware(['rol:admin,gerente'])
        ->patch('/tickets/{ticket}/cerrar', [TicketWebController::class, 'close'])
        ->name('tickets.cerrar');

    // ───────────────────────────────────────────────
    // RUTAS ADMIN (solo rol: admin)
    // ───────────────────────────────────────────────
    Route::middleware(['rol:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Dashboard del admin
            Route::get('/dashboard', [AdminController::class, 'dashboard'])
                ->name('dashboard');

            // CRUD completo de tickets
            Route::resource('tickets', TicketWebController::class);

            // Gestión de usuarios
            Route::get('/usuarios', [AdminController::class, 'usuarios'])
                ->name('usuarios.index');

            Route::get('/usuarios/{user}', [AdminController::class, 'verUsuario'])
                ->name('usuarios.show');

            Route::patch('/usuarios/{user}/cambiar-rol', [
                AdminController::class,
                'cambiarRol'
            ])->name('usuarios.cambiar-rol');

            Route::delete('/usuarios/{user}', [
                AdminController::class,
                'eliminarUsuario'
            ])->name('usuarios.destroy');
        });

    // ───────────────────────────────────────────────
    // RUTAS GERENTE (roles: admin, gerente)
    // ───────────────────────────────────────────────
    Route::middleware(['rol:admin,gerente'])
        ->prefix('gerente')
        ->name('gerente.')
        ->group(function () {
            Route::get('/dashboard', [GerenteController::class, 'dashboard'])
                ->name('dashboard');

            Route::get('/reportes', [GerenteController::class, 'reportes'])
                ->name('reportes');

            Route::get('/tickets', [GerenteController::class, 'verTodos'])
                ->name('tickets.index');
        });

    // ───────────────────────────────────────────────
    // RUTAS USUARIO (roles: admin, gerente, usuario)
    // ───────────────────────────────────────────────
    Route::middleware(['rol:admin,gerente,usuario'])
        ->prefix('mis-tickets')
        ->name('usuario.')
        ->group(function () {

            Route::get('/dashboard', [UsuarioController::class, 'dashboard'])
                ->name('dashboard');

            Route::get('/', [UsuarioController::class, 'index'])
                ->name('tickets.index');

            Route::get('/crear', [UsuarioController::class, 'create'])
                ->name('tickets.create');

            Route::post('/', [UsuarioController::class, 'store'])
                ->name('tickets.store');

            Route::get('/{ticket}', [UsuarioController::class, 'show'])
                ->name('tickets.show');
        });
});
