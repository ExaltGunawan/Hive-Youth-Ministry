<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rka_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rka_id')->constrained('rkas')->onDelete('cascade');
            $table->string('item_name');
            $table->bigInteger('amount');
            $table->string('category')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rka_details');
    }
};