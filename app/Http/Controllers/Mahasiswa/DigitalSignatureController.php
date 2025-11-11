<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DigitalSignatureController extends Controller
{
    /**
     * Menampilkan daftar dokumen yang sudah ditandatangani.
     */
    public function index()
    {
        // Ambil ID mahasiswa yang login
        $mahasiswaId = Auth::user()->mahasiswa_id;

        // Ambil SEMUA dokumen (dari SEMUA pengajuan)
        // yang dimiliki oleh mahasiswa ini
        $dokumenTertanda = \App\Models\DokumenPengajuan::whereHas('pengajuanSidang.tugasAkhir', function ($query) use ($mahasiswaId) {
            $query->where('mahasiswa_id', $mahasiswaId);
        })
        ->where('is_signed', true)
        ->latest()
        ->get();

        return view('mahasiswa.digital-signature', [
            'dokumenTertanda' => $dokumenTertanda
        ]);
    }
}