<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            // Berita Acara ini milik 1 Sidang
            $table->foreignId('sidang_id')->unique()->constrained('sidangs')->onDelete('cascade');
            
            // Data dari Gambar 3.2
            $table->decimal('jumlah_nilai_mentah_nma', 6, 2)->nullable();
            $table->decimal('rata_rata_nma', 5, 2)->nullable();
            $table->string('nilai_relatif_nr', 2)->nullable();
            $table->string('hasil_ujian')->nullable(); // LULUS / TIDAK LULUS
            
            $table->string('path_file_generated')->nullable(); // Path PDF BA
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('berita_acaras');
    }
};