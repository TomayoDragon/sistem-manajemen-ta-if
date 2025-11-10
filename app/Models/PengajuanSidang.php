<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSidang extends Model
{
    use HasFactory;
    
    protected $table = 'pengajuan_sidangs';

    /**
     * Kolom yang boleh diisi (mass assignable)
     */
    protected $fillable = [
        'tugas_akhir_id',
        'path_buku_skripsi',
        'path_khs',
        'path_transkrip',
        'status_validasi',
        'catatan_validasi',
        'validator_id',
        'validated_at',
    ];

    /**
     * Relasi: Pengajuan ini milik TA mana.
     */
    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    /**
     * Relasi: Siapa staf yang memvalidasi.
     */
    public function validator()
    {
        return $this->belongsTo(Staff::class, 'validator_id');
    }
}