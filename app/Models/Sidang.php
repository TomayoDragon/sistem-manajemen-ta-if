<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sidang extends Model {
    use HasFactory;
    protected $fillable = [
        'tugas_akhir_id',
        'pengajuan_sidang_id',
        'dosen_penguji_ketua_id',
        'dosen_penguji_sekretaris_id',
        'jadwal',
        'ruangan',
        'status',
    ];
    public function tugasAkhir() {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }
    public function pengajuanSidang() {
        return $this->belongsTo(PengajuanSidang::class, 'pengajuan_sidang_id');
    }
    public function beritaAcara() {
        return $this->hasOne(BeritaAcara::class, 'sidang_id'); // Sidang punya 1 BA
    }

    public function lembarPenilaians()
    {
        return $this->morphMany(LembarPenilaian::class, 'penilaian');
    }
}