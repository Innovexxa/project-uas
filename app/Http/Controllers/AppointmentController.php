<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAppointmentRequest;

class AppointmentController extends Controller
{
    // Menampilkan Dashboard & Data Dokter
    public function index()
    {
        $doctors = Doctor::with('poli')->get();
        $myAppointments = Appointment::where('user_id', auth()->id())->get();

        return view('dashboard', compact('doctors', 'myAppointments'));
    }

    // Memproses Pendaftaran Antrian
   public function store(StoreAppointmentRequest $request)
{
    // 1. Ambil input dari form
    $tgl = $request->appointment_date;
    $dokterId = $request->doctor_id;

    // 2. CARI NOMOR TERAKHIR (Gunakan max agar akurat)
    // Mencari angka tertinggi pada kolom queue_number untuk dokter & tanggal yang sama
 // Cari nomor tertinggi di database untuk dokter dan tanggal yang sama
        $lastQueue = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->max('queue_number');

        // Jika ada (misal 1), maka 1 + 1 = 2. Jika tidak ada, mulai dari 1.
        $newNumber = $lastQueue ? (int)$lastQueue + 1 : 1;

    // 4. SIMPAN KE DATABASE
    \App\Models\Appointment::create([
        'user_id' => auth()->id(),
        'doctor_id' => $dokterId,
        'appointment_date' => $tgl,
        'symptoms' => $request->symptoms,
        'queue_number' => $newNumber,
        'status' => 'WAITING',
    ]);

    // 5. REDIRECT dengan pesan sukses
    return redirect()->route('dashboard')
        ->with('success', "Berhasil mendaftar! Nomor antrian Anda: $newNumber");
}
   public function destroy($id)
{
    $appointment = Appointment::where('id', $id)
        ->where('user_id', auth()->id()) // Keamanan: agar user tidak hapus antrian orang lain
        ->firstOrFail();

    $appointment->delete();

    return redirect()->route('dashboard')->with('success', 'Antrian berhasil dibatalkan.');
}
}
