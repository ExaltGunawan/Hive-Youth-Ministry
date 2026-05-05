<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('withdrawal_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Pengambil
            $table->foreignId('rka_item_id')->nullable()->constrained('rka_items')->onDelete('cascade');
            $table->bigInteger('amount');
            $table->text('notes')->nullable();
            $table->string('status')->default('requested'); // Simple string status
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_schedules');
    }
};