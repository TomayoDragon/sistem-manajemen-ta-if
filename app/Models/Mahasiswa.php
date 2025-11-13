<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\SignatureService; // <-- IMPORT SERVICE

class Mahasiswa extends Model
{
    use HasFactory;
    
    protected $table = 'mahasiswas';

    // Pastikan $fillable Anda ada dan benar
    protected $fillable = [
        'nrp',
        'nama_lengkap',
        // Kolom 'public_key' & 'private_key_encrypted' tidak perlu di sini
        // karena kita mengisinya dengan 'updateQuietly'
    ];
    
    /**
     * The "booted" method of the model.
     * Ini adalah *event listener* otomatis.
     */
    protected static function booted(): void
    {
        /**
         * Setiap kali seorang Mahasiswa BARU (created) dibuat,
         * panggil SignatureService untuk men-generate kunci untuknya.
         */
        static::created(function (Mahasiswa $mahasiswa) {
            // Dapatkan service dari container Laravel
            $signatureService = app(SignatureService::class);
            
            // Panggil logic pembuatan kunci
            $signatureService->generateAndStoreKeys($mahasiswa);
        });
    }

    /**
     * Relasi ke User (Login)
     */
    public function user()
    {
        return $this->hasOne(User::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke Tugas Akhir
     */
    public function tugasAkhirs()
    {
        return $this->hasMany(TugasAkhir::class, 'mahasiswa_id');
    }
}