<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPengajuan extends Model
{
    use HasFactory;
    
    protected $table = 'dokumen_pengajuans';

    // --- INI ADALAH PERBAIKANNYA ---
    /**
     * Kolom yang boleh diisi (mass assignable).
     */
    protected $fillable = [
        'pengajuan_sidang_id',
        'tipe_dokumen',
        'path_penyimpanan',
        'nama_file_asli',
        'hash_sha512_full',
        'hash_blake2b_full', // Tetap gunakan nama ini, tidak apa-apa
        'hash_combined',
        'signature_data',
        'is_signed',
    ];
    // --- AKHIR PERBAIKAN ---

    /**
     * Relasi: Dokumen ini milik PengajuanSidang mana.
     */
    public function pengajuanSidang()
    {
        return $this->belongsTo(PengajuanSidang::class, 'pengajuan_sidang_id');
    }
}