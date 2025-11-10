<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSidang;
use App\Models\Dosen;
use App\Models\Lsta;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Carbon\Carbon; // <-- Import Carbon untuk mengelola tanggal

class JadwalController extends Controller
{
    /**
     * Meng-generate jadwal dummy untuk SEMUA pengajuan yang
     * statusnya 'TERIMA' tapi belum punya jadwal.
     */
    public function generateAll()
    {
        // 1. Ambil semua pengajuan yang 'TERIMA' & belum punya LSTA
        $pengajuansToSchedule = PengajuanSidang::where('status_validasi', 'TERIMA')
                                  ->doesntHave('lstas') // Kunci: Hanya yg belum punya LSTA
                                  ->with('tugasAkhir') // Ambil data TA terkait
                                  ->get();
        
        // 2. Ambil semua ID Dosen
        $allDosenIds = Dosen::pluck('id');

        // 3. Siapkan data dummy
        $dummyRooms = ['TC.2.1', 'TC.2.2', 'Ruang Rapat IF', 'Lab Cyber'];
        $startDate = Carbon::now()->addDays(7)->setTime(9, 0); // Mulai jadwal 1 minggu dari sekarang

        $counter = 0;

        // 4. Loop setiap pengajuan dan buat jadwal
        foreach ($pengajuansToSchedule as $pengajuan) {
            
            // Ambil ID Pembimbing
            $pembimbingIds = [
                $pengajuan->tugasAkhir->dosen_pembimbing_1_id,
                $pengajuan->tugasAkhir->dosen_pembimbing_2_id
            ];
            
            // Ambil Dosen yg BUKAN pembimbing
            $availablePenguji = $allDosenIds->diff($pembimbingIds);

            // --- BUAT JADWAL LSTA (DUMMY) ---
            // (Sesuai req: 1 Dosen Penguji)
            if ($availablePenguji->count() > 0) {
                Lsta::create([
                    'tugas_akhir_id' => $pengajuan->tugas_akhir_id,
                    'pengajuan_sidang_id' => $pengajuan->id,
                    'dosen_penguji_id' => $availablePenguji->random(), // Ambil 1 Dosen acak
                    'jadwal' => $startDate->copy()->addHours($counter), // Jadwal unik
                    'ruangan' => $dummyRooms[array_rand($dummyRooms)],
                    'status' => 'TERJADWAL',
                ]);
            }

            // --- BUAT JADWAL SIDANG (DUMMY) ---
            // (Sesuai req: 2 Dosen Penguji)
            if ($availablePenguji->count() > 1) {
                $pengujiSidang = $availablePenguji->random(2); // Ambil 2 Dosen acak
                Sidang::create([
                    'tugas_akhir_id' => $pengajuan->tugas_akhir_id,
                    'pengajuan_sidang_id' => $pengajuan->id,
                    'dosen_penguji_ketua_id' => $pengujiSidang[0],
                    'dosen_penguji_sekretaris_id' => $pengujiSidang[1],
                    // Jadwal sidang kita buat 3 hari setelah LSTA
                    'jadwal' => $startDate->copy()->addHours($counter)->addDays(3),
                    'ruangan' => $dummyRooms[array_rand($dummyRooms)],
                    'status' => 'TERJADWAL',
                ]);
            }

            $counter++; // Tambah 1 jam untuk mahasiswa berikutnya
        }

        // 5. Redirect kembali dengan pesan sukses
        if ($counter == 0) {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Tidak ada jadwal baru yang di-generate (semua sudah terjadwal).');
        }

        return redirect()->route('staff.dashboard')
            ->with('success', "Berhasil men-generate $counter jadwal LSTA & Sidang baru.");
    }
}