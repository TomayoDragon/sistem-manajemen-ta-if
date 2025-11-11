<x-staff-layout>
    <x-slot name="title">
        Detail Arsip {{ $ta->mahasiswa->nrp }}
    </x-slot>

    <style>
        .detail-card { margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .detail-card h3 { font-size: 1.5rem; color: #0a2e6c; margin-bottom: 10px; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .detail-item { margin-bottom: 15px; }
        .detail-label { display: block; font-size: 0.9rem; color: #777; }
        .detail-value { font-weight: 700; font-size: 1.1rem; }

        .history-table-small { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .history-table-small th, .history-table-small td { border: 1px solid #ddd; padding: 8px; font-size: 0.9rem; }
        .history-table-small th { background-color: #f9f9f9; }
    </style>

    <h1 class="content-title">Detail Arsip Tugas Akhir</h1>
    
    <div class="content-box">
        
        <div class="detail-card">
            <h3>Informasi Mahasiswa</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">NRP / Nama:</span>
                    <span class="detail-value">{{ $ta->mahasiswa->nrp }} / {{ $ta->mahasiswa->nama_lengkap }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status Akhir:</span>
                    <span class="detail-value" style="color: green;">{{ $ta->status }}</span>
                </div>
            </div>
            <div class="detail-item">
                <span class="detail-label">Judul Tugas Akhir:</span>
                <span class="detail-value">{{ $ta->judul }}</span>
            </div>
        </div>

        <div class="detail-card">
            <h3>Riwayat Pengajuan Berkas</h3>
            <table class="history-table-small">
                <thead>
                    <tr>
                        <th>Tgl Pengajuan</th>
                        <th>Status Validasi</th>
                        <th>Divalidasi Oleh</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ta->pengajuanSidangs as $pengajuan)
                        <tr>
                            <td>{{ $pengajuan->created_at->format('d M Y') }}</td>
                            <td><span style="color: {{ $pengajuan->status_validasi == 'TERIMA' ? 'green' : 'red' }}">{{ $pengajuan->status_validasi }}</span></td>
                            <td>{{ $pengajuan->validator->nama_lengkap ?? '-' }}</td>
                            <td>{{ $pengajuan->catatan_validasi ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Tidak ada riwayat pengajuan berkas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="detail-card">
            <h3>Riwayat Sidang & LSTA</h3>
            <table class="history-table-small">
                <thead>
                    <tr>
                        <th>Jenis</th>
                        <th>Jadwal</th>
                        <th>Ruangan</th>
                        <th>Hasil Ujian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ta->lstas as $lsta)
                        <tr>
                            <td>LSTA</td>
                            <td>{{ \Carbon\Carbon::parse($lsta->jadwal)->format('d M Y') }}</td>
                            <td>{{ $lsta->ruangan }}</td>
                            <td>{{ $lsta->status }}</td>
                        </tr>
                    @empty
                    @endforelse
                    
                    @forelse ($ta->sidangs as $sidang)
                        <tr>
                            <td>Sidang TA</td>
                            <td>{{ \Carbon\Carbon::parse($sidang->jadwal)->format('d M Y') }}</td>
                            <td>{{ $sidang->ruangan }}</td>
                            <td>{{ $sidang->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Tidak ada riwayat LSTA/Sidang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-staff-layout>