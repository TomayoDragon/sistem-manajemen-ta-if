<x-mahasiswa-layout>
    <x-slot name="title">
        Digital Signature
    </x-slot>

    <style>
        .table-wrapper {
            width: 100%; border-collapse: collapse; margin-top: 20px;
        }
        .table-wrapper th, .table-wrapper td {
            border: 1px solid #ddd; padding: 12px 15px; text-align: left;
        }
        .table-wrapper th {
            background-color: #0a2e6c; color: white; font-weight: 700;
        }
        .hash-cell {
            font-family: monospace; font-size: 0.9rem; max-width: 250px;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .btn-check {
            padding: 5px 12px; font-size: 0.9rem; text-decoration: none;
            color: white; background-color: #0a2e6c;
            border: none; border-radius: 5px; cursor: pointer;
        }
        .btn-tambah {
            padding: 10px 20px; font-size: 1rem; text-decoration: none;
            color: white; background-color: #0a2e6c;
            border: none; border-radius: 8px; cursor: pointer;
        }
        .search-bar { display: flex; margin-bottom: 20px; }
        .search-bar input[type="text"] {
            flex-grow: 1; padding: 10px 15px; font-size: 1rem; border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
        }
        .search-bar button {
            padding: 10px 15px; background-color: #0a2e6c; color: white;
            border: none; border-radius: 0 5px 5px 0; cursor: pointer;
        }
        .header-flex {
            display: flex; justify-content: space-between; align-items: center;
        }
        .pagination-links { margin-top: 20px; }
    </style>

    <div class="header-flex">
        <h1 class="content-title">Berkas Ditandatangani</h1>
        
        <a href="#" class="btn-tambah" style="background-color: #ccc; cursor: not-allowed;" 
           title="Upload & Tanda Tangan dilakukan di Halaman Upload Berkas TA">
            <i class="fa-solid fa-plus"></i> Tambah
        </a>
    </div>

    <div class="content-box">
        <form method="GET" action="{{ route('mahasiswa.signature') }}" class="search-bar">
            <input type="text" name="search" placeholder="Cari nama file atau tipe..." 
                   value="{{ $searchQuery ?? '' }}">
            <button type="submit">
                <i class="fa-solid fa-search"></i> Cari
            </button>
        </form>

        <table class="table-wrapper">
            <thead>
                <tr>
                    <th>Nama File (Download)</th>
                    <th>Tipe</th>
                    <th>Tanggal Tanda Tangan</th>
                    <th>Hash (Tersimpan)</th>
                    <th>Aksi (Verifikasi)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dokumenTertanda as $dokumen)
                    <tr>
                        <td>
                            <a href="{{ route('dokumen.download', $dokumen->id) }}" target="_blank" 
                               style="color: #0a2e6c; text-decoration: none; font-weight: 600;">
                                {{ $dokumen->nama_file_asli }}
                            </a>
                        </td>
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
                            Tidak ada berkas yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-links">
            {{ $dokumenTertanda->links('pagination::bootstrap-4') }}
        </div>
    </div>

</x-mahasiswa-layout>