<?php
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SignatureService;

class KeyGenerationController extends Controller
{
    public function store(Request $request, SignatureService $signatureService)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        // Pastikan belum punya kunci
        if ($mahasiswa->public_key) {
            return redirect()->route('profile.edit')->with('status', 'key-exists');
        }

        // Panggil Service untuk buat & simpan kunci
        $signatureService->generateAndStoreKeys($mahasiswa);

        return redirect()->route('profile.edit')->with('status', 'keys-generated');
    }
}