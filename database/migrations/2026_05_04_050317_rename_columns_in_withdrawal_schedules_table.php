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
            $table->renameColumn('id_pengambil', 'pengambil_id');
            $table->renameColumn('id_sumber', 'rka_detail_id');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawal_schedules', function (Blueprint $table) {
            $table->renameColumn('pengambil_id', 'id_pengambil');
            $table->renameColumn('rka_detail_id', 'id_sumber');
        });
    }
};
