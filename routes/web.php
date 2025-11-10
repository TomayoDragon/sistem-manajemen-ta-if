<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\DashboardController; // <-- TAMBAHKAN INI

Route::get('/', function () {
    return redirect()->route('login');
});



Route::middleware(['auth'])->group(function () {

    // --- GRUP MAHASISWA ---
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        
        // Arahkan /dashboard ke DashboardController
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Rute dummy untuk link sidebar lainnya (agar tidak error 404)
        Route::get('/upload', function () {
            // Nanti kita akan buat view untuk ini
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
        Route::get('/dashboard', function () {
            return 'Ini adalah Dashboard Dosen.';
        })->name('dashboard');
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

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';