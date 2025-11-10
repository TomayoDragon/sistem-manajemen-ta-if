<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TugasAkhir;
use App\Models\Lsta;
use App\Models\Sidang;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dosen.
     */
    public function index()
    {
        // 1. Ambil data user dosen yang sedang login
        $dosen = Auth::user()->dosen;
        $dosenId = $dosen->id;

        // 2. Ambil daftar mahasiswa bimbingan (Ini tetap sama)
        $mahasiswaBimbingan = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)
                                      ->orWhere('dosen_pembimbing_2_id', $dosenId)
                                      ->with('mahasiswa')
                                      ->get();

        // 3. Ambil jadwal LSTA (LOGIKA DIPERBARUI)
        // (Tampilkan jika Dosen adalah Penguji LSTA ATAU Pembimbing dari TA tsb)
        $jadwalLsta = Lsta::where('status', 'TERJADWAL')
                            ->where(function ($query) use ($dosenId) {
                                // Kondisi 1: Dosen adalah Penguji
                                $query->where('dosen_penguji_id', $dosenId)
                                // Kondisi 2: ATAU Dosen adalah Pembimbing
                                      ->orWhereHas('tugasAkhir', function ($taQuery) use ($dosenId) {
                                          $taQuery->where('dosen_pembimbing_1_id', $dosenId)
                                                  ->orWhere('dosen_pembimbing_2_id', $dosenId);
                                      });
                            })
                            ->with('tugasAkhir.mahasiswa')
                            ->orderBy('jadwal', 'asc')
                            ->get();

        // 4. Ambil jadwal Sidang (LOGIKA DIPERBARUI)
        // (Tampilkan jika Dosen adalah Penguji Sidang ATAU Pembimbing dari TA tsb)
        $jadwalSidang = Sidang::where('status', 'TERJADWAL')
                              ->where(function ($query) use ($dosenId) {
                                  // Kondisi 1: Dosen adalah Penguji (Ketua/Sekretaris)
                                  $query->where('dosen_penguji_ketua_id', $dosenId)
                                        ->orWhere('dosen_penguji_sekretaris_id', $dosenId)
                                  // Kondisi 2: ATAU Dosen adalah Pembimbing
                                        ->orWhereHas('tugasAkhir', function ($taQuery) use ($dosenId) {
                                            $taQuery->where('dosen_pembimbing_1_id', $dosenId)
                                                    ->orWhere('dosen_pembimbing_2_id', $dosenId);
                                        });
                              })
                              ->with('tugasAkhir.mahasiswa')
                              ->orderBy('jadwal', 'asc')
                              ->get();

        // 5. Kirim semua data ke view
        return view('dosen.dashboard', [
            'dosen' => $dosen,
            'mahasiswaBimbingan' => $mahasiswaBimbingan,
            'jadwalLsta' => $jadwalLsta,
            'jadwalSidang' => $jadwalSidang,
        ]);
    }
}