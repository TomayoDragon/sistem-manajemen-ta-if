<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LembarPenilaian extends Model
{
    use HasFactory;

    protected $table = 'lembar_penilaians';
    
    protected $fillable = [
        'dosen_id',
        'penilaian_type',
        'penilaian_id',
        'nilai_materi',
        'nilai_sistematika',
        'nilai_mempertahankan',
        'nilai_pengetahuan_bidang',
        'nilai_karya_ilmiah',
        'komentar_revisi',
    ];

    /**
     * Relasi ke Dosen yang menilai.
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    /**
     * Relasi polimorfik (bisa ke Lsta atau Sidang).
     */
    public function penilaian()
    {
        return $this->morphTo();
    }
}