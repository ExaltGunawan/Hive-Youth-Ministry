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
        Schema::table('rka_details', function (Blueprint $table) {
            $table->string('manual_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('rka_details', function (Blueprint $table) {
            $table->dropColumn('manual_id');
        });
    }
};
