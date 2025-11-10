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

    // Rute untuk Mahasiswa
    Route::get('/mahasiswa/dashboard', function () {
        return 'Ini adalah Dashboard Mahasiswa. (Halaman 404 sudah hilang!)';
    })->name('mahasiswa.dashboard'); // Kita beri nama untuk nanti

    // Rute untuk Dosen
    Route::get('/dosen/dashboard', function () {
        return 'Ini adalah Dashboard Dosen.';
    })->name('dosen.dashboard');

    // Rute untuk Staff
    Route::get('/staff/dashboard', function () {
        return 'Ini adalah Dashboard Staff.';
    })->name('staff.dashboard');

    // Rute untuk Admin
    Route::get('/admin/dashboard', function () {
        return 'Ini adalah Dashboard Admin.';
    })->name('admin.dashboard');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
