<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dosen.
     */
    public function index()
    {
        // Ambil data user dosen yang sedang login
        $dosen = Auth::user()->dosen;

        // Kirim data dosen ke view
        return view('dosen.dashboard', [
            'dosen' => $dosen,
        ]);
    }
}