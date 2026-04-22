<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nama_panggilan')->nullable();
            $table->string('instagram')->nullable();
            $table->string('angkatan')->nullable();
            $table->jsonb('etnis')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kontak')->nullable();
            $table->enum('kesibukan', ['kerja', 'kuliah', 'sekolah', 'cuti', 'menganggur'])->nullable();
            $table->text('keterangan')->nullable();
            $table->jsonb('minat_pelayanan')->nullable();
            $table->enum('status_anggota', ['Anggota', 'Simpatisan', 'Unknown'])->default('Unknown');
            $table->string('golongan_darah')->nullable();
            $table->jsonb('hobi_interest')->nullable();
            $table->string('photo')->nullable();  // Path ke file photo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};