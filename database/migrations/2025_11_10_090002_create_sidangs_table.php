<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sidangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhirs')->onDelete('cascade');
            $table->foreignId('pengajuan_sidang_id')->constrained('pengajuan_sidangs')->onDelete('cascade');
            
            // 2 Dosen Penguji (Ketua & Sekretaris, sesuai Gambar 3.1)
            $table->foreignId('dosen_penguji_ketua_id')->constrained('dosens')->onDelete('cascade');
            $table->foreignId('dosen_penguji_sekretaris_id')->constrained('dosens')->onDelete('cascade');
            // 2 Dosen Pembimbing sudah ada di tabel 'tugas_akhirs', tidak perlu di-copy
            
            $table->dateTime('jadwal');
            $table->string('ruangan');
            
            // Status untuk Sidang
            $table->enum('status', ['TERJADWAL', 'LULUS', 'LULUS_REVISI', 'TIDAK_LULUS'])->default('TERJADWAL');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('sidangs');
    }
};