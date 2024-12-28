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
        Schema::create('skps', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('atasan_id')->nullable()->constrained('atasans')->onDelete('cascade');
            $table->foreignId('skp_atasan_id')->nullable()->constrained('skp_atasan')->onDelete('cascade');
            $table->string('unit_kerja');
            $table->integer('tahun'); // Tahun SKP
            $table->string('module'); // Module (kuantitatif/kualitatif)
            $table->date('tanggal_skp'); // Tanggal awal SKP
            $table->date('tanggal_akhir')->nullable();
            $table->enum('status', ['pending', 'approve', 'revisi'])->default('pending'); // Tanggal akhir otomatis akhir tahun
            $table->boolean('is_submitted')->default(false);
            $table->boolean('is_active')->default(false);
            $table->date('submitted_at')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('keterangan_revisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skps');
    }
};