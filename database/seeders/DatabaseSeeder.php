<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Tugas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@simakin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create OB Users
        User::create([
            'name' => 'OB Budi',
            'email' => 'ob1@simakin.com',
            'password' => Hash::make('password'),
            'role' => 'ob',
        ]);

        User::create([
            'name' => 'OB Siti',
            'email' => 'ob2@simakin.com',
            'password' => Hash::make('password'),
            'role' => 'ob',
        ]);

        // Create Ruangan
        $ruangans = [
            ['name' => 'Kelas 1A', 'description' => 'Kelas tingkat 1'],
            ['name' => 'Kelas 2B', 'description' => 'Kelas tingkat 2'],
            ['name' => 'Ruang Guru', 'description' => 'Ruangan untuk guru'],
            ['name' => 'Perpustakaan', 'description' => 'Perpustakaan sekolah'],
            ['name' => 'Toilet Lantai 1', 'description' => 'Toilet umum lantai 1'],
        ];

        foreach ($ruangans as $ruangan) {
            Ruangan::create($ruangan);
        }

        // Create Tugas
        $tugas = [
            ['name' => 'Menyapu lantai', 'description' => 'Membersihkan lantai dengan sapu'],
            ['name' => 'Mengepel lantai', 'description' => 'Mengepel lantai hingga bersih'],
            ['name' => 'Membersihkan meja', 'description' => 'Membersihkan permukaan meja'],
            ['name' => 'Membersihkan kursi', 'description' => 'Membersihkan kursi dari debu'],
            ['name' => 'Membersihkan papan tulis', 'description' => 'Menghapus dan membersihkan papan tulis'],
            ['name' => 'Membuang sampah', 'description' => 'Mengosongkan tempat sampah'],
        ];

        foreach ($tugas as $task) {
            Tugas::create($task);
        }
    }
}