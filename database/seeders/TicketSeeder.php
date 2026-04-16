<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $tickets = [
            [
                'numero_reporte' => 'TKT-2024-0001',
                'cliente_nombre' => 'Pedro Sanchez',
                'departamento' => 'Finanzas',
                'categoria' => 'hardware',
                'nivel_urgencia' => 'critica',
                'descripcion_corta' => 'Laptop sin encender desde esta manana',
                'tecnico_asignado' => 'Luis Torres',
                'fecha_reporte' => '2024-01-15 08:00:00',
                'fecha_promesa' => '2024-01-15 12:00:00',
                'status' => 'en_curso',
            ],
            [
                'numero_reporte' => 'TKT-2024-0002',
                'cliente_nombre' => 'Laura Gonzalez',
                'departamento' => 'Ventas',
                'categoria' => 'email',
                'nivel_urgencia' => 'media',
                'descripcion_corta' => 'No recibo correos desde ayer tarde',
                'fecha_reporte' => '2024-01-15 09:15:00',
                'fecha_promesa' => '2024-01-16 09:00:00',
                'status' => 'pendiente',
            ],
        ];
        foreach ($tickets as $t) {
            Ticket::create($t);
        }
        $this->command->info('Tickets de prueba creados: ' . count($tickets));
    }
}
