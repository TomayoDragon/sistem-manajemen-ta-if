<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat 1 Akun Mahasiswa (yang bisa kita pakai login)
        // Kita buat profilnya dulu
        $mhs_james = \App\Models\Mahasiswa::factory()->create([
            'nrp' => '160421001',
            'nama_lengkap' => 'James Dharmawan',
        ]);
        
        // Lalu buat User-nya
        User::factory()->create([
            'mahasiswa_id' => $mhs_james->id,
            'login_id' => $mhs_james->nrp,
            'email' => '160421001@student.ubaya.ac.id',
            'password' => Hash::make('password123'), // Password yang kita tahu
        ]);

        // 2. Buat 1 Akun Dosen
        $dosen_joko = \App\Models\Dosen::factory()->create([
            'npk' => '12345678',
            'nama_lengkap' => 'Dr. Joko Siswantoro',
        ]);
        
        User::factory()->create([
            'dosen_id' => $dosen_joko->id,
            'login_id' => $dosen_joko->npk,
            'email' => 'joko@ubaya.ac.id',
            'password' => Hash::make('password123'),
        ]);

        // 3. Buat 1 Akun Staff
        $staff_duladi = \App\Models\Staff::factory()->create([
            'npk' => '87654321',
            'nama_lengkap' => 'Duladi',
        ]);
        
        User::factory()->create([
            'staff_id' => $staff_duladi->id,
            'login_id' => $staff_duladi->npk,
            'email' => 'duladi@ubaya.ac.id',
            'password' => Hash::make('password123'),
        ]);

        // 4. Buat 1 Akun Admin
        $admin_super = \App\Models\Admin::factory()->create([
            'username' => 'admin',
            'nama_lengkap' => 'Super Admin',
        ]);
        
        User::factory()->create([
            'admin_id' => $admin_super->id,
            'login_id' => $admin_super->username,
            'email' => 'admin@sistem.id',
            'password' => Hash::make('admin123'), // Password beda
        ]);


        // 5. Buat 5 user mahasiswa random
        User::factory(5)->mahasiswa()->create();

        // 6. Buat 2 user dosen random
        User::factory(2)->dosen()->create();
    }
}