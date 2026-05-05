<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. RKA Header table
        Schema::create('rkas', function (Blueprint $table) {
            $table->string('id')->primary(); // MANUAL ID (e.g. Kode Anggaran)
            $table->string('name'); 
            $table->string('title')->nullable(); // Ditambahkan dari squash
            $table->integer('fiscal_year');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        // 2. RKA Items (Details) table
        Schema::create('rka_items', function (Blueprint $table) {
            $table->id(); 
            $table->string('rka_id'); 
            $table->string('manual_id')->nullable(); // Ditambahkan dari squash
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

        // 3. Withdrawal Requests Header
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('withdrawal_date');
            $table->text('notes')->nullable();
            $table->string('status')->default('submitted');
            $table->softDeletes();
            $table->timestamps();
        });

        // 4. Withdrawal Items (Linking to RKA Items)
        Schema::create('withdrawal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('withdrawal_request_id')->constrained('withdrawal_requests')->onDelete('cascade');
            $table->foreignId('rka_item_id')->constrained('rka_items')->onDelete('cascade');
            $table->decimal('requested_amount', 15, 2);
            $table->decimal('actual_amount', 15, 2)->nullable();
            $table->json('proof_images')->nullable();
            $table->timestamps();
        });

        // 5. Withdrawal Comments
        Schema::create('withdrawal_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('withdrawal_request_id')->constrained('withdrawal_requests')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('comment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_comments');
        Schema::dropIfExists('withdrawal_items');
        Schema::dropIfExists('withdrawal_requests');
        Schema::dropIfExists('rka_items');
        Schema::dropIfExists('rkas');
    }
};
