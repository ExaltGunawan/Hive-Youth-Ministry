<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pembuat')->constrained('users');
            $table->string('title')->nullable();
            $table->LONGTEXT('value');
            $table->jsonb('allowed_viewers')->nullable();
            $table->text('conclusion')->nullable();
            $table->jsonb('attendance')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};