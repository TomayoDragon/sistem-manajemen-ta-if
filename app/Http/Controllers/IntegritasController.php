<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenPengajuan; // <-- INI YANG BENAR
use App\Services\SignatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegritasController extends Controller
{
    /**
     * Menampilkan halaman verifikasi (Per-File).
     */
    public function show(DokumenPengajuan $dokumen) // <-- HARUS DokumenPengajuan
    {
        $dokumen->load('pengajuanSidang.tugasAkhir.mahasiswa');
        $layout = $this->getLayoutForUser(Auth::user());
        
        return view('integritas-check', [
            'dokumen' => $dokumen,
            'layout' => $layout
        ]);
    }

    /**
     * Memproses 1 file yang diupload untuk dicek.
     */
    public function verify(Request $request, DokumenPengajuan $dokumen, SignatureService $signatureService) // <-- HARUS DokumenPengajuan
    {
        $request->validate([
            'file_cek' => 'required|file|max:20480', // Validasi 1 file
        ]);

        $originalHash = $dokumen->hash_combined;

        $fileContent = $request->file('file_cek')->get(); // Ambil 1 file
        
        $hashData = $signatureService->performCustomHash($fileContent);
        $newHash = $hashData['combined_hex'];

        $isMatch = ($originalHash === $newHash);
        
        return redirect()->route('integritas.show', $dokumen->id)
            ->with([
                'checkResult' => $isMatch,
                'newHash' => $newHash,
            ]);
    }
    
    private function getLayoutForUser($user)
    {
        if ($user->hasRole('mahasiswa')) return 'mahasiswa-layout';
        if ($user->hasRole('dosen')) return 'dosen-layout';
        if ($user->hasRole('staff')) return 'staff-layout';
        return 'app-layout'; // Fallback
    }
}