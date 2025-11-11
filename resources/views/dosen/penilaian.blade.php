<x-dosen-layout>
    <x-slot name="title">
        Form Penilaian {{ Str::upper($type) }}
    </x-slot>

    <style>
        /* ... (Semua style .detail-grid, .detail-box, .info-item, dll. tetap sama) ... */
        .detail-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; }
        .detail-box { background-color: #fff; border-radius: 8px; border: 1px solid #e0e0e0; padding: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .detail-box h3 { font-size: 1.3rem; font-weight: 700; color: #333; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .info-item { margin-bottom: 15px; }
        .info-label { display: block; font-size: 0.9rem; color: #777; margin-bottom: 4px; }
        .info-value { font-weight: 700; color: #333; font-size: 1.1rem; }
        
        /* Style Link Download (baru) */
        .file-list-header { font-size: 1.1rem; font-weight: 700; color: #333; margin-top: 25px; margin-bottom: 10px; }
        .file-list a { display: block; padding: 12px 15px; background-color: #f4f7f6; border: 1px solid #ddd; border-radius: 8px; text-decoration: none; color: #0a2e6c; font-weight: 700; font-size: 0.9rem; margin-bottom: 8px; transition: background-color 0.2s; }
        .file-list a:hover { background-color: #eef5ff; }
        .file-list a i { margin-right: 10px; color: #3498db; }
        
        /* ... (Style Form Penilaian, .form-group, .btn-submit tetap sama) ... */
    </style>

    <h1 class="content-title">Form Penilaian {{ Str::upper($type) }}</h1>

    <div class="detail-grid">
        <div class="detail-box">
            <h3>Informasi Mahasiswa</h3>
            <div class="info-item"> <span class="info-label">Mahasiswa:</span> <span class="info-value">{{ $event->tugasAkhir->mahasiswa->nama_lengkap }}</span> </div>
            <div class="info-item"> <span class="info-label">NRP:</span> <span class="info-value">{{ $event->tugasAkhir->mahasiswa->nrp }}</span> </div>
            <div class="info-item"> <span class="info-label">Judul TA:</span> <span class="info-value">{{ $event->tugasAkhir->judul }}</span> </div>
            <div class="info-item"> <span class="info-label">Jadwal:</span> <span class="info-value">{{ \Carbon\Carbon::parse($event->jadwal)->format('d M Y, H:i') }}</span> </div>
            <div class="info-item"> <span class="info-label">Ruangan:</span> <span class="info-value">{{ $event->ruangan }}</span> </div>

            @if ($event->pengajuanSidang && $event->pengajuanSidang->dokumen->isNotEmpty())
                <h4 class="file-list-header">Berkas Mahasiswa</h4>
                <div class="file-list">
                    @foreach ($event->pengajuanSidang->dokumen as $dokumen)
                        <a href="{{ route('dokumen.download', $dokumen->id) }}" target="_blank">
                            <i class="fa-solid fa-file-pdf"></i>
                            {{ $dokumen->tipe_dokumen }} ({{ $dokumen->nama_file_asli }})
                        </a>
                    @endforeach

                    <a href="{{ route('integritas.show', $event->pengajuanSidang->id) }}" 
                       target="_blank"
                       style="background-color: #eef5ff; border-color: #3498db; margin-top: 15px;">
                        <i class="fa-solid fa-shield-halved"></i> Cek Integritas Dokumen
                    </a>
                </div>
            @endif
            </div>

        <div class="detail-box">
            <h3>Lembar Penilaian</h3>
            <form action="{{ route('dosen.penilaian.store', ['type' => $type, 'id' => $event->id]) }}" method="POST">
                @csrf
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-save"></i>
                    Simpan Penilaian
                </button>
            </form>
        </div>
    </div>
</x-dosen-layout>