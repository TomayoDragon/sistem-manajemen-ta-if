<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TugasAkhir; // Import Model TugasAkhir

class ArsipController extends Controller
{
    /**
     * Menampilkan halaman Arsip TA dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        // 1. Ambil kata kunci pencarian
        $searchQuery = $request->search;

        // 2. Query utama untuk mengambil semua data TA
        $query = TugasAkhir::query()
                            ->with('mahasiswa', 'dosenPembimbing1', 'dosenPembimbing2')
                            ->orderBy('created_at', 'desc');

        // 3. Terapkan Filter Pencarian (jika ada search query)
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                // Cari berdasarkan Judul TA
                $q->where('judul', 'like', '%' . $searchQuery . '%')
                  
                  // ATAU Cari berdasarkan Nama Mahasiswa/NRP
                  ->orWhereHas('mahasiswa', function ($mahasiswaQuery) use ($searchQuery) {
                      $mahasiswaQuery->where('nama_lengkap', 'like', '%' . $searchQuery . '%')
                                     ->orWhere('nrp', 'like', '%' . $searchQuery . '%');
                  });
            });
        }
        
        // 4. Ambil data dengan pagination (misal 20 data per halaman)
        $arsipTugasAkhir = $query->paginate(20);

        // 5. Kirim data ke view
        return view('staff.arsip-index', [
            'arsipTugasAkhir' => $arsipTugasAkhir,
            'searchQuery' => $searchQuery,
        ]);
    }

    /**
     * Menampilkan detail TA (Placeholder untuk nanti).
     */
    public function show(TugasAkhir $tugasAkhir)
    {
        // Muat relasi yang dibutuhkan
        $tugasAkhir->load('mahasiswa', 'dosenPembimbing1', 'dosenPembimbing2', 'sidangs', 'lstas', 'pengajuanSidangs');

        return view('staff.arsip-detail', [
            'ta' => $tugasAkhir
        ]);
    }
}