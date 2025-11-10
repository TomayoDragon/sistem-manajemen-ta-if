<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Mahasiswa\UploadController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\ValidasiController;
use App\Http\Controllers\Mahasiswa\SidangController; // <-- TAMBAHKAN IMPORT INI
use App\Http\Controllers\Staff\JadwalController;
use App\Http\Controllers\Dosen\PenilaianController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute utama ('/') mengarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});


// Grup utama untuk SEMUA user yang sudah login
Route::middleware(['auth'])->group(function () {

    // --- GRUP MAHASISWA ---
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/upload', [UploadController::class, 'create'])->name('upload');
        Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');

        // GANTI RUTE INI:
        // Route::get('/sidang', function () {
        //     return 'Halaman Sidang / LSTA (Soon)';
        // })->name('sidang');

        // MENJADI INI:
        Route::get('/sidang', [SidangController::class, 'index'])->name('sidang');

        Route::get('/signature', function () {
            return 'Halaman Digital Signature (Soon)';
        })->name('signature');
    });

    // --- GRUP DOSEN ---

    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');

        // --- TAMBAHKAN RUTE BARU INI ---
        // {type} akan berisi 'lsta' atau 'sidang'
        // {id} akan berisi ID dari LSTA atau Sidang tsb
        Route::get('/penilaian/{type}/{id}', [PenilaianController::class, 'show'])
             ->name('penilaian.show');
        Route::post('/penilaian/{type}/{id}', [PenilaianController::class, 'store'])
             ->name('penilaian.store');
        
    });

    // --- GRUP STAFF ---
    Route::middleware(['role:staff'])->prefix('staff')->name('staff.')->group(function () {

        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

        // Rute Validasi
        Route::get('/validasi/{id}/review', [ValidasiController::class, 'show'])->name('validasi.review');
        Route::post('/validasi/{id}/process', [ValidasiController::class, 'process'])->name('validasi.process');
        Route::get('/validasi/{pengajuan}/download/{tipe}', [ValidasiController::class, 'downloadFile'])->name('validasi.download');

        // --- TAMBAHKAN RUTE BARU INI ---
        // Rute untuk menampilkan form "Buat Jadwal"
        Route::post('/jadwal/generate-all', [JadwalController::class, 'generateAll'])
            ->name('jadwal.generate');

    });

    // --- GRUP ADMIN ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return 'Ini adalah Dashboard Admin.';
        })->name('dashboard');
    });

    // --- RUTE PROFIL (Bawaan Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


// Memuat rute otentikasi (login, logout, lupa password, dll.)
require __DIR__ . '/auth.php';