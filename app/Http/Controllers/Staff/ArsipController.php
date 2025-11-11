<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TugasAkhir; // Import Model TugasAkhir

class ArsipController extends Controller
{
    /**
     * Menampilkan halaman Arsip TA dengan fitur pencarian.
     * (Method ini sudah benar, tidak ada perubahan)
     */
    public function index(Request $request)
    {
        $searchQuery = $request->search;

        $query = TugasAkhir::query()
                            ->with('mahasiswa', 'dosenPembimbing1', 'dosenPembimbing2')
                            ->orderBy('created_at', 'desc');

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('judul', 'like', '%' . $searchQuery . '%')
                  ->orWhereHas('mahasiswa', function ($mahasiswaQuery) use ($searchQuery) {
                      $mahasiswaQuery->where('nama_lengkap', 'like', '%' . $searchQuery . '%')
                                     ->orWhere('nrp', 'like', '%' . $searchQuery . '%');
                  });
            });
        }
        
        $arsipTugasAkhir = $query->paginate(20);

        return view('staff.arsip-index', [
            'arsipTugasAkhir' => $arsipTugasAkhir,
            'searchQuery' => $searchQuery,
        ]);
    }

    /**
     * Menampilkan detail TA (LOGIKA DIPERBARUI).
     */
    public function show(TugasAkhir $tugasAkhir)
    {
        // Muat SEMUA relasi yang kita butuhkan untuk halaman detail
        $tugasAkhir->load(
            'mahasiswa', 
            'dosenPembimbing1', 
            'dosenPembimbing2', 
            'lstas',  // Semua jadwal LSTA
            'sidangs', // Semua jadwal Sidang
            'pengajuanSidangs.dokumen', // Semua paket pengajuan, DAN dokumen di dalamnya
            'pengajuanSidangs.validator' // Siapa staf yg memvalidasi
        );

        return view('staff.arsip-detail', [
            'ta' => $tugasAkhir
        ]);
    }
}