<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $dosen = User::updateOrCreate(
            ['email' => 'budi.santoso@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'nomor_induk' => '0412058901',
            ]
        );

        $mahasiswa = User::updateOrCreate(
            ['email' => 'raihan@celoe.test'],
            [
                'name' => 'Ahmad Raihan Arifinza',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1302220001',
            ]
        );

        $course = MataKuliah::updateOrCreate(
            ['kode_mk' => 'PAW'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mempelajari pengembangan aplikasi web berbasis framework Laravel, mulai dari routing, controller, view, hingga operasi CRUD dengan database.',
                'sks' => 3,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        $course->students()->syncWithoutDetaching([
            $mahasiswa->id => ['status' => 'approved'],
        ]);

        $assignment = Assignment::updateOrCreate(
            ['course_id' => $course->id, 'judul' => 'Tugas 01 - Bikin Sistem Kasir pake Laravel'],
            [
                'deskripsi' => 'Buatlah sistem kasir sederhana menggunakan Laravel yang memuat operasi CRUD produk dan transaksi.',
                'deadline' => '2026-11-30 23:59:00',
            ]
        );
        
        Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => $mahasiswa->id],
            [
                'jawaban' => 'Sistem kasir sudah dibuat dengan fitur CRUD produk dan transaksi. Source code ada di repository GitHub.',
                'submitted_at' => now(),
            ]
        );
    }
}
