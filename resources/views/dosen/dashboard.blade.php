<x-dosen-layout>
    <x-slot name="title">
        Dashboard Dosen
    </x-slot>

    <h1 class="content-title">Dashboard Dosen</h1>

    <div class="content-box">
        <p>
            Selamat datang, {{ $dosen->nama_lengkap }}.
            <br>
            Halaman ini masih dalam pengembangan.
        </p>
    </div>

</x-dosen-layout>