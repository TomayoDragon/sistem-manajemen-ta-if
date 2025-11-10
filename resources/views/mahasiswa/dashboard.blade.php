<x-mahasiswa-layout>
    <x-slot name="title">
        Dashboard
    </x-slot>

    <style>
        .ta-info-box h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }
        .ta-info-box .info-grid {
            display: grid;
            /* Kita buat 1 kolom agar rapi saat ada 2 dosbing */
            grid-template-columns: 1fr; 
            gap: 15px;
        }
        .ta-info-box .info-item {
            font-size: 1rem;
        }
        .ta-info-box .info-label {
            display: block;
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 4px;
        }
        .ta-info-box .info-value {
            font-weight: 700;
            color: #333;
        }
        .no-ta-box {
            text-align: center;
            padding: 40px;
            color: #777;
            font-size: 1.1rem;
        }
    </style>

    <h1 class="content-title">Informasi Tugas Akhir</h1>

    <div class="content-box ta-info-box">

        @if ($tugasAkhir)
            
            <h3>{{ $tugasAkhir->judul }}</h3>
            
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Dosen Pembimbing 1:</span>
                    <span class="info-value">{{ $tugasAkhir->dosenPembimbing1->nama_lengkap }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Dosen Pembimbing 2:</span>
                    <span class="info-value">{{ $tugasAkhir->dosenPembimbing2->nama_lengkap }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value">{{ $tugasAkhir->status }}</span>
                </div>
            </div>

        @else

            <div class="no-ta-box">
                <p>Anda belum memiliki data Tugas Akhir yang aktif.</p>
            </div>

        @endif
    </div>

</x-mahasiswa-layout>