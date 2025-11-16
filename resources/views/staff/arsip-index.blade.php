<x-staff-layout>
    <x-slot name="title">
        Arsip Tugas Akhir
    </x-slot>

    <style>
        .table-wrapper { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table-wrapper th, .table-wrapper td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .table-wrapper th { background-color: #f4f4f4; font-weight: 700; }
        
        .filter-bar { 
            display: grid;
            grid-template-columns: 2fr 1fr; /* 2 kolom: search dan dropdown */
            gap: 15px;
            margin-bottom: 20px;
        }
        .search-box { display: flex; }
        .search-box input[type="text"] {
            flex-grow: 1; padding: 10px 15px; font-size: 1rem; border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
        }
        .search-box button {
            padding: 10px 15px; background-color: #0a2e6c; color: white;
            border: none; border-radius: 0 5px 5px 0; cursor: pointer;
        }
        .filter-dropdown select {
            width: 100%;
            padding: 10px 15px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }
        .btn-detail {
            padding: 5px 12px; font-size: 0.9rem; text-decoration: none;
            color: white; background-color: #3498db; border: none;
            border-radius: 5px; cursor: pointer;
        }
        .pagination-links { margin-top: 20px; }
    </style>

    <h1 class="content-title">Arsip Tugas Akhir</h1>

    <div class="content-box">
        
        <form method="GET" action="{{ route('staff.arsip.index') }}" class="filter-bar">
            <div class="search-box">
                <input type="text" name="search" placeholder="Cari NRP, Nama, atau Judul TA..." 
                       value="{{ $searchQuery ?? '' }}">
                <button type="submit">
                    <i class="fa-solid fa-search"></i> Cari
                </button>
            </div>
            
            <div class="filter-dropdown">
                <select name="periode_id" onchange="this.form.submit()">
                    <option value="">Semua Periode</option>
                    
                    @foreach ($periodes as $periode)
                        <option value="{{ $periode->id }}" 
                                {{ (string)$selectedPeriodeId == (string)$periode->id ? 'selected' : '' }}>
                            {{ $periode->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <table class="table-wrapper">
            <thead>
                <tr>
                    <th>NRP/Nama</th>
                    <th>Judul Tugas Akhir</th>
                    <th>Pembimbing 1 & 2</th>
                    <th>Status TA</th>
                    <th>Periode</th> <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($arsipTugasAkhir as $ta)
                    <tr>
                        <td>
                            <strong>{{ $ta->mahasiswa->nama_lengkap }}</strong><br>
                            <small>{{ $ta->mahasiswa->nrp }}</small>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($ta->judul, 60) }}</td>
                        <td>
                            1. {{ $ta->dosenPembimbing1->nama_lengkap }}<br>
                            2. {{ $ta->dosenPembimbing2->nama_lengkap }}
                        </td>
                        <td>
                            <span style="font-weight: 700;">
                                {{ $ta->status }}
                            </span>
                        </td>
                        <td>
                            {{ $ta->periode->nama ?? 'N/A' }}
                        </td>
                        <td>
                            <a href="{{ route('staff.arsip.show', $ta->id) }}" class="btn-detail">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #777;">
                            Tidak ada Tugas Akhir yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-links">
            {{ $arsipTugasAkhir->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</x-staff-layout>   