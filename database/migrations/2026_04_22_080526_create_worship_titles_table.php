<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('worship_titles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worship_theme_id')->constrained('worship_themes')->onDelete('cascade');
            $table->date('date');
            $table->string('title');
            $table->string('scripture')->nullable();
            $table->text('background_context')->nullable();
            $table->text('objective')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worship_titles');
    }
};