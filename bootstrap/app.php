<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth; // <-- Pastikan ini ada

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Mendaftarkan alias 'role' kita (seperti sebelumnya)
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // 2. SOLUSI: Memberi tahu 'guest' middleware ke mana harus redirect
        // Ini adalah pengganti file RedirectIfAuthenticated.php
        $middleware->redirectUsersTo(function ($request) {
            $user = Auth::user();
            
            if ($user->hasRole('mahasiswa')) {
                return route('mahasiswa.dashboard');
            }
            if ($user->hasRole('dosen')) {
                return route('dosen.dashboard');
            }
            if ($user->hasRole('staff')) {
                return route('staff.dashboard');
            }
            if ($user->hasRole('admin')) {
                return route('admin.dashboard');
            }

            // Fallback jika tidak punya peran
            return route('login');
        });

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
