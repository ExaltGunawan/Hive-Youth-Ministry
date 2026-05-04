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
        Schema::table('withdrawal_schedules', function (Blueprint $table) {
            $table->enum('status', ['submitted', 'approved', 'rejected'])->default('submitted')->after('jumlah_diambil');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawal_schedules', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
