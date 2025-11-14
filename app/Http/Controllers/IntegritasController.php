<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenPengajuan; // Ini model Dokumen
use App\Services\SignatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegritasController extends Controller
{
    /**
     * Menampilkan halaman verifikasi (Per-File).
     * (Method 'show' tidak berubah)
     */
    public function show(DokumenPengajuan $dokumen)
    {
        $dokumen->load('pengajuanSidang.tugasAkhir.mahasiswa');
        $layout = $this->getLayoutForUser(Auth::user());
        
        return view('integritas-check', [
            'dokumen' => $dokumen,
            'layout' => $layout
        ]);
    }

    /**
     * Memproses verifikasi TANDA TANGAN DIGITAL (Bukan lagi Hash Check).
     */
    public function verify(Request $request, DokumenPengajuan $dokumen, SignatureService $signatureService)
    {
        $request->validate(['file_cek' => 'required|file|max:20480']);

        // --- INI ADALAH LOGIKA BARU (VERIFIKASI SIGNATURE) ---
        
        // 1. Ambil data orisinal dari database
        $storedSignature = $dokumen->signature_data; // Signature biner ASLI
        $mahasiswa = $dokumen->pengajuanSidang->tugasAkhir->mahasiswa;
        $publicKeyBase64 = $mahasiswa->public_key; // Public key pemilik

        // 2. Hitung hash Kustom (Novelti Anda) dari file BARU
        $fileContent = $request->file('file_cek')->get();
        $hashData = $signatureService->performCustomHash($fileContent);
        $newHashRaw = $hashData['combined_raw_for_signing']; // Hash biner dari file BARU
        
        // 3. Panggil service verifikasi
        $isMatch = $signatureService->verifySignature(
            $storedSignature, // (Gembok)
            $newHashRaw,      // (Konten yang diuji)
            $publicKeyBase64  // (Kunci)
        );
        // --- AKHIR LOGIKA BARU ---
        
        // 4. Kembalikan ke halaman sebelumnya dengan hasil
        return redirect()->route('integritas.show', $dokumen->id)
            ->with([
                'checkResult' => $isMatch,
                'newHash' => $hashData['combined_hex'], // Tetap kirim hash hex untuk ditampilkan
            ]);
    }
    
    // ... (method getLayoutForUser tetap sama) ...
    private function getLayoutForUser($user)
    {
        if ($user->hasRole('mahasiswa')) return 'mahasiswa-layout';
        if ($user->hasRole('dosen')) return 'dosen-layout';
        if ($user->hasRole('staff')) return 'staff-layout';
        return 'app-layout'; // Fallback
    }
}