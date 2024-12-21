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
        Schema::create('kegiatan_harians', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rencana_pegawai_id')->constrained('rencana_hasil_kerja_pegawai')->onDelete('cascade');
            $table->foreignId('atasan_id')->constrained('atasans')->onDelete('cascade');
            $table->string('uraian');
            $table->enum('jenis_kegiatan', ['tugas_pokok', 'tugas_tambahan', 'dinas_luar', 'bebas_piket']);
            $table->string('output');
            $table->string('jumlah');
            $table->string('biaya');
            $table->string('evidence');
            $table->boolean('is_draft')->default(false);
            $table->enum('status', ['pending', 'approve', 'revisi'])->default('pending');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('penilaian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_harians');
    }
};