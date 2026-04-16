<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
// Esta UNICA linea registra las 5 rutas del CRUD automaticamente:
// GET /api/tickets -> TicketController@index
// POST /api/tickets -> TicketController@store
// GET /api/tickets/{ticket} -> TicketController@show
// PUT /api/tickets/{ticket} -> TicketController@update
// DELETE /api/tickets/{ticket} -> TicketController@destroy
Route::apiResource('tickets', TicketController::class);
// Ruta adicional para actualizar solo el status:
Route::patch('tickets/{ticket}/status', [TicketController::class, 'updateStatus']);
