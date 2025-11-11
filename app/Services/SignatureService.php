<?php

namespace App\Services;

use App\Models\PengajuanSidang;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile; // Penting untuk verifikasi

class SignatureService
{
    /**
     * Mengambil konten mentah (raw) dari 3 file saat UPLOAD.
     */
    public function getConcatenatedFileContentsFromRequest(Request $request): string
    {
        $contents = $request->file('buku_skripsi')->get() . 
                    $request->file('khs')->get() . 
                    $request->file('transkrip')->get();
        return $contents;
    }

    /**
     * Mengambil konten mentah (raw) dari 3 file saat VERIFIKASI.
     */
    public function getConcatenatedFileContentsFromVerification(Request $request): string
    {
        $contents = $request->file('buku_cek')->get() . 
                    $request->file('khs_cek')->get() . 
                    $request->file('transkrip_cek')->get();
        return $contents;
    }

    /**
     * Implementasi DUMMY Hashing Kustom Anda
     * MENGGUNAKAN SHA-512 + SHA3-512 (Pengganti BLAKE2b)
     */
    public function performCustomHash(string $content): array
    {
        // 1. Hitung hash penuh (PENTING: 'true' untuk output biner mentah)
        $sha512_full_raw = hash('sha512', $content, true);
        $sha3_512_full_raw = hash('sha3-512', $content, true); 

        // 2. Ambil 32 byte pertama (256-bit)
        $sha512_32bytes = substr($sha512_full_raw, 0, 32);
        $sha3_32bytes = substr($sha3_512_full_raw, 0, 32);

        // 3. Gabungkan 2 hash biner (total 64 byte)
        $combined_raw = $sha512_32bytes . $sha3_32bytes;

        // 4. Kembalikan versi heksadesimal & biner
        return [
            'sha512_full_hex' => bin2hex($sha512_full_raw),
            'blake2b_full_hex' => bin2hex($sha3_512_full_raw), // Kita tetap simpan di kolom 'blake2b'
            'combined_hex' => bin2hex($combined_raw),
            'combined_raw_for_signing' => $combined_raw,
        ];
    }

    /**
     * Implementasi DUMMY EdDSA Signing.
     */
    public function simulateEdDSASigning(string $combinedHashRaw): string
    {
        // Simulasi: Mengembalikan hash acak yang dikodekan sebagai binary
        return hash('sha256', $combinedHashRaw . 'kunci_rahasia_mahasiswa', true); 
    }
}