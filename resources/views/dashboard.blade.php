<x-staff-layout>
    <x-slot name="title">
        Dashboard Staf
    </x-slot>

    <style>
        .table-wrapper { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-wrapper th, .table-wrapper td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .table-wrapper th { background-color: #f4f4f4; font-weight: 700; }
        .btn-review {
            padding: 5px 12px; font-size: 0.9rem; text-decoration: none;
            color: white; background-color: #0a2e6c; border: none; border-radius: 5px; cursor: pointer;
        }
        /* Tombol Generate (Kembali) */
        .btn-generate-all {
            display: inline-block; padding: 12px 25px; font-size: 1rem; font-weight: 700;
            color: #fff; background-color: #5cb85c; /* Hijau */
            border: none; border-radius: 8px; cursor: pointer; margin-bottom: 20px;
        }
        /* Tombol Finalisasi (Orange) */
        .btn-finalize {
            padding: 5px 12px; font-size: 0.9rem; text-decoration: none;
            color: white; background-color: #e67e22; /* Oranye */
            border: none; border-radius: 5px; cursor: pointer;
        }
    </style>

    @if (session('success'))
        <div class="content-box" style="background-color: #eBffeb; color: #0a0; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="content-box" style="background-color: #ffebeB; color: #a00; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="content-title">Validasi Pengajuan Sidang Tertunda</h1>
    <div class="content-box">
        <table class="table-wrapper">
            <thead>
                <tr> <th>Mahasiswa</th> <th>NRP</th> <th>Tgl Pengajuan</th> <th>Aksi</th> </tr>
            </thead>
            <tbody>
                @forelse ($pendingPengajuans as $pengajuan)
                    <tr>
                        <td>{{ $pengajuan->tugasAkhir->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $pengajuan->tugasAkhir->mahasiswa->nrp }}</td>
                        <td>{{ $pengajuan->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <a href="{{ route('staff.validasi.review', $pengajuan->id) }}" class="btn-review">
                                <i class="fa-solid fa-search"></i> Review Paket
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr> <td colspan="4" style="text-align: center; color: #777;">Tidak ada paket pengajuan yang menunggu validasi.</td> </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h1 class="content-title" style="margin-top: 30px;">Pengajuan Disetujui (Siap Dijadwalkan)</h1>
    <div class="content-box">
        
        @if($acceptedPengajuans->count() > 0)
            <form action="{{ route('staff.jadwal.generate') }}" method="POST" 
                  onsubmit="return confirm('Anda yakin ingin men-generate jadwal untuk {{ $acceptedPengajuans->count() }} mahasiswa?');">
                @csrf
                <button type="submit" class="btn-generate-all">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    Auto-Generate Jadwal untuk Semua ({{ $acceptedPengajuans->count() }})
                </button>
            </form>
        @endif
        
        <table class="table-wrapper">
            <thead>
                <tr> <th>Mahasiswa</th> <th>NRP</th> <th>Tgl Disetujui</th> </tr>
            </thead>
            <tbody>
                @forelse ($acceptedPengajuans as $pengajuan)
                    <tr>
                        <td>{{ $pengajuan->tugasAkhir->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $pengajuan->tugasAkhir->mahasiswa->nrp }}</td>
                        <td>{{ $pengajuan->validated_at ? \Carbon\Carbon::parse($pengajuan->validated_at)->format('d M Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr> <td colspan="3" style="text-align: center; color: #777;">Tidak ada pengajuan yang siap dijadwalkan.</td> </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h1 class="content-title" style="margin-top: 30px;">Sidang Selesai (Menunggu Finalisasi Nilai)</h1>
    <div class="content-box">
        <table class="table-wrapper">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Judul TA</th>
                    <th>Tgl Sidang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sessionsToFinalize as $sidang)
                    <tr>
                        <td>{{ $sidang->tugasAkhir->mahasiswa->nama_lengkap }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($sidang->tugasAkhir->judul, 45) }}</td>
                        <td>{{ $sidang->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <form action="{{ route('staff.berita-acara.store', $sidang->id) }}" method="POST" 
                                  onsubmit="return confirm('Anda yakin ingin memfinalisasi nilai untuk sidang ini? Aksi ini tidak dapat diulang.');">
                                @csrf
                                <button type="submit" class="btn-finalize">
                                    <i class="fa-solid fa-calculator"></i> Finalisasi Nilai
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr> <td colspan="4" style="text-align: center; color: #777;">Tidak ada sidang yang menunggu finalisasi.</td> </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-staff-layout>