<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IntegritasController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\UploadController;
use App\Http\Controllers\Mahasiswa\SidangController;
use App\Http\Controllers\Mahasiswa\DigitalSignatureController;
use App\Http\Controllers\Mahasiswa\BeritaAcaraController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\PenilaianController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\ValidasiController;
use App\Http\Controllers\Staff\JadwalController; // <-- KEMBALIKAN IMPORT INI
use App\Http\Controllers\Staff\ArsipController;
use App\Http\Controllers\Mahasiswa\KeyGenerationController;
// HAPUS IMPORT 'JadwalImportController'

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
        Route::get('/sidang', [SidangController::class, 'index'])->name('sidang');
        Route::get('/sidang/{sidang}/berita-acara', [BeritaAcaraController::class, 'show'])->name('sidang.berita-acara');
        Route::get('/signature', [DigitalSignatureController::class, 'index'])->name('signature');
    });

    // --- GRUP DOSEN ---
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
        Route::get('/penilaian/{type}/{id}', [PenilaianController::class, 'show'])->name('penilaian.show');
        Route::post('/penilaian/{type}/{id}', [PenilaianController::class, 'store'])->name('penilaian.store');
    });

    // --- GRUP STAFF ---
    Route::middleware(['role:staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
        Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
        Route::get('/arsip/{tugasAkhir}/detail', [ArsipController::class, 'show'])->name('arsip.show');
        Route::get('/validasi/{id}/review', [ValidasiController::class, 'show'])->name('validasi.review');
        Route::post('/validasi/{id}/process', [ValidasiController::class, 'process'])->name('validasi.process');

        // HAPUS RUTE IMPORT EXCEL
        // Route::get('/jadwal/import', ...)->name('jadwal.import.form');
        // Route::post('/jadwal/import', ...)->name('jadwal.import.process');

        // KEMBALIKAN RUTE AUTO-GENERATE:
        Route::post('/jadwal/generate-all', [JadwalController::class, 'generateAll'])->name('jadwal.generate');
    });

    // --- GRUP ADMIN ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return 'Ini adalah Dashboard Admin.';
        })->name('dashboard');
    });

    // --- RUTE GLOBAL (UNTUK SEMUA ROLE) ---
    Route::get('/integritas/{dokumen}', [IntegritasController::class, 'show'])->name('integritas.show');
    Route::post('/integritas/{dokumen}', [IntegritasController::class, 'verify'])->name('integritas.verify');
    Route::get('/dokumen/{dokumen}/download', [DokumenController::class, 'download'])->name('dokumen.download');

    // Rute Profil Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Memuat rute otentikasi
require __DIR__ . '/auth.php';