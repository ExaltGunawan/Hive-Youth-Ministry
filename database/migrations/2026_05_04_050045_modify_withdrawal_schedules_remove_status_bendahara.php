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
            $table->dropColumn('status');
            $table->dropForeign(['id_bendahara']);
            $table->dropColumn('id_bendahara');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawal_schedules', function (Blueprint $table) {
            $table->enum('status', ['requested', 'verified', 'completed'])->default('requested')->after('notes');
            $table->foreignId('id_bendahara')->nullable()->after('status')->constrained('users');
        });
    }
};
