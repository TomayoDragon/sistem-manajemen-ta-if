<?php

namespace App\Services;

use App\Models\Mahasiswa; // <-- IMPORT MAHASISWA
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt; // <-- IMPORT CRYPT
use Illuminate\Support\Facades\Auth;

class SignatureService
{
    // =================================================================
    // LANGKAH 1: MANAJEMEN KUNCI (DIPANGGIL OTOMATIS)
    // =================================================================

    /**
     * Membuat sepasang kunci Ed25519 (Public & Private) baru.
     * Enkripsi Private Key dan simpan ke database mahasiswa.
     * Method ini akan dipanggil oleh Model Event 'created' di Mahasiswa.php
     */
    public function generateAndStoreKeys(Mahasiswa $mahasiswa)
    {
        // 1. Buat sepasang kunci Ed25519 (Public & Private)
        $keyPair = sodium_crypto_sign_keypair();
        
        // 2. Pisahkan kuncinya
        $publicKey = sodium_crypto_sign_publickey($keyPair);
        $privateKey = sodium_crypto_sign_secretkey($keyPair);

        // 3. Enkripsi Private Key menggunakan APP_KEY Laravel
        $encryptedPrivateKey = Crypt::encryptString(base64_encode($privateKey));

        // 4. Simpan ke database (langsung, tanpa 'save()' di sini)
        // Kita gunakan updateQuietly agar tidak memicu event 'updated'
        $mahasiswa->updateQuietly([
            'public_key' => base64_encode($publicKey),
            'private_key_encrypted' => $encryptedPrivateKey
        ]);
        
        // 5. Hapus kunci mentah dari memori
        sodium_memzero($privateKey);
        sodium_memzero($keyPair);
    }

    // =================================================================
    // LANGKAH 2: HASHING (NOVETLI ANDA)
    // =================================================================

    /**
     * (Untuk Verifikasi) Mengambil konten mentah dari 3 file saat VERIFIKASI.
     */
    public function getConcatenatedFileContentsFromVerification(Request $request): string
    {
        $contents = $request->file('buku_cek')->get() . 
                    $request->file('khs_cek')->get() . 
                    $request->file('transkrip_cek')->get();
        return $contents;
    }

    /**
     * Implementasi Hashing Kustom Anda (SHA-512 + SHA3-512)
     */
    public function performCustomHash(string $content): array
    {
        $sha512_full_raw = hash('sha512', $content, true);
        $sha3_512_full_raw = hash('sha3-512', $content, true); 

        $sha512_32bytes = substr($sha512_full_raw, 0, 32);
        $sha3_32bytes = substr($sha3_512_full_raw, 0, 32);

        $combined_raw = $sha512_32bytes . $sha3_32bytes;

        return [
            'sha512_full_hex' => bin2hex($sha512_full_raw),
            'blake2b_full_hex' => bin2hex($sha3_512_full_raw),
            'combined_hex' => bin2hex($combined_raw),
            'combined_raw_for_signing' => $combined_raw,
        ];
    }

    // =================================================================
    // LANGKAH 3: SIGNING (IMPLEMENTASI ASLI)
    // =================================================================

    /**
     * IMPLEMENTASI ASLI EdDSA Signing (menggunakan Sodium).
     */
    public function performRealEdDSASigning(string $combinedHashRaw, Mahasiswa $mahasiswa): string
    {
        $encryptedPrivateKey = $mahasiswa->private_key_encrypted;
        if (!$encryptedPrivateKey) {
            throw new \Exception("Mahasiswa ini tidak memiliki private key. Kunci mungkin gagal di-generate.");
        }

        $privateKey = base64_decode(Crypt::decryptString($encryptedPrivateKey));
        $signature = sodium_crypto_sign_detached($combinedHashRaw, $privateKey);
        sodium_memzero($privateKey);

        return $signature; 
    }
}