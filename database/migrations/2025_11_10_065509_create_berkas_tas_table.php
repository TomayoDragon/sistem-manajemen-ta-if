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
        Schema::create('berkas_tas', function (Blueprint $table) {
            $table->id();

            // Relasi ke TA mana berkas ini dimiliki
            $table->foreignId('tugas_akhir_id')
                  ->constrained('tugas_akhirs')
                  ->onDelete('cascade');
            
            // --- Info Berkas ---
            $table->string('nama_file_asli'); // Nama file dari komputer user
            $table->string('path_penyimpanan'); // Lokasi file di server
            $table->string('tipe_berkas')->default('DRAFT_SIDANG'); // Nanti bisa ditambah 'LAPORAN_FINAL'

            // --- Fitur Validasi Staf (Sesuai Bab 1 Anda) ---
            $table->enum('status_validasi', ['PENDING', 'TERIMA', 'TOLAK'])
                  ->default('PENDING');
            
            $table->text('catatan_validasi')->nullable(); // Komentar revisi dari Staf

            // Siapa Staf yang memvalidasi
            $table->foreignId('validator_id')->nullable()
                  ->constrained('staff') // Merujuk ke tabel 'staff'
                  ->onDelete('set null');

            $table->timestamp('validated_at')->nullable(); // Kapan divalidasi

            $table->timestamps(); // Kapan di-upload (created_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_tas');
    }
};