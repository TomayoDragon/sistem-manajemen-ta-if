<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;       // <-- PASTIKAN INI ADA
use App\Models\TugasAkhir;  // <-- PASTIKAN INI ADA
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================================
        // LANGKAH 1: BUAT SEMUA DOSEN TERLEBIH DAHULU
        // (Sangat penting agar logic `inRandomOrder` tidak gagal)
        // ==========================================================

        // Buat Dosen Joko (Dosbing 1 James)
        $dosen_joko = Dosen::factory()->create([
            'npk' => '12345678',
            'nama_lengkap' => 'Dr. Joko Siswantoro',
        ]);
        User::factory()->create([
            'dosen_id' => $dosen_joko->id,
            'login_id' => $dosen_joko->npk,
            'email' => 'joko@ubaya.ac.id',
            'password' => Hash::make('password123'),
        ]);

        // Buat Dosen Ahmad (Dosbing 2 James)
        $dosen_ahmad = Dosen::factory()->create([
            'npk' => '11223344',
            'nama_lengkap' => 'Ahmad Miftah Fajrin, M.Kom.',
        ]);
        User::factory()->create([
            'dosen_id' => $dosen_ahmad->id,
            'login_id' => $dosen_ahmad->npk,
            'email' => 'ahmad@ubaya.ac.id',
            'password' => Hash::make('password123'),
        ]);

        // Buat 2 user dosen random (agar ada total 4 dosen di DB)
        User::factory(2)->dosen()->create();


        // ==========================================================
        // LANGKAH 2: BUAT MAHASISWA & ROLE LAIN
        // ==========================================================

        // Buat Mahasiswa James
        $mhs_james = \App\Models\Mahasiswa::factory()->create([
            'nrp' => '160421001',
            'nama_lengkap' => 'James Dharmawan',
        ]);
        User::factory()->create([
            'mahasiswa_id' => $mhs_james->id,
            'login_id' => $mhs_james->nrp,
            'email' => '160421001@student.ubaya.ac.id',
            'password' => Hash::make('password123'),
        ]);

        // Buat Staff Duladi
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

        // Buat Admin
        $admin_super = \App\Models\Admin::factory()->create([
            'username' => 'admin',
            'nama_lengkap' => 'Super Admin',
        ]);
        User::factory()->create([
            'admin_id' => $admin_super->id,
            'login_id' => $admin_super->username,
            'email' => 'admin@sistem.id',
            'password' => Hash::make('admin123'),
        ]);


        // ==========================================================
        // LANGKAH 3: BUAT DATA TUGAS AKHIR
        // ==========================================================

        // KODE YANG HILANG: Buat TA spesifik untuk James
        TugasAkhir::factory()->create([
            'mahasiswa_id' => $mhs_james->id,
            'judul' => 'Pembuatan Sistem Manajemen Berkas Tugas Akhir Dengan Digital Signature',
            'dosen_pembimbing_1_id' => $dosen_joko->id,
            'dosen_pembimbing_2_id' => $dosen_ahmad->id,
            'status' => 'Bimbingan',
        ]);

        User::factory(5)->mahasiswa()->withTugasAkhir()->create();
        User::factory(5)->mahasiswa()->create();
    }
}