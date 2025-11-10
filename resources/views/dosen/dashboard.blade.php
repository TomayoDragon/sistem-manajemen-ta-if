<x-dosen-layout>
    <x-slot name="title">
        Dashboard Dosen
    </x-slot>

    <style>
        .table-wrapper {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-wrapper th,
        .table-wrapper td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .table-wrapper th {
            background-color: #f4f4f4;
            font-weight: 700;
        }

        .btn-penilaian {
            padding: 5px 12px;
            font-size: 0.9rem;
            text-decoration: none;
            color: white;
            background-color: #0a2e6c;
            /* Biru IF */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>

    <h1 class="content-title">Jadwal Menguji LSTA</h1>
    <div class="content-box">
        <table class="table-wrapper">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Judul TA</th>
                    <th>Jadwal</th>
                    <th>Ruangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwalLsta as $lsta)
                    <tr>
                        <td>{{ $lsta->tugasAkhir->mahasiswa->nama_lengkap }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($lsta->tugasAkhir->judul, 40) }}</td>
                        <td>{{ \Carbon\Carbon::parse($lsta->jadwal)->format('d M Y, H:i') }}</td>
                        <td>{{ $lsta->ruangan }}</td>
                        <td>
                            <a href="{{ route('dosen.penilaian.show', ['type' => 'lsta', 'id' => $lsta->id]) }}"
                                class="btn-penilaian">
                                <i class="fa-solid fa-file-pen"></i> Beri Nilai
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #777;">
                            Anda tidak memiliki jadwal menguji LSTA.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h1 class="content-title" style="margin-top: 30px;">Jadwal Menguji Sidang TA</h1>
    <div class="content-box">
        <table class="table-wrapper">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Judul TA</th>
                    <th>Jadwal</th>
                    <th>Ruangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwalSidang as $sidang)
                    <tr>
                        <td>{{ $sidang->tugasAkhir->mahasiswa->nama_lengkap }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($sidang->tugasAkhir->judul, 40) }}</td>
                        <td>{{ \Carbon\Carbon::parse($sidang->jadwal)->format('d M Y, H:i') }}</td>
                        <td>{{ $sidang->ruangan }}</td>
                        <td>
                            <a href="{{ route('dosen.penilaian.show', ['type' => 'sidang', 'id' => $sidang->id]) }}"
                                class="btn-penilaian">
                                <i class="fa-solid fa-file-pen"></i> Beri Nilai
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #777;">
                            Anda tidak memiliki jadwal menguji Sidang TA.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h1 class="content-title" style="margin-top: 30px;">Mahasiswa Bimbingan</h1>
    <div class="content-box">
        <table class="table-wrapper">
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>NRP</th>
                    <th>Judul TA</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mahasiswaBimbingan as $ta)
                    <tr>
                        <td>{{ $ta->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $ta->mahasiswa->nrp }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($ta->judul, 50) }}</td>
                        <td>{{ $ta->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #777;">
                            Anda tidak memiliki mahasiswa bimbingan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-dosen-layout>