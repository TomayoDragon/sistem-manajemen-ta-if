<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TugasAkhir; // <-- IMPORT MODEL

class DashboardController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        // AMBIL DATA DARI DATABASE (BUKAN HARDCODE)
        // Kita ambil TA terbaru milik mahasiswa ini,
        // dan 'eager load' (ambil sekalian) data 2 dosen pembimbingnya
        $tugasAkhir = $mahasiswa->tugasAkhirs()
                                ->with('dosenPembimbing1', 'dosenPembimbing2')
                                ->latest() // Ambil yang paling baru
                                ->first(); // Ambil 1 saja

        // Kirim data TA (bisa jadi null jika belum ada) ke view
        return view('mahasiswa.dashboard', [
            'mahasiswa' => $mahasiswa,
            'tugasAkhir' => $tugasAkhir, // Kirim object $tugasAkhir
        ]);
    }
}