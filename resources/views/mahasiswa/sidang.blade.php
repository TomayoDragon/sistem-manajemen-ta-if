<x-mahasiswa-layout>
    <x-slot name="title">
        Jadwal Sidang & LSTA
    </x-slot>

    <style>
        .table-wrapper {
            width: 100%; border-collapse: collapse; margin-top: 10px;
        }
        .table-wrapper th, .table-wrapper td {
            border: 1px solid #ddd; padding: 12px; text-align: left;
        }
        .table-wrapper th {
            background-color: #0a2e6c; color: white; font-weight: 700;
        }
        .table-wrapper td.empty {
            text-align: center; color: #777; font-style: italic;
        }
        
        .status-message-box {
            border-radius: 8px; padding: 40px; text-align: center;
            background-color: #f4f7f6; border: 2px dashed #ccc;
        }
        .status-message-box .icon { font-size: 3rem; margin-bottom: 15px; }
        .status-message-box h3 { font-size: 1.3rem; margin-bottom: 10px; }
        .status-message-box p { color: #777; }

        /* Status: Pending (Kuning) */
        .status-pending { border-color: #f39c12; background-color: #fffaf0; }
        .status-pending .icon { color: #f39c12; }
        .status-pending h3 { color: #d35400; }
        
        /* Status: Reject (Merah) */
        .status-reject { border-color: #e74c3c; background-color: #fff2f2; }
        .status-reject .icon { color: #e74c3c; }
        .status-reject h3 { color: #c0392b; }

        /* Status: Diterima tapi Menunggu (Biru) */
        .status-wait-schedule { border-color: #3498db; background-color: #f0f9ff; }
        .status-wait-schedule .icon { color: #3498db; }
        .status-wait-schedule h3 { color: #2980b9; }
    </style>

    <h1 class="content-title">Sidang / LSTA</h1>

    @if ($pengajuanTerbaru && $pengajuanTerbaru->status_validasi == 'TERIMA')
        
        <h2 class="content-title" style="font-size: 1.5rem; margin-top: 10px;">LSTA</h2>
        <div class="content-box">
            <table class="table-wrapper">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Ruangan</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($lsta) <tr>
                            <td>{{ \Carbon\Carbon::parse($lsta->jadwal)->format('d F Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($lsta->jadwal)->format('H:i') }} WIB</td>
                            <td>{{ $lsta->ruangan }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3" class="empty">
                                Berkas Anda telah disetujui. Harap tunggu Staf PAJ mem-publish jadwal LSTA Anda.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <h2 class="content-title" style="font-size: 1.5rem; margin-top: 30px;">Sidang</h2>
        <div class="content-box">
            <table class="table-wrapper">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Ruangan</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($sidang) <tr>
                            <td>{{ \Carbon\Carbon::parse($sidang->jadwal)->format('d F Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($sidang->jadwal)->format('H:i') }} WIB</td>
                            <td>{{ $sidang->ruangan }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3" class="empty">
                                Jadwal Sidang belum ditentukan oleh Staf PAJ.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    @elseif ($pengajuanTerbaru && $pengajuanTerbaru->status_validasi == 'PENDING')

        <div class="content-box">
            <div class="status-message-box status-pending">
                <i class="fa-solid fa-hourglass-half icon"></i>
                <h3>Berkas Sedang Diverifikasi</h3>
                <p>Jadwal sidang akan muncul di halaman ini setelah paket berkas Anda disetujui oleh Staf PAJ.</p>
            </div>
        </div>

    @else

        <div class="content-box">
            <div class="status-message-box status-reject">
                <i class="fa-solid fa-circle-xmark icon"></i>
                <h3>Berkas Belum Disetujui</h3>
                <p>
                    Anda harus mengupload paket berkas sidang dan menunggu persetujuan
                    <br>
                    sebelum dapat melihat jadwal sidang di halaman ini.
                </p>
            </div>
        </div>
        
    @endif

</x-mahasiswa-layout>