<?php

namespace App\Services;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class SignatureService
{
    public function generateAndStoreKeys(Mahasiswa $mahasiswa)
    {
        $keyPair = sodium_crypto_sign_keypair();
        $publicKey = sodium_crypto_sign_publickey($keyPair);
        $privateKey = sodium_crypto_sign_secretkey($keyPair);
        $encryptedPrivateKey = Crypt::encryptString(base64_encode($privateKey));
        $mahasiswa->updateQuietly([
            'public_key' => base64_encode($publicKey),
            'private_key_encrypted' => $encryptedPrivateKey
        ]);
        sodium_memzero($privateKey);
        sodium_memzero($keyPair);
    }

    public function getConcatenatedFileContentsFromVerification(Request $request): string
    {
        $contents = $request->file('buku_cek')->get() . 
                    $request->file('khs_cek')->get() . 
                    $request->file('transkrip_cek')->get();
        return $contents;
    }

    public function performCustomHash(string $content): array
    {
        $sha512_full_raw = hash('sha512', $content, true);
        $blake2b_full_raw = sodium_crypto_generichash($content, '', 64); 

        $sha512_32bytes = substr($sha512_full_raw, 0, 32);
        $blake2b_32bytes = substr($blake2b_full_raw, 0, 32);

        $combined_raw = $sha512_32bytes . $blake2b_32bytes;

        return [
            'sha512_full_hex' => bin2hex($sha512_full_raw),
            'blake2b_full_hex' => bin2hex($blake2b_full_raw),
            'combined_hex' => bin2hex($combined_raw),
            'combined_raw_for_signing' => $combined_raw, // Biner mentah 64-byte
        ];
    }

    public function performRealEdDSASigning(string $combinedHashRaw, Mahasiswa $mahasiswa): string
    {
        $encryptedPrivateKey = $mahasiswa->private_key_encrypted;
        if (!$encryptedPrivateKey) {
            throw new \Exception("Mahasiswa ini tidak memiliki private key. Kunci mungkin gagal di-generate saat akun dibuat.");
        }

        $privateKey = base64_decode(Crypt::decryptString($encryptedPrivateKey));
        
        $signature = sodium_crypto_sign_detached($combinedHashRaw, $privateKey);
        
        sodium_memzero($privateKey);

        return $signature; 
    }
}