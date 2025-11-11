<x-mahasiswa-layout>
    <x-slot name="title">
        Digital Signature
    </x-slot>

    <style>
        .table-wrapper {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-wrapper th,
        .table-wrapper td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        .table-wrapper th {
            background-color: #0a2e6c;
            color: white;
            font-weight: 700;
        }

        .hash-cell {
            font-family: monospace;
            font-size: 0.9rem;
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn-check {
            padding: 5px 12px;
            font-size: 0.9rem;
            text-decoration: none;
            color: white;
            background-color: #0a2e6c;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>

    <h1 class="content-title">Berkas Telah Ditandatangani</h1>

    <div class="content-box">
        <p>Berikut adalah daftar semua berkas yang telah Anda tandatangani secara digital saat proses upload.</p>

        <table class="table-wrapper">
            <thead>
                <tr>
                    <th>Nama File</th>
                    <th>Tipe Dokumen</th>
                    <th>Tanggal Tanda Tangan</th>
                    <th>Hash (Tersimpan)</th>
                    <th>Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dokumenTertanda as $dokumen)
                    <tr>
                        <td>{{ $dokumen->nama_file_asli }}</td>
                        <td>{{ $dokumen->tipe_dokumen }}</td>
                        <td>{{ $dokumen->created_at->format('d M Y, H:i') }}</td>
                        <td class="hash-cell" title="{{ $dokumen->hash_combined }}">
                            {{ $dokumen->hash_combined }}
                        </td>
                        <td>
                            <a href="{{ route('integritas.show', $dokumen->id) }}" class="btn-check" target="_blank">
                                <i class="fa-solid fa-shield-halved"></i> Cek
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #777;">
                            Anda belum memiliki berkas yang ditandatangani.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-mahasiswa-layout>