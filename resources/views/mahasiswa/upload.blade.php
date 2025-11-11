<x-mahasiswa-layout>
    <x-slot name="title">
        Upload Berkas TA
    </x-slot>

    <style>
        /* --- Form --- */
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-size: 1.1rem; font-weight: 700; color: #333; margin-bottom: 8px; }
        .form-input { width: 100%; padding: 12px 15px; font-size: 1rem; border: 1px solid #ccc; border-radius: 8px; }
        .btn-submit { padding: 12px 25px; font-size: 1rem; font-weight: 700; color: #fff; background-color: #0a2e6c; border: none; border-radius: 8px; cursor: pointer; transition: background-color 0.2s; }
        .btn-submit:hover { background-color: #082456; }

        /* --- Status Box --- */
        .status-box { display: flex; flex-direction: column; align-items: center; justify-content: center; border: 2px dashed; border-radius: 8px; padding: 40px; text-align: center; margin-bottom: 30px; }
        .status-box .icon { font-size: 5rem; }
        .status-box h3 { font-size: 1.3rem; margin-top: 20px; }
        .status-box p { color: #777; margin-top: 10px; }
        .pending-box { border-color: #f39c12; background-color: #fffaf0; }
        .pending-box .icon { color: #f39c12; }
        .pending-box h3 { color: #d35400; }
        .reject-box { border-color: #e74c3c; background-color: #fff2f2; margin-bottom: 30px; }
        .reject-box .icon { color: #e74c3c; }
        .reject-box h3 { color: #c0392b; }
        .reject-box .notes { margin-top: 15px; padding: 10px; background: #ffebeB; border: 1px solid #ffcccc; border-radius: 5px; color: #a00; font-style: italic; }
        .accept-box { border-color: #2ecc71; background-color: #f0fff4; }
        .accept-box .icon { color: #2ecc71; }
        .accept-box h3 { color: #27ae60; }

        /* --- History Table --- */
        .history-table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        .history-table th, .history-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .history-table th { background-color: #f4f4f4; font-weight: 700; }
        .status-pending { color: #d35400; font-weight: 700; }
        .status-terima { color: #2ecc71; font-weight: 700; }
        .status-tolak { color: #e74c3c; font-weight: 700; }
        .icon-signed { color: #3498db; font-size: 1.2rem; }
        .link-verifikasi { color: #0a2e6c; text-decoration: none; font-weight: 600; }
    </style>

 <h1 class="content-title">Upload Paket Berkas Sidang</h1>

    @if ($errors->any())
        @endif
    @if (session('success'))
        @endif
    @if (session('error'))
        @endif
    
    <div class="content-box">
        </div>

    <h2 class="content-title" style="margin-top: 30px;">Riwayat Pengajuan Sidang</h2>
    <div class="content-box">
        <table class="history-table">
            <thead>
                <tr>
                    <th>Tgl Pengajuan</th>
                    <th>Status</th>
                    <th>Tanda Tangan</th>
                    <th>Catatan Staf</th>
                    </tr>
            </thead>
            <tbody>
                @forelse ($riwayatPengajuan as $pengajuan)
                    <tr>
                        <td>{{ $pengajuan->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <span class="status-{{ strtolower($pengajuan->status_validasi) }}">
                                {{ $pengajuan->status_validasi }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            @if ($pengajuan->dokumen->isNotEmpty())
                                <i class="fa-solid fa-check-circle icon-signed"
                                    title="Paket telah ditandatangani pada {{ $pengajuan->created_at }}"></i>
                            @else
                                <i class="fa-solid fa-times-circle" style="color: #ccc;"
                                    title="Tidak ada data tanda tangan"></i>
                            @endif
                        </td>
                        <td>{{ $pengajuan->catatan_validasi ?? '-' }}</td>
                        </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #777;">
                            Belum ada riwayat pengajuan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-mahasiswa-layout>