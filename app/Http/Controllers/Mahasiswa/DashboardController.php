<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard mahasiswa.
     */
    public function index()
    {
        // Ambil data user mahasiswa yang sedang login
        $mahasiswa = Auth::user()->mahasiswa;

        // --- PENTING ---
        // Untuk saat ini, data Tugas Akhir (TA) kita hardcode
        // sesuai desain Anda. Nanti kita akan ambil ini dari database.
        $tugasAkhir = [
            'judul' => 'Pembuatan Sistem Manajemen Berkas Tugas Akhir Dengan Digital Signature',
            'dosbing' => 'Ahmad Miftah Fajrin, M.Kom.',
            'status' => 'On Progress',
        ];

        // Kirim data ke view
        return view('mahasiswa.dashboard', [
            'mahasiswa' => $mahasiswa,
            'tugasAkhir' => $tugasAkhir,
        ]);
    }
}