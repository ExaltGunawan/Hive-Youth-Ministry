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
            $table->bigInteger('balance')->nullable()->after('amount');
        });

        // Initialize balance with amount for existing records
        \DB::table('rka_details')->update(['balance' => \DB::raw('amount')]);
    }

    public function down(): void
    {
        Schema::table('rka_details', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
};
