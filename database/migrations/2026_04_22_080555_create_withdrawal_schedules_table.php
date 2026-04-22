<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('withdrawal_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengambil')->constrained('users');
            $table->foreignId('id_sumber')->constrained('rka_details');
            $table->bigInteger('jumlah_diambil');
            $table->text('notes')->nullable();
            $table->enum('status', ['requested', 'verified', 'completed'])->default('requested');
            $table->foreignId('id_bendahara')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_schedules');
    }
};