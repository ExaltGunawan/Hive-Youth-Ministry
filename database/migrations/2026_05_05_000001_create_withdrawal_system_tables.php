<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Withdrawal Requests Header
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pengaju
            $table->date('withdrawal_date'); // Tanggal Pengambilan
            $table->text('notes')->nullable();
            $table->string('status')->default('submitted'); // submitted, more_info, approved, actualized
            $table->softDeletes();
            $table->timestamps();
        });

        // 2. Withdrawal Items (Linking to RKA Items)
        Schema::create('withdrawal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('withdrawal_request_id')->constrained('withdrawal_requests')->onDelete('cascade');
            $table->foreignId('rka_item_id')->constrained('rka_items')->onDelete('cascade');
            $table->decimal('requested_amount', 15, 2);
            $table->decimal('actual_amount', 15, 2)->nullable();
            $table->json('proof_images')->nullable();
            $table->timestamps();
        });

        // 3. Discussion/Comments for "More Info"
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
    }
};
