<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- TAMBAHKAN INI
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory; // <-- DAN TAMBAHKAN INI

    // (fillable, dll. bisa ditambahkan di sini jika perlu)

    /**
     * Relasi ke Tugas Akhir (Satu mahasiswa bisa punya banyak TA, misal ganti judul)
     */
    public function tugasAkhirs()
    {
        return $this->hasMany(TugasAkhir::class, 'mahasiswa_id');
    }
}