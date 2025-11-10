<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\DashboardController; // <-- Import Controller
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;

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

        Route::get('/upload', function () {
            return 'Halaman Upload Berkas TA (Soon)';
        })->name('upload');

        Route::get('/sidang', function () {
            return 'Halaman Sidang / LSTA (Soon)';
        })->name('sidang');

        Route::get('/signature', function () {
            return 'Halaman Digital Signature (Soon)';
        })->name('signature');
    });

    // --- GRUP DOSEN ---
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {

        // GANTI RUTE INI:
        // Route::get('/dashboard', function () {
        //     return 'Ini adalah Dashboard Dosen.';
        // })->name('dashboard');

        // MENJADI INI:
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');

    });
    // --- GRUP STAFF ---
    Route::middleware(['role:staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', function () {
            return 'Ini adalah Dashboard Staff.';
        })->name('dashboard');
    });

    // --- GRUP ADMIN ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return 'Ini adalah Dashboard Admin.';
        })->name('dashboard');
    });


    // --- RUTE PROFIL (Bawaan Breeze) ---
    // Kita letakkan di dalam grup 'auth' utama
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


// Memuat rute otentikasi (login, logout, lupa password, dll.)
require __DIR__ . '/auth.php';