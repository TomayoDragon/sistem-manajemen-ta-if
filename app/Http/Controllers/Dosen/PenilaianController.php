<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lsta;
use App\Models\Sidang;
use App\Models\LembarPenilaian;
use Illuminate\Support\Facades\Auth;
use Illuminatef\Support\Str;

class PenilaianController extends Controller
{
    /**
     * Menampilkan form lembar penilaian.
     */
    public function show($type, $id)
    {
        $dosenId = Auth::user()->dosen_id;
        $event = null;
        $modelClass = null;

        // 1. Tentukan model (Lsta or Sidang) berdasarkan $type
        if ($type === 'lsta') {
            $modelClass = Lsta::class;
            $event = Lsta::with('tugasAkhir.mahasiswa')->findOrFail($id);
        } elseif ($type === 'sidang') {
            $modelClass = Sidang::class;
            $event = Sidang::with('tugasAkhir.mahasiswa')->findOrFail($id);
        } else {
            abort(404);
        }

        // 2. Cek apakah dosen ini sudah pernah mengisi nilai
        $existingScore = LembarPenilaian::where('dosen_id', $dosenId)
                            ->where('penilaian_type', $modelClass)
                            ->where('penilaian_id', $event->id)
                            ->first();

        // 3. Tampilkan view, kirim data event & nilai yg sudah ada (jika ada)
        return view('dosen.penilaian', [
            'event' => $event,
            'type' => $type,
            'existingScore' => $existingScore // Akan 'null' jika belum isi
        ]);
    }

    /**
     * Menyimpan nilai dari form penilaian.
     */
    public function store(Request $request, $type, $id)
    {
        $dosenId = Auth::user()->dosen_id;
        $modelClass = null;

        // 1. Tentukan model (Lsta or Sidang)
        if ($type === 'lsta') {
            $modelClass = Lsta::class;
        } elseif ($type === 'sidang') {
            $modelClass = Sidang::class;
        } else {
            abort(404);
        }

        // 2. Validasi 5 komponen nilai (sesuai Gambar 3.3)
        $request->validate([
            'nilai_materi' => 'required|integer|min:0|max:100',
            'nilai_sistematika' => 'required|integer|min:0|max:100',
            'nilai_mempertahankan' => 'required|integer|min:0|max:100',
            'nilai_pengetahuan_bidang' => 'required|integer|min:0|max:100',
            'nilai_karya_ilmiah' => 'required|integer|min:0|max:100',
            'komentar_revisi' => 'nullable|string|max:2000',
        ]);

        // 3. Gunakan updateOrCreate untuk menyimpan/update nilai
        // Ini akan mencari berdasarkan 3 key unik,
        // jika ketemu, akan di-update. Jika tidak, akan dibuat.
        LembarPenilaian::updateOrCreate(
            [
                'dosen_id' => $dosenId,
                'penilaian_type' => $modelClass,
                'penilaian_id' => $id,
            ],
            [
                'nilai_materi' => $request->input('nilai_materi'),
                'nilai_sistematika' => $request->input('nilai_sistematika'),
                'nilai_mempertahankan' => $request->input('nilai_mempertahankan'),
                'nilai_pengetahuan_bidang' => $request->input('nilai_pengetahuan_bidang'),
                'nilai_karya_ilmiah' => $request->input('nilai_karya_ilmiah'),
                'komentar_revisi' => $request->input('komentar_revisi'),
            ]
        );

        // 4. Redirect kembali ke dashboard dosen
        return redirect()->route('dosen.dashboard')
            ->with('success', 'Lembar penilaian berhasil disimpan.');
    }
}