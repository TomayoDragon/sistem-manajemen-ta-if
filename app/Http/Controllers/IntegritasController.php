<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenPengajuan; // <-- UBAH DARI PengajuanSidang
use App\Services\SignatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegritasController extends Controller
{
    /**
     * Menampilkan halaman verifikasi (Per-File).
     */
    public function show(DokumenPengajuan $dokumen) // <-- UBAH INI
    {
        $dokumen->load('pengajuanSidang.tugasAkhir.mahasiswa');
        $layout = $this->getLayoutForUser(Auth::user());
        
        return view('integritas-check', [
            'dokumen' => $dokumen, // <-- Ubah nama variabel
            'layout' => $layout
        ]);
    }

    /**
     * Memproses 1 file yang diupload untuk dicek.
     */
    public function verify(Request $request, DokumenPengajuan $dokumen, SignatureService $signatureService) // <-- UBAH INI
    {
        // 1. Validasi 1 file
        $request->validate([
            'file_cek' => 'required|file|max:20480',
        ]);

        // 2. Ambil hash orisinal DARI DOKUMEN
        $originalHash = $dokumen->hash_combined;

        // 3. Ambil konten file BARU
        $fileContent = $request->file('file_cek')->get();
        
        // 4. Hitung hash baru menggunakan logic yang SAMA PERSIS
        $hashData = $signatureService->performCustomHash($fileContent);
        $newHash = $hashData['combined_hex'];

        // 5. Bandingkan
        $isMatch = ($originalHash === $newHash);
        
        // 6. Kembalikan ke halaman sebelumnya dengan hasil
        return redirect()->route('integritas.show', $dokumen->id)
            ->with([
                'checkResult' => $isMatch,
                'newHash' => $newHash,
            ]);
    }
    
    // ... (method getLayoutForUser tetap sama) ...
    private function getLayoutForUser($user)
    {
        if ($user->hasRole('mahasiswa')) return 'mahasiswa-layout';
        if ($user->hasRole('dosen')) return 'dosen-layout';
        if ($user->hasRole('staff')) return 'staff-layout';
        return 'app-layout';
    }
}