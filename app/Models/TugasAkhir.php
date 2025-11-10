<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasAkhir extends Model
{
    use HasFactory; // Pastikan ini ada

    /**
     * Nama tabel
     */
    protected $table = 'tugas_akhirs';

    /**
     * Relasi ke mahasiswa (TugasAkhir ini milik siapa)
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke Dosen Pembimbing 1
     * NAMA METHOD INI (dosenPembimbing1) HARUS SAMA PERSIS
     * DENGAN YANG DIPANGGIL DI CONTROLLER ( ->with('dosenPembimbing1') )
     */
    public function dosenPembimbing1()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_1_id');
    }

    /**
     * Relasi ke Dosen Pembimbing 2
     */
    public function dosenPembimbing2()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_2_id');
    }
}