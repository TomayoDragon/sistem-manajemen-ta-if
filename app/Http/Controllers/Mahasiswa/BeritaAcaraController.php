<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth; // <-- INI BARIS YANG HILANG (Penyebab error 'Auth')

class BeritaAcaraController extends Controller
{
    /**
     * Menampilkan atau men-download Berita Acara sebagai PDF.
     */
    public function show(Sidang $sidang)
    {
        // 1. Pastikan user yang login adalah pemilik TA ini
        $mahasiswaId = Auth::user()->mahasiswa_id; // Baris ini sekarang akan berfungsi
        if ($sidang->tugasAkhir->mahasiswa_id !== $mahasiswaId) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        // 2. Pastikan Berita Acara sudah di-generate
        if (!$sidang->beritaAcara) {
            return redirect()->back()->with('error', 'Berita Acara belum di-generate.');
        }

        // 3. Ambil SEMUA data yang dibutuhkan untuk BA
        $sidang->load(
            'tugasAkhir.mahasiswa',
            'tugasAkhir.dosenPembimbing1',
            'tugasAkhir.dosenPembimbing2',
            'dosenPengujiKetua',
            'dosenPengujiSekretaris',
            'beritaAcara' // Data nilai akhir
        );

        // 4. Load view template PDF
        $pdf = Pdf::loadView('mahasiswa.berita-acara-pdf', [
            'sidang' => $sidang,
            'ta' => $sidang->tugasAkhir,
            'mahasiswa' => $sidang->tugasAkhir->mahasiswa,
            'ba' => $sidang->beritaAcara
        ]);

        // 5. Stream (download) PDF ke browser
        $namaFile = 'Berita_Acara_Sidang_' . $sidang->tugasAkhir->mahasiswa->nrp . '.pdf';
        return $pdf->stream($namaFile);
    }
}