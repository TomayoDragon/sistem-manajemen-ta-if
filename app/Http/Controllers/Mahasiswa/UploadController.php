<?php

// INI ADALAH BARIS KUNCI YANG MEMPERBAIKI ERROR ANDA
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanSidang;
use App\Models\TugasAkhir;
use App\Models\DokumenPengajuan;
use App\Services\SignatureService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    /**
     * Menampilkan halaman form upload.
     */
    public function create()
    {
        $tugasAkhir = Auth::user()->mahasiswa->tugasAkhirs()->latest()->first();
        if (! $tugasAkhir) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda harus memiliki data Tugas Akhir aktif untuk mengupload berkas.');
        }

        $riwayatPengajuan = $tugasAkhir->pengajuanSidangs()->with('dokumen')->latest()->get();
        $pengajuanTerbaru = $riwayatPengajuan->first();

        return view('mahasiswa.upload', [
            'tugasAkhir' => $tugasAkhir,
            'pengajuanTerbaru' => $pengajuanTerbaru,
            'riwayatPengajuan' => $riwayatPengajuan
        ]);
    }

    /**
     * Menyimpan paket pengajuan, melakukan HASHING & SIGNATURE PER FILE.
     */
    public function store(Request $request, SignatureService $signatureService)
    {
        $request->validate([
            'buku_skripsi' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'khs' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'transkrip' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $tugasAkhir = Auth::user()->mahasiswa->tugasAkhirs()->latest()->first();
        
        $isPending = $tugasAkhir->pengajuanSidangs()->where('status_validasi', 'PENDING')->exists();
        if ($isPending) {
            return redirect()->route('mahasiswa.upload')->with('error', 'Anda sudah memiliki pengajuan yang sedang divalidasi.');
        }

        DB::beginTransaction();
        try {
            // 1. Buat "Paket" Pengajuan
            $pengajuan = PengajuanSidang::create([
                'tugas_akhir_id' => $tugasAkhir->id,
                'status_validasi' => 'PENDING',
            ]);

            // 2. Siapkan array file
            $filesToProcess = [
                'BUKU_SKRIPSI' => $request->file('buku_skripsi'),
                'KHS' => $request->file('khs'),
                'TRANSKRIP' => $request->file('transkrip'),
            ];

            // 3. Loop dan proses SETIAP file
            foreach ($filesToProcess as $tipe => $file) {
                $fileContent = $file->get();
                
                $hashData = $signatureService->performCustomHash($fileContent); 
                $signature = $signatureService->simulateEdDSASigning($hashData['combined_raw_for_signing']);
                
                $path = $file->store('uploads/dokumen_pengajuan');
                $namaFileAsli = $file->getClientOriginalName();

                // 4. Buat record Dokumen terpisah
                DokumenPengajuan::create([
                    'pengajuan_sidang_id' => $pengajuan->id,
                    'tipe_dokumen' => $tipe,
                    'path_penyimpanan' => $path,
                    'nama_file_asli' => $namaFileAsli,
                    'hash_sha512_full' => $hashData['sha512_full_hex'],
                    'hash_blake2b_full' => $hashData['blake2b_full_hex'],
                    'hash_combined' => $hashData['combined_hex'],
                    'signature_data' => $signature,
                    'is_signed' => true,
                ]);
            }

            DB::commit();
            return redirect()->route('mahasiswa.upload')->with('success', 'Paket berkas berhasil di-upload dan DITANDATANGANI (per file). Menunggu validasi Staf.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('mahasiswa.upload')->with('error', 'Terjadi kesalahan saat memproses file. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }
}