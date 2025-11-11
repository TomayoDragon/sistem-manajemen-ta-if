<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenPengajuan; // <-- Model baru kita
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
    /**
     * Menangani download file yang aman untuk semua role.
     */
    public function download(DokumenPengajuan $dokumen)
    {
        // --- LOGIKA KEAMANAN ---
        $user = Auth::user();
        $taOwnerId = $dokumen->pengajuanSidang->tugasAkhir->mahasiswa_id;

        // Izinkan download HANYA JIKA:
        // 1. User adalah Staf
        // 2. User adalah Dosen
        // 3. User adalah Mahasiswa PEMILIK dokumen ini
        if (
            !$user->hasRole('staff') &&
            !$user->hasRole('dosen') &&
            $user->mahasiswa_id !== $taOwnerId
        ) {
            abort(403, 'ANDA TIDAK BERHAK MENGAKSES FILE INI.');
        }

        // --- LOGIKA DOWNLOAD ---
        $path = $dokumen->path_penyimpanan;

        if (!Storage::exists($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        // Stream file ke browser dengan nama aslinya
        return Storage::download($path, $dokumen->nama_file_asli);
    }
}