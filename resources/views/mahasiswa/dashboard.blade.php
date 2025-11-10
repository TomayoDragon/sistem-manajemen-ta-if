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
            grid-template-columns: 1fr 1fr; /* 2 kolom */
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
    </style>

    <h1 class="content-title">Informasi Tugas Akhir</h1>

    <div class="content-box ta-info-box">
        <h3>{{ $tugasAkhir['judul'] }}</h3>
        
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Dosbing:</span>
                <span class="info-value">{{ $tugasAkhir['dosbing'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="info-value">{{ $tugasAkhir['status'] }}</span>
            </div>
        </div>
    </div>

</x-mahasiswa-layout>