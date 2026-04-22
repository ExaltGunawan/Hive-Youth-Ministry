<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_id')->constrained('divisi');
            $table->string('schedule_name');
            $table->string('sub_schedule')->nullable();
            $table->string('tempat');
            $table->string('sub_tempat')->nullable();
            $table->time('jam');
            $table->date('tanggal');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};