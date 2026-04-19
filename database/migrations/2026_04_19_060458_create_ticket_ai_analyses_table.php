<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ticket_ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->text('technical_description')->nullable();
            $table->longText('ocr_text')->nullable();
            $table->string('suggested_category')->nullable();
            $table->json('possible_causes')->nullable();
            $table->text('executive_summary')->nullable();
            $table->text('what_the_image_says')->nullable();
            $table->decimal('confidence', 5, 2)->nullable();
            $table->boolean('needs_human_review')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_ai_analyses');
    }
};