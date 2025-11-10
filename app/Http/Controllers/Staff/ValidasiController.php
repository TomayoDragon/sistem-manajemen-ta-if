<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSidang; // <-- Import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- Import Storage

class ValidasiController extends Controller
{
    /**
     * Menampilkan halaman detail "Review Paket Pengajuan".
     */
    public function show($id)
    {
        // Ambil data pengajuan, pastikan statusnya PENDING
        $pengajuan = PengajuanSidang::with('tugasAkhir.mahasiswa')
                        ->where('id', $id)
                        ->where('status_validasi', 'PENDING')
                        ->firstOrFail(); // Error 404 jika tidak ditemukan

        return view('staff.review', [
            'pengajuan' => $pengajuan
        ]);
    }

    /**
     * Menangani download file dari halaman review.
     * Ini adalah rute aman untuk mengambil file private.
     */
    public function downloadFile(PengajuanSidang $pengajuan, $tipe)
    {
        $path = '';
        $namaFile = '';

        // Tentukan path & nama file berdasarkan tipe yang diminta
        switch ($tipe) {
            case 'buku':
                $path = $pengajuan->path_buku_skripsi;
                $namaFile = 'BukuSkripsi_' . $pengajuan->tugasAkhir->mahasiswa->nrp . '.pdf';
                break;
            case 'khs':
                $path = $pengajuan->path_khs;
                $namaFile = 'KHS_' . $pengajuan->tugasAkhir->mahasiswa->nrp . '.pdf';
                break;
            case 'transkrip':
                $path = $pengajuan->path_transkrip;
                $namaFile = 'Transkrip_' . $pengajuan->tugasAkhir->mahasiswa->nrp . '.pdf';
                break;
            default:
                abort(404, 'Tipe berkas tidak valid.');
        }

        // Cek jika file ada di storage
        if (!Storage::exists($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        // Kirim file ke browser
        return Storage::download($path, $namaFile);
    }

    /**
     * Memproses keputusan validasi (Terima / Tolak).
     */
    public function process(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'keputusan' => 'required|in:TERIMA,TOLAK',
            // Catatan wajib diisi HANYA JIKA keputusan = TOLAK
            'catatan_validasi' => 'required_if:keputusan,TOLAK|nullable|string|max:1000',
        ]);

        // 2. Ambil data pengajuan
        $pengajuan = PengajuanSidang::findOrFail($id);

        // 3. Update data di database
        $pengajuan->status_validasi = $request->input('keputusan');
        $pengajuan->catatan_validasi = $request->input('catatan_validasi');
        $pengajuan->validator_id = Auth::user()->staff_id; // Staf yang sedang login
        $pengajuan->validated_at = now(); // Waktu validasi
        $pengajuan->save();

        // 4. Redirect kembali ke dashboard staf
        $pesan = $request->input('keputusan') == 'TERIMA' ? 'disetujui' : 'ditolak';
        
        return redirect()->route('staff.dashboard')
            ->with('success', 'Paket pengajuan berhasil ' . $pesan . '.');
    }
}