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
        // Ini adalah tabel "Paket Pengajuan"
        Schema::create('pengajuan_sidangs', function (Blueprint $table) {
            $table->id();

            // Paket ini milik TA siapa
            $table->foreignId('tugas_akhir_id')
                  ->constrained('tugas_akhirs')
                  ->onDelete('cascade');

            // 3 File Wajib
            $table->string('path_buku_skripsi'); // Path file buku
            $table->string('path_khs');          // Path file KHS
            $table->string('path_transkrip');    // Path file Transkrip

            // Validasi Staf untuk 1 paket
            $table->enum('status_validasi', ['PENDING', 'TERIMA', 'TOLAK'])
                  ->default('PENDING');
            
            $table->text('catatan_validasi')->nullable(); // Catatan jika 1 paket ditolak

            // Siapa Staf yang memvalidasi
            $table->foreignId('validator_id')->nullable()
                  ->constrained('staff')
                  ->onDelete('set null');

            $table->timestamp('validated_at')->nullable();
            $table->timestamps(); // Kapan paket ini di-submit
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_sidangs');
    }
};