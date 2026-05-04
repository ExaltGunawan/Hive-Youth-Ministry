<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenditure_reports', function (Blueprint $table) {
            $table->id();
            $table->string('month');
            $table->integer('year');
            $table->foreignId('withdrawal_schedule_id')->constrained('withdrawal_schedules')->onDelete('cascade');
            $table->bigInteger('actual_amount');
            $table->string('proof_image')->nullable(); // Photo of receipt
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenditure_reports');
    }
};
