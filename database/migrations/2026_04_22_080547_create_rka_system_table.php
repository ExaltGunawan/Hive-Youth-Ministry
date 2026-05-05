<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Create RKA Header table with Manual IDs
        Schema::create('rkas', function (Blueprint $table) {
            $table->string('id')->primary(); // MANUAL ID (e.g. Kode Anggaran)
            $table->string('name'); 
            $table->integer('fiscal_year');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        // Create RKA Items (Details) table
        Schema::create('rka_items', function (Blueprint $table) {
            $table->id(); 
            $table->string('rka_id'); 
            $table->foreign('rka_id')
                ->references('id')
                ->on('rkas')
                ->onUpdate('cascade') 
                ->onDelete('cascade');
            $table->string('item_name');
            $table->decimal('price', 15, 2);
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rka_items');
        Schema::dropIfExists('rkas');
    }
};
