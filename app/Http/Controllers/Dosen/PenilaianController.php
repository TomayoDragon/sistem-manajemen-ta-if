<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lsta;
use App\Models\Sidang;
use App\Models\LembarPenilaian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\BeritaAcaraService;
// HAPUS IMPORT 'PengajuanSidang' DAN 'Storage' JIKA ADA

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

        if ($type === 'lsta') {
            $modelClass = Lsta::class;
            // Eager load relasi 'pengajuanSidang' DAN 'dokumen' di dalamnya
            $event = Lsta::with([
                'tugasAkhir.mahasiswa', 
                'pengajuanSidang.dokumen' // <-- PERBARUI INI
            ])->findOrFail($id);
        } elseif ($type === 'sidang') {
            $modelClass = Sidang::class;
            // Eager load relasi 'pengajuanSidang' DAN 'dokumen' di dalamnya
            $event = Sidang::with([
                'tugasAkhir.mahasiswa', 
                'pengajuanSidang.dokumen' // <-- PERBARUI INI
            ])->findOrFail($id);
        } else {
            abort(404);
        }

        // Logic 'existingScore' tetap sama
        $existingScore = LembarPenilaian::where('dosen_id', $dosenId)
                            ->where('penilaian_type', $modelClass)
                            ->where('penilaian_id', $event->id)
                            ->first();

        return view('dosen.penilaian', [
            'event' => $event,
            'type' => $type,
            'existingScore' => $existingScore
        ]);
    }

    // HAPUS SELURUH METHOD 'downloadFile()' DARI SINI
    // (Karena sudah dipindah ke DokumenController global)

    /**
     * Menyimpan nilai DAN MENTRIGGER FINALISASI OTOMATIS.
     * (Method ini tetap sama, tidak berubah)
     */
    public function store(Request $request, $type, $id, BeritaAcaraService $baService)
    {
        // ... (Seluruh logic store() Anda tetap sama persis) ...
        $dosenId = Auth::user()->dosen_id;
        $event = null;
        $modelClass = null;
        if ($type === 'lsta') {
            $modelClass = Lsta::class;
            $event = Lsta::findOrFail($id);
        } elseif ($type === 'sidang') {
            $modelClass = Sidang::class;
            $event = Sidang::findOrFail($id);
        } else {
            abort(404);
        }

        $request->validate([
            'nilai_materi' => 'required|integer|min:0|max:100',
            'nilai_sistematika' => 'required|integer|min:0|max:100',
            'nilai_mempertahankan' => 'required|integer|min:0|max:100',
            'nilai_pengetahuan_bidang' => 'required|integer|min:0|max:100',
            'nilai_karya_ilmiah' => 'required|integer|min:0|max:100',
            'komentar_revisi' => 'nullable|string|max:2000',
        ]);

        $lembar = LembarPenilaian::updateOrCreate(
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

        $event->load('lembarPenilaians');
        $jumlahNilaiMasuk = $event->lembarPenilaians->count();

        if ($type === 'lsta' && $jumlahNilaiMasuk == 3) {
            // ... (Logic kalkulasi LSTA) ...
        } elseif ($type === 'sidang' && $jumlahNilaiMasuk == 4) {
            $baService->generate($event);
        }
        
        return redirect()->route('dosen.dashboard')
            ->with('success', 'Lembar penilaian berhasil disimpan.');
    }
}