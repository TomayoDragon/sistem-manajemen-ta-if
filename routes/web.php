<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // --- GRUP MAHASISWA ---
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

        Route::get('/dashboard', function () {
            return 'Ini adalah Dashboard Mahasiswa. (Halaman 404 sudah hilang!)';
        })->name('dashboard');

        // Nanti rute mahasiswa lain (misal: upload berkas) bisa ditaruh di sini
        // Route::get('/upload', ...)->name('upload');

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

require __DIR__ . '/auth.php';
