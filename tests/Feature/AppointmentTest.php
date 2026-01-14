<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase; // WAJIB ADA
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase; // Memastikan DB kosong sebelum test jalan

    public function test_user_cannot_register_if_quota_full()
    {
        // 1. Persiapan Data (Setup)
        $poli = Poli::create(['name' => 'Poli Umum']);
        $doctor = Doctor::create([
            'poli_id' => $poli->id,
            'name' => 'dr. Smith',
            'schedule_day' => now()->format('l'), // Hari ini
            'start_time' => '08:00',
            'end_time' => '12:00'
        ]);

        $user = User::factory()->create(['role' => 'user']);

        // 2. Buat 20 antrian tiruan agar kuota penuh
        // Jika belum punya Factory, gunakan loop sederhana:
        for ($i = 1; $i <= 20; $i++) {
            Appointment::create([
                'user_id' => $user->id,
                'doctor_id' => $doctor->id,
                'appointment_date' => now()->toDateString(),
                'symptoms' => 'Gejala pemeriksaan rutin ' . $i,
                'queue_number' => $i,
                'status' => 'WAITING'
            ]);
        }

        // 3. Jalankan Test (Action)
        // Pastikan URL '/appointments' sesuai dengan route POST Anda
        $response = $this->actingAs($user)->post('/appointments', [
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->toDateString(),
            'symptoms' => 'Sakit kepala terus menerus'
        ]);

        // 4. Verifikasi (Assertion)
        $response->assertSessionHasErrors('doctor_id');
    }
}
