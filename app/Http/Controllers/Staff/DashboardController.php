<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSidang; // <-- Ganti model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard staf (validasi "paket" berkas).
     */
    public function index()
    {
        // Ambil data staf yang sedang login
        $staff = Auth::user()->staff;

        // AMBIL SEMUA "PAKET PENGAJUAN" YANG STATUSNYA 'PENDING'
        $pendingPengajuans = PengajuanSidang::where('status_validasi', 'PENDING')
                                  ->with('tugasAkhir.mahasiswa') // Ambil data TA & Mhs
                                  ->latest()
                                  ->get();

        // Kirim data ke view
        return view('staff.dashboard', [
            'staff' => $staff,
            'pendingPengajuans' => $pendingPengajuans, // <-- Ganti nama variabel
        ]);
    }
}