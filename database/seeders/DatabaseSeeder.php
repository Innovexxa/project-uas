<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Poli;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin & User Test agar login tidak error lagi
        User::create([
            'name' => 'Admin Klinik',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'User Biasa',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        // 2. Buat Daftar Poli
        $polis = [
            ['name' => 'Poli Umum'],
            ['name' => 'Poli Gigi'],
            ['name' => 'Poli Anak'],
            ['name' => 'Poli Penyakit Dalam'],
            ['name' => 'Poli Kandungan']
        ];

        $poliIds = [];
        foreach ($polis as $p) {
            $createdPoli = Poli::create($p);
            $poliIds[] = $createdPoli->id;
        }

        // 3. Buat 20 Dokter Otomatis
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        for ($i = 1; $i <= 20; $i++) {
            Doctor::create([
                'poli_id' => $poliIds[array_rand($poliIds)], // Pilih poli secara acak
                'name' => 'dr. ' . fake()->name(), // Nama dokter random
                'schedule_day' => $days[array_rand($days)], // Hari acak
                'start_time' => '08:00',
                'end_time' => '12:00'
            ]);
        }
    }
}
