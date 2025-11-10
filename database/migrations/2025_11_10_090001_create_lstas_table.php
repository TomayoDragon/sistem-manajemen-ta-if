<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lstas', function (Blueprint $table) {
            $table->id();
            // LSTA ini terhubung ke TA mana
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhirs')->onDelete('cascade');
            // LSTA ini dibuat berdasarkan paket pengajuan yang mana
            $table->foreignId('pengajuan_sidang_id')->constrained('pengajuan_sidangs')->onDelete('cascade');
            
            // 1 Dosen Penguji (Requirement 3)
            $table->foreignId('dosen_penguji_id')->constrained('dosens')->onDelete('cascade');
            
            $table->dateTime('jadwal');
            $table->string('ruangan');
            
            // Status untuk LSTA (Lulus / Tidak Lulus / Masih dijadwalkan)
            $table->enum('status', ['TERJADWAL', 'LULUS', 'TIDAK_LULUS'])->default('TERJADWAL');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('lstas');
    }
};