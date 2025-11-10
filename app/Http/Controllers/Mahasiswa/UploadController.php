<?php

// PASTIKAN NAMESPACE INI BENAR
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanSidang; // <-- Gunakan model baru
use App\Models\TugasAkhir;

class UploadController extends Controller
{
    /**
     * Menampilkan halaman form upload (Paket Pengajuan Sidang).
     */
    public function create()
    {
        // 1. Ambil TA aktif milik mahasiswa
        $tugasAkhir = Auth::user()->mahasiswa
                        ->tugasAkhirs()
                        ->latest()
                        ->first();

        // 2. Jika tidak punya TA, redirect
        if (! $tugasAkhir) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Anda harus memiliki data Tugas Akhir aktif untuk mengupload berkas.');
        }

        // 3. Ambil SEMUA riwayat pengajuan untuk TA ini
        $riwayatPengajuan = $tugasAkhir->pengajuanSidangs()
                                    ->latest() // Tampilkan yang terbaru di atas
                                    ->get();
        
        // 4. Ambil pengajuan TERBARU untuk dicek statusnya
        $pengajuanTerbaru = $riwayatPengajuan->first();

        // 5. Tampilkan view, kirim data pengajuan terbaru & semua riwayat
        return view('mahasiswa.upload', [
            'tugasAkhir' => $tugasAkhir,
            'pengajuanTerbaru' => $pengajuanTerbaru,
            'riwayatPengajuan' => $riwayatPengajuan
        ]);
    }

    /**
     * Menyimpan paket pengajuan sidang yang baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi 3 file wajib
        $request->validate([
            'buku_skripsi' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'khs' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'transkrip' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // 2. Ambil TA aktif
        $tugasAkhir = Auth::user()->mahasiswa->tugasAkhirs()->latest()->first();

        // 3. Cek apakah sudah ada yang PENDING
        $isPending = $tugasAkhir->pengajuanSidangs()
                               ->where('status_validasi', 'PENDING')
                               ->exists();
        
        if ($isPending) {
            return redirect()->route('mahasiswa.upload')
                ->with('error', 'Anda sudah memiliki pengajuan yang sedang divalidasi. Harap tunggu.');
        }

        // 4. Simpan 3 file ke storage
        $pathBuku = $request->file('buku_skripsi')->store('uploads/pengajuan_sidang');
        $pathKhs = $request->file('khs')->store('uploads/pengajuan_sidang');
        $pathTranskrip = $request->file('transkrip')->store('uploads/pengajuan_sidang');

        // 5. Buat SATU record "Paket Pengajuan" baru
        PengajuanSidang::create([
            'tugas_akhir_id' => $tugasAkhir->id,
            'path_buku_skripsi' => $pathBuku,
            'path_khs' => $pathKhs,
            'path_transkrip' => $pathTranskrip,
            'status_validasi' => 'PENDING',
        ]);

        // 6. Kembalikan ke halaman upload dengan pesan sukses
        return redirect()->route('mahasiswa.upload')
            ->with('success', 'Paket berkas berhasil di-upload dan sedang menunggu validasi.');
    }
}