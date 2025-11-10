<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SidangController extends Controller
{
    /**
     * Menampilkan halaman jadwal Sidang / LSTA.
     */
    public function index()
    {
        // 1. Ambil TA aktif mahasiswa
        $tugasAkhir = Auth::user()->mahasiswa
                        ->tugasAkhirs()
                        ->latest()
                        ->first();

        // 2. Jika tidak punya TA, kembalikan
        if (! $tugasAkhir) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Anda belum memiliki data Tugas Akhir.');
        }

        // 3. Ambil pengajuan TERBARU untuk cek status
        $pengajuanTerbaru = $tugasAkhir->pengajuanSidangs()
                                     ->latest()
                                     ->first();
        
        // 4. Inisialisasi jadwal
        $lstaTerbaru = null;
        $sidangTerbaru = null;

        // 5. HANYA JIKA status = TERIMA, cari jadwal TERBARU
        if ($pengajuanTerbaru && $pengajuanTerbaru->status_validasi == 'TERIMA') {
            // Ambil jadwal LSTA terbaru (jika ada)
            $lstaTerbaru = $tugasAkhir->lstas()->latest()->first();
            // Ambil jadwal Sidang terbaru (jika ada)
            $sidangTerbaru = $tugasAkhir->sidangs()->latest()->first();
        }

        // 6. Kirim semua data ke view
        return view('mahasiswa.sidang', [
            'pengajuanTerbaru' => $pengajuanTerbaru,
            'lsta' => $lstaTerbaru, // Kirim LSTA terbaru
            'sidang' => $sidangTerbaru, // Kirim Sidang terbaru
        ]);
    }
}