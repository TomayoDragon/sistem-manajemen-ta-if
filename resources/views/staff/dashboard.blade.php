<x-staff-layout>
    <x-slot name="title">
        Validasi Berkas
    </x-slot>

    <style>
        .table-wrapper {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-wrapper th, .table-wrapper td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .table-wrapper th {
            background-color: #f4f4f4;
            font-weight: 700;
        }
        .btn-review {
            padding: 5px 12px;
            font-size: 0.9rem;
            text-decoration: none;
            color: white;
            background-color: #0a2e6c; /* Biru IF */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>

    <h1 class="content-title">Validasi Pengajuan Sidang Tertunda</h1>

    <div class="content-box">
        <table class="table-wrapper">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>NRP</th>
                    <th>Judul TA</th>
                    <th>Tgl Pengajuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingPengajuans as $pengajuan)
                    <tr>
                        <td>{{ $pengajuan->tugasAkhir->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $pengajuan->tugasAkhir->mahasiswa->nrp }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($pengajuan->tugasAkhir->judul, 45) }}</td> 
                        <td>{{ $pengajuan->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <a href="{{ route('staff.validasi.review', $pengajuan->id) }}" class="btn-review">
                                <i class="fa-solid fa-search"></i> Review Paket
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #777;">
                            Tidak ada paket pengajuan yang menunggu validasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-staff-layout>