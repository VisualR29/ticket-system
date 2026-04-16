<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            // Llave primaria auto-incremental
            $table->id();
            // Numero de reporte unico (ej: TKT-2024-0001)
            $table->string('numero_reporte', 20)->unique();
            // Datos del cliente/usuario que reporta
            $table->string('cliente_nombre', 100);
            $table->string('cliente_email', 150)->nullable();
            $table->string('departamento', 100);
            // Clasificacion del ticket
            $table->enum('categoria', [
                'software',
                'hardware',
                'comunicaciones',
                'plataformas',
                'email',
                'otro'
            ]);
            $table->enum('nivel_urgencia', ['baja', 'media', 'alta', 'critica'])
                ->default('media');
            // Informacion del incidente
            $table->string('descripcion_corta', 255);
            $table->text('descripcion_detallada')->nullable();
            // Asignacion y seguimiento
            $table->string('tecnico_asignado', 100)->nullable();
            $table->dateTime('fecha_reporte');
            $table->dateTime('fecha_promesa')->nullable();
            $table->dateTime('fecha_resolucion')->nullable();
            // Comentarios del tecnico
            $table->text('comentarios_tecnico')->nullable();
            // Estado del ticket
            $table->enum('status', [
                'pendiente',
                'en_curso',
                'en_espera',
                'cancelada',
                'finalizada'
            ])->default('pendiente');
            // Timestamps automaticos (created_at, updated_at)
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};