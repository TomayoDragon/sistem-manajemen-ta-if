<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// HAPUS 'use Storage' KARENA SUDAH TIDAK DIPAKAI DI SINI

class ValidasiController extends Controller
{
    /**
     * Menampilkan halaman detail "Review Paket Pengajuan".
     */
    public function show($id)
    {
        // Ambil data pengajuan DAN relasi 'dokumen'-nya
        $pengajuan = PengajuanSidang::with('tugasAkhir.mahasiswa', 'dokumen') // <-- PERBARUI INI
                        ->where('id', $id)
                        ->where('status_validasi', 'PENDING')
                        ->firstOrFail(); 

        return view('staff.review', [
            'pengajuan' => $pengajuan
        ]);
    }

    // HAPUS SELURUH METHOD 'downloadFile()' DARI SINI
    // (Karena sudah dipindah ke DokumenController)

    /**
     * Memproses keputusan validasi (Terima / Tolak).
     * (Method ini tetap sama, tidak berubah)
     */
    public function process(Request $request, $id)
    {
        $request->validate([
            'keputusan' => 'required|in:TERIMA,TOLAK',
            'catatan_validasi' => 'required_if:keputusan,TOLAK|nullable|string|max:1000',
        ]);

        $pengajuan = PengajuanSidang::findOrFail($id);

        $pengajuan->status_validasi = $request->input('keputusan');
        $pengajuan->catatan_validasi = $request->input('catatan_validasi');
        $pengajuan->validator_id = Auth::user()->staff_id;
        $pengajuan->validated_at = now();
        $pengajuan->save();

        $pesan = $request->input('keputusan') == 'TERIMA' ? 'disetujui' : 'ditolak';
        
        return redirect()->route('staff.dashboard')
            ->with('success', 'Paket pengajuan berhasil ' . $pesan . '.');
    }
}